<?php

require_once __DIR__ . '/../models/File.php';
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../services/EmailService.php';
require_once __DIR__ . '/../middleware/Auth.php';

class FilesController
{
    private $fileModel;
    private $patientModel;
    private $notificationModel;
    private $userModel;
    private $companyModel;
    private $emailService;
    private $uploadDir;
    private $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
    private $maxFileSize = 10485760; // 10MB en bytes

    public function __construct()
    {
        Auth::requireAuth();
        Auth::checkTimeout();
        $this->fileModel = new File();
        $this->patientModel = new Patient();
        $this->notificationModel = new Notification();
        $this->userModel = new User();
        $this->companyModel = new Company();
        $this->emailService = new EmailService();
        $this->uploadDir = __DIR__ . '/../../public/uploads/patients/';

        // Crear directorio si no existe
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Subir archivo para un paciente
     */
    public function upload($idPaciente)
    {
        $this->validateCSRFToken();

        $patient = $this->patientModel->getById($idPaciente);

        if (!$patient) {
            setFlash('error', 'Paciente no encontrado.');
            redirect(baseUrl('patients'));
            return;
        }

        // Validar que se haya subido un archivo
        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] === UPLOAD_ERR_NO_FILE) {
            setFlash('error', 'Debe seleccionar un archivo.');
            redirect(baseUrl('patients/view/' . $idPaciente));
            return;
        }

        $file = $_FILES['archivo'];

        // Validar errores de subida
        if ($file['error'] !== UPLOAD_ERR_OK) {
            setFlash('error', 'Error al subir el archivo. Por favor intente nuevamente.');
            redirect(baseUrl('patients/view/' . $idPaciente));
            return;
        }

        // Validar tamaño
        if ($file['size'] > $this->maxFileSize) {
            setFlash('error', 'El archivo es demasiado grande. Máximo 10MB.');
            redirect(baseUrl('patients/view/' . $idPaciente));
            return;
        }

        // Obtener extensión
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Validar tipo de archivo
        if (!in_array($extension, $this->allowedTypes)) {
            setFlash('error', 'Tipo de archivo no permitido. Solo se permiten: ' . implode(', ', $this->allowedTypes));
            redirect(baseUrl('patients/view/' . $idPaciente));
            return;
        }

        // Generar nombre único
        $nombreArchivo = uniqid('file_' . $idPaciente . '_') . '.' . $extension;
        $rutaCompleta = $this->uploadDir . $nombreArchivo;

        // Mover archivo
        if (!move_uploaded_file($file['tmp_name'], $rutaCompleta)) {
            setFlash('error', 'Error al guardar el archivo en el servidor.');
            redirect(baseUrl('patients/view/' . $idPaciente));
            return;
        }

        // Guardar registro en BD
        $data = [
            'id_paciente' => $idPaciente,
            'nombre_archivo' => $nombreArchivo,
            'nombre_original' => $file['name'],
            'ruta' => 'uploads/patients/' . $nombreArchivo,
            'tipo' => $file['type'],
            'tamano' => $file['size'],
            'id_usuario_subio' => $_SESSION['user_id'],
            'descripcion' => trim($_POST['descripcion'] ?? '')
        ];

        $fileId = $this->fileModel->create($data);

        if ($fileId) {
            setFlash('success', 'Archivo subido exitosamente.');

            // Crear notificación para todos los usuarios
            $this->notificationModel->createFileUploadNotification(
                $idPaciente,
                $fileId,
                $file['name'],
                $_SESSION['user_id']
            );

            // Verificar si se debe enviar email del documento
            $enviarEmail = isset($_POST['enviar_email']) && $_POST['enviar_email'] === '1';
            $tipoDocumento = trim($_POST['tipo_documento'] ?? '');
            $asuntoEmail = trim($_POST['asunto_email'] ?? '');
            $comentarioEmail = trim($_POST['comentario_email'] ?? '');

            if ($enviarEmail && !empty($tipoDocumento)) {
                // Obtener empresas asociadas al paciente
                $empresas = $this->companyModel->getByPaciente($idPaciente);

                // Obtener emails de administradores para BCC
                $admins = $this->userModel->getAll(['estado' => 'activo', 'rol' => 'administrador']);
                $adminsEmails = [];

                foreach ($admins as $admin) {
                    if (!empty($admin['email'])) {
                        $adminsEmails[] = $admin['email'];
                    }
                }

                // Enviar email a cada empresa
                if (!empty($empresas)) {
                    try {
                        $emailsEnviados = 0;

                        foreach ($empresas as $empresa) {
                            if (!empty($empresa['email'])) {
                                $result = $this->emailService->sendDocumentToCompany(
                                    $empresa['email'],
                                    $empresa['nombre'],
                                    $patient['nombre_completo'],
                                    $tipoDocumento,
                                    $asuntoEmail,
                                    $comentarioEmail,
                                    $rutaCompleta,
                                    $adminsEmails
                                );

                                if ($result) {
                                    $emailsEnviados++;
                                }
                            }
                        }

                        if ($emailsEnviados > 0) {
                            setFlash('success', 'Archivo subido y email(s) enviado(s) a la(s) empresa(s) exitosamente.');
                        }
                    } catch (Exception $e) {
                        // Log el error pero no detener el proceso
                        error_log("Error al enviar email del documento: " . $e->getMessage());
                        setFlash('warning', 'Archivo subido, pero hubo un problema al enviar el email.');
                    }
                }
            }

        } else {
            // Si falla el registro en BD, eliminar archivo físico
            unlink($rutaCompleta);
            setFlash('error', 'Error al registrar el archivo.');
        }

        redirect(baseUrl('patients/view/' . $idPaciente));
    }

    /**
     * Descargar archivo
     */
    public function download($id)
    {
        $file = $this->fileModel->getById($id);

        if (!$file) {
            setFlash('error', 'Archivo no encontrado.');
            redirect(baseUrl('patients'));
            return;
        }

        $rutaCompleta = __DIR__ . '/../../public/' . $file['ruta'];

        if (!file_exists($rutaCompleta)) {
            setFlash('error', 'El archivo no existe en el servidor.');
            redirect(baseUrl('patients/view/' . $file['id_paciente']));
            return;
        }

        // Forzar descarga
        header('Content-Type: ' . $file['tipo']);
        header('Content-Disposition: attachment; filename="' . $file['nombre_original'] . '"');
        header('Content-Length: ' . filesize($rutaCompleta));
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: public');

        readfile($rutaCompleta);
        exit;
    }

    /**
     * Ver archivo en el navegador (para PDFs e imágenes)
     */
    public function view($id)
    {
        $file = $this->fileModel->getById($id);

        if (!$file) {
            setFlash('error', 'Archivo no encontrado.');
            redirect(baseUrl('patients'));
            return;
        }

        $rutaCompleta = __DIR__ . '/../../public/' . $file['ruta'];

        if (!file_exists($rutaCompleta)) {
            setFlash('error', 'El archivo no existe en el servidor.');
            redirect(baseUrl('patients/view/' . $file['id_paciente']));
            return;
        }

        // Mostrar en el navegador
        header('Content-Type: ' . $file['tipo']);
        header('Content-Disposition: inline; filename="' . $file['nombre_original'] . '"');
        header('Content-Length: ' . filesize($rutaCompleta));

        readfile($rutaCompleta);
        exit;
    }

    /**
     * Eliminar archivo
     */
    public function delete($id)
    {
        // Solo admin puede eliminar
        if (!hasRole('administrador')) {
            setFlash('error', 'No tiene permisos para realizar esta acción.');
            redirect(baseUrl('patients'));
            return;
        }

        $this->validateCSRFToken();

        $file = $this->fileModel->getById($id);

        if (!$file) {
            setFlash('error', 'Archivo no encontrado.');
            redirect(baseUrl('patients'));
            return;
        }

        $idPaciente = $file['id_paciente'];
        $rutaCompleta = __DIR__ . '/../../public/' . $file['ruta'];

        // Eliminar archivo físico
        if (file_exists($rutaCompleta)) {
            unlink($rutaCompleta);
        }

        // Eliminar registro de BD
        if ($this->fileModel->delete($id)) {
            setFlash('success', 'Archivo eliminado exitosamente.');
        } else {
            setFlash('error', 'Error al eliminar el archivo.');
        }

        redirect(baseUrl('patients/view/' . $idPaciente));
    }

    /**
     * Validar CSRF token
     */
    private function validateCSRFToken()
    {
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlash('error', 'Token de seguridad inválido.');
            redirect(baseUrl('patients'));
            exit;
        }
    }

    /**
     * Formatear tamaño de archivo
     */
    public static function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}

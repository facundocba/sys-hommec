<?php

require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/Professional.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../models/TipoPrestacion.php';
require_once __DIR__ . '/../models/ObraSocial.php';
require_once __DIR__ . '/../models/PrestacionPaciente.php';
require_once __DIR__ . '/../models/File.php';
require_once __DIR__ . '/../models/Frequency.php';
require_once __DIR__ . '/../models/Province.php';
require_once __DIR__ . '/../middleware/Auth.php';

class PatientsController
{
    private $patientModel;
    private $professionalModel;
    private $companyModel;
    private $serviceModel;
    private $tipoPrestacionModel;
    private $obraSocialModel;
    private $prestacionPacienteModel;
    private $fileModel;
    private $frequencyModel;
    private $provinceModel;

    public function __construct()
    {
        Auth::requireAuth();
        Auth::checkTimeout();
        $this->patientModel = new Patient();
        $this->professionalModel = new Professional();
        $this->companyModel = new Company();
        $this->serviceModel = new Service();
        $this->tipoPrestacionModel = new TipoPrestacion();
        $this->obraSocialModel = new ObraSocial();
        $this->frequencyModel = new Frequency();
        $this->prestacionPacienteModel = new PrestacionPaciente();
        $this->fileModel = new File();
        $this->provinceModel = new Province();
    }

    /**
     * Listado de pacientes
     */
    public function index()
    {
        // Obtener filtros
        $filters = [
            'search' => $_GET['search'] ?? '',
            'profesional' => $_GET['profesional'] ?? '',
            'empresa' => $_GET['empresa'] ?? '',
            'prestacion' => $_GET['prestacion'] ?? '',
            'obra_social' => $_GET['obra_social'] ?? '',
            'provincia' => $_GET['provincia'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'recurrente' => $_GET['recurrente'] ?? ''
        ];

        // Obtener pacientes
        $patients = $this->patientModel->getAll($filters);

        // Obtener datos para filtros
        $professionals = $this->professionalModel->getAll(['estado' => 'activo']);
        $companies = $this->companyModel->getAll(['estado' => 'activo']);
        $services = $this->tipoPrestacionModel->getAll(['estado' => 'activo']);
        $obrasSociales = $this->obraSocialModel->getAll(['estado' => 'activo']);
        $provinces = $this->provinceModel->getAll();

        // Renderizar vista
        $title = 'Gestión de Pacientes';
        include __DIR__ . '/../../views/patients/index.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        // Obtener datos para selects
        $professionals = $this->professionalModel->getAll(['estado' => 'activo']);
        $companies = $this->companyModel->getAll(['estado' => 'activo']);
        $services = $this->tipoPrestacionModel->getAll(['estado' => 'activo']);
        $obrasSociales = $this->obraSocialModel->getAll(['estado' => 'activo']);
        $frecuencias = $this->frequencyModel->getAll();
        $provinces = $this->provinceModel->getAll();

        $title = 'Nuevo Paciente';
        include __DIR__ . '/../../views/patients/create.php';
    }

    /**
     * Guardar nuevo paciente
     */
    public function store()
    {
        $this->validateCSRFToken();

        // Validar datos
        $errors = $this->validatePatientData($_POST);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('patients/create'));
            return;
        }

        // Si es paciente recurrente, fecha_finalizacion debe ser NULL
        $fechaFinalizacion = null;
        if (empty($_POST['paciente_recurrente']) && !empty($_POST['fecha_finalizacion'])) {
            $fechaFinalizacion = $_POST['fecha_finalizacion'];
        }

        // Guardar valores de prestación para crear en prestaciones_pacientes
        $id_profesional_temp = !empty($_POST['id_profesional']) ? $_POST['id_profesional'] : null;
        $id_empresa_temp = !empty($_POST['id_empresa']) ? $_POST['id_empresa'] : null;
        $id_prestacion_temp = !empty($_POST['id_prestacion']) ? $_POST['id_prestacion'] : null;

        // Preparar datos del paciente (sin prestación - se guarda en prestaciones_pacientes)
        $data = [
            'nombre_completo' => trim($_POST['nombre_completo']),
            'dni' => trim($_POST['dni'] ?? ''),
            'id_provincia' => !empty($_POST['id_provincia']) ? $_POST['id_provincia'] : null,
            'localidad' => trim($_POST['localidad'] ?? ''),
            'id_obra_social' => !empty($_POST['id_obra_social']) ? $_POST['id_obra_social'] : null,
            'frecuencia_servicio' => trim($_POST['frecuencia_servicio'] ?? ''),
            'id_profesional' => null, // No se guarda en pacientes, solo en prestaciones_pacientes
            'id_empresa' => null, // No se guarda en pacientes, solo en prestaciones_pacientes
            'id_prestacion' => null, // No se guarda en pacientes, solo en prestaciones_pacientes
            'fecha_ingreso' => $_POST['fecha_ingreso'],
            'fecha_finalizacion' => $fechaFinalizacion,
            'paciente_recurrente' => !empty($_POST['paciente_recurrente']) ? 1 : 0,
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'valor_profesional' => !empty($_POST['valor_profesional']) ? $_POST['valor_profesional'] : null,
            'valor_empresa' => !empty($_POST['valor_empresa']) ? $_POST['valor_empresa'] : null,
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Crear paciente
        $patientId = $this->patientModel->create($data);

        if ($patientId) {
            // Si se asignó profesional y prestación, crear automáticamente el registro en prestaciones_pacientes
            if (!empty($id_profesional_temp) && !empty($id_prestacion_temp)) {
                $prestacionData = [
                    'id_paciente' => $patientId,
                    'id_tipo_prestacion' => $id_prestacion_temp,
                    'id_profesional' => $id_profesional_temp,
                    'id_empresa' => $id_empresa_temp,
                    'fecha_inicio' => $data['fecha_ingreso'],
                    'fecha_fin' => $data['fecha_finalizacion'],
                    'es_recurrente' => $data['paciente_recurrente'],
                    'id_frecuencia' => !empty($_POST['id_frecuencia']) ? $_POST['id_frecuencia'] : null,
                    'sesiones_personalizadas' => !empty($_POST['sesiones_personalizadas']) ? $_POST['sesiones_personalizadas'] : null,
                    'frecuencia_servicio' => $data['frecuencia_servicio'],
                    'valor_profesional' => $data['valor_profesional'],
                    'valor_empresa' => $data['valor_empresa'],
                    'observaciones' => $data['observaciones'],
                    'estado' => 'activo'
                ];

                $this->prestacionPacienteModel->create($prestacionData);
            }

            setFlash('success', 'Paciente creado exitosamente.');
            redirect(baseUrl('patients'));
        } else {
            setFlash('error', 'Error al crear el paciente.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('patients/create'));
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $patient = $this->patientModel->getById($id);

        if (!$patient) {
            setFlash('error', 'Paciente no encontrado.');
            redirect(baseUrl('patients'));
            return;
        }

        // Obtener datos para selects
        $professionals = $this->professionalModel->getAll(['estado' => 'activo']);
        $companies = $this->companyModel->getAll(['estado' => 'activo']);
        $services = $this->tipoPrestacionModel->getAll(['estado' => 'activo']);
        $obrasSociales = $this->obraSocialModel->getAll(['estado' => 'activo']);
        $frecuencias = $this->frequencyModel->getAll();
        $provinces = $this->provinceModel->getAll();

        $title = 'Editar Paciente';
        include __DIR__ . '/../../views/patients/edit.php';
    }

    /**
     * Ver detalle del paciente y sus servicios
     */
    public function view($id)
    {
        $patient = $this->patientModel->getById($id);

        if (!$patient) {
            setFlash('error', 'Paciente no encontrado.');
            redirect(baseUrl('patients'));
            return;
        }

        // Obtener servicios del paciente
        $servicios = $this->prestacionPacienteModel->getByPaciente($id);

        // Obtener archivos del paciente
        $archivos = $this->fileModel->getByPaciente($id);

        $title = 'Detalle Paciente - ' . $patient['nombre_completo'];
        include __DIR__ . '/../../views/patients/view.php';
    }

    /**
     * Actualizar paciente
     */
    public function update($id)
    {
        $this->validateCSRFToken();

        $patient = $this->patientModel->getById($id);

        if (!$patient) {
            setFlash('error', 'Paciente no encontrado.');
            redirect(baseUrl('patients'));
            return;
        }

        // Validar datos
        $errors = $this->validatePatientData($_POST, $id);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('patients/edit/' . $id));
            return;
        }

        // Si es paciente recurrente, fecha_finalizacion debe ser NULL
        $fechaFinalizacion = null;
        if (empty($_POST['paciente_recurrente']) && !empty($_POST['fecha_finalizacion'])) {
            $fechaFinalizacion = $_POST['fecha_finalizacion'];
        }

        // Preparar datos
        $data = [
            'nombre_completo' => trim($_POST['nombre_completo']),
            'dni' => trim($_POST['dni'] ?? ''),
            'id_provincia' => !empty($_POST['id_provincia']) ? $_POST['id_provincia'] : null,
            'localidad' => trim($_POST['localidad'] ?? ''),
            'id_obra_social' => !empty($_POST['id_obra_social']) ? $_POST['id_obra_social'] : null,
            'frecuencia_servicio' => trim($_POST['frecuencia_servicio'] ?? ''),
            'id_profesional' => !empty($_POST['id_profesional']) ? $_POST['id_profesional'] : null,
            'id_empresa' => !empty($_POST['id_empresa']) ? $_POST['id_empresa'] : null,
            'id_prestacion' => !empty($_POST['id_prestacion']) ? $_POST['id_prestacion'] : null,
            'fecha_ingreso' => $_POST['fecha_ingreso'],
            'fecha_finalizacion' => $fechaFinalizacion,
            'paciente_recurrente' => !empty($_POST['paciente_recurrente']) ? 1 : 0,
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'valor_profesional' => !empty($_POST['valor_profesional']) ? $_POST['valor_profesional'] : null,
            'valor_empresa' => !empty($_POST['valor_empresa']) ? $_POST['valor_empresa'] : null,
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Actualizar paciente
        if ($this->patientModel->update($id, $data)) {
            setFlash('success', 'Paciente actualizado exitosamente.');
            redirect(baseUrl('patients'));
        } else {
            setFlash('error', 'Error al actualizar el paciente.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('patients/edit/' . $id));
        }
    }

    /**
     * Finalizar paciente (cambiar estado a finalizado)
     */
    public function finalize($id)
    {
        // Solo admin puede finalizar
        if (!hasRole('administrador')) {
            setFlash('error', 'No tiene permisos para realizar esta acción.');
            redirect(baseUrl('patients'));
            return;
        }

        $this->validateCSRFToken();

        if ($this->patientModel->finalize($id)) {
            setFlash('success', 'Paciente finalizado exitosamente.');
        } else {
            setFlash('error', 'Error al finalizar el paciente.');
        }

        redirect(baseUrl('patients'));
    }

    /**
     * Eliminar paciente permanentemente
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

        if ($this->patientModel->deletePermanently($id)) {
            setFlash('success', 'Paciente eliminado permanentemente.');
        } else {
            setFlash('error', 'Error al eliminar el paciente.');
        }

        redirect(baseUrl('patients'));
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
     * Validar datos de paciente
     */
    private function validatePatientData($data, $excludeId = null)
    {
        $errors = [];

        // Nombre completo
        if (empty($data['nombre_completo'])) {
            $errors['nombre_completo'] = 'El nombre completo es obligatorio.';
        } elseif (strlen($data['nombre_completo']) < 3) {
            $errors['nombre_completo'] = 'El nombre debe tener al menos 3 caracteres.';
        }

        // Fecha ingreso
        if (empty($data['fecha_ingreso'])) {
            $errors['fecha_ingreso'] = 'La fecha de ingreso es obligatoria.';
        }

        // Fecha finalización (solo si NO es recurrente)
        if (empty($data['paciente_recurrente']) && empty($data['fecha_finalizacion'])) {
            $errors['fecha_finalizacion'] = 'La fecha de finalización es obligatoria para pacientes no recurrentes.';
        }

        // Validar que fecha_finalizacion sea mayor a fecha_ingreso
        if (!empty($data['fecha_ingreso']) && !empty($data['fecha_finalizacion'])) {
            if (strtotime($data['fecha_finalizacion']) < strtotime($data['fecha_ingreso'])) {
                $errors['fecha_finalizacion'] = 'La fecha de finalización debe ser posterior a la fecha de ingreso.';
            }
        }

        // Valores numéricos
        if (!empty($data['valor_profesional']) && !is_numeric($data['valor_profesional'])) {
            $errors['valor_profesional'] = 'El valor debe ser numérico.';
        }

        if (!empty($data['valor_empresa']) && !is_numeric($data['valor_empresa'])) {
            $errors['valor_empresa'] = 'El valor debe ser numérico.';
        }

        return $errors;
    }
}

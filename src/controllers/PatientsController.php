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

        // Configuración de paginación
        $itemsPerPage = 6;
        $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $offset = ($currentPage - 1) * $itemsPerPage;

        // Obtener total de pacientes con filtros
        $totalItems = $this->patientModel->count($filters);
        $totalPages = ceil($totalItems / $itemsPerPage);

        // Obtener pacientes con paginación
        $patients = $this->patientModel->getAll($filters, $itemsPerPage, $offset);

        // Procesar prestaciones múltiples para cada paciente
        foreach ($patients as &$patient) {
            // Obtener todas las prestaciones activas del paciente
            $prestaciones = $this->prestacionPacienteModel->getByPaciente($patient['id'], ['estado' => 'activo']);

            $patient['prestaciones_activas'] = count($prestaciones);

            // Obtener listas únicas de prestaciones, profesionales y empresas
            $prestacionesNames = [];
            $profesionalesNames = [];
            $profesionalesIds = [];
            $empresasNames = [];
            $empresasIds = [];

            foreach ($prestaciones as $prest) {
                if (!empty($prest['prestacion_nombre']) && !in_array($prest['prestacion_nombre'], $prestacionesNames)) {
                    $prestacionesNames[] = $prest['prestacion_nombre'];
                }

                if (!empty($prest['profesional_nombre']) && !in_array($prest['id_profesional'], $profesionalesIds)) {
                    $profesionalesNames[] = $prest['profesional_nombre'];
                    $profesionalesIds[] = $prest['id_profesional'];
                }

                if (!empty($prest['empresa_nombre']) && !in_array($prest['id_empresa'], $empresasIds)) {
                    $empresasNames[] = $prest['empresa_nombre'];
                    $empresasIds[] = $prest['id_empresa'];
                }
            }

            $patient['prestaciones_list'] = implode(', ', $prestacionesNames);
            $patient['profesionales_list'] = implode(', ', $profesionalesNames);
            $patient['profesionales_count'] = count($profesionalesNames);
            $patient['empresas_list'] = implode(', ', $empresasNames);
            $patient['empresas_count'] = count($empresasNames);
        }
        unset($patient); // Romper la referencia

        // Obtener datos para filtros
        $professionals = $this->professionalModel->getAll(['estado' => 'activo']);
        $companies = $this->companyModel->getAll(['estado' => 'activo']);
        $services = $this->tipoPrestacionModel->getAll(['estado' => 'activo']);
        $obrasSociales = $this->obraSocialModel->getAll(['estado' => 'activo']);
        $provinces = $this->provinceModel->getAll();

        // Datos de paginación
        $pagination = [
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
            'items_per_page' => $itemsPerPage,
            'offset' => $offset
        ];

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

        // Validar que haya al menos una prestación
        if (empty($_POST['prestaciones']) || !is_array($_POST['prestaciones'])) {
            setFlash('error', 'Debe agregar al menos una prestación para el paciente.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('patients/create'));
            return;
        }

        // Si es paciente recurrente, fecha_finalizacion debe ser NULL
        $fechaFinalizacion = null;
        if (empty($_POST['paciente_recurrente']) && !empty($_POST['fecha_finalizacion'])) {
            $fechaFinalizacion = $_POST['fecha_finalizacion'];
        }

        // Preparar datos del paciente (sin prestación - se guarda en prestaciones_pacientes)
        $data = [
            'nombre_completo' => trim($_POST['nombre_completo']),
            'dni' => trim($_POST['dni'] ?? ''),
            'id_provincia' => !empty($_POST['id_provincia']) ? $_POST['id_provincia'] : null,
            'localidad' => trim($_POST['localidad'] ?? ''),
            'id_obra_social' => !empty($_POST['id_obra_social']) ? $_POST['id_obra_social'] : null,
            'frecuencia_servicio' => '', // Ahora cada prestación tiene su propia frecuencia
            'id_profesional' => null, // No se guarda en pacientes, solo en prestaciones_pacientes
            'id_empresa' => null, // No se guarda en pacientes, solo en prestaciones_pacientes
            'id_prestacion' => null, // No se guarda en pacientes, solo en prestaciones_pacientes
            'fecha_ingreso' => $_POST['fecha_ingreso'],
            'fecha_finalizacion' => $fechaFinalizacion,
            'paciente_recurrente' => !empty($_POST['paciente_recurrente']) ? 1 : 0,
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'valor_profesional' => null, // Ahora cada prestación tiene su propio valor
            'valor_empresa' => null, // Ahora cada prestación tiene su propio valor
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Crear paciente
        $patientId = $this->patientModel->create($data);

        if ($patientId) {
            // Crear todas las prestaciones del paciente
            $prestacionesCreadas = 0;
            foreach ($_POST['prestaciones'] as $prestacion) {
                // Validar que tenga los datos mínimos requeridos
                if (empty($prestacion['id_profesional']) || empty($prestacion['id_tipo_prestacion'])) {
                    continue; // Saltar prestaciones incompletas
                }

                // Obtener nombre de frecuencia desde el id
                $frecuenciaNombre = '';
                if (!empty($prestacion['id_frecuencia'])) {
                    $frecuenciaData = $this->frequencyModel->getById($prestacion['id_frecuencia']);
                    if ($frecuenciaData) {
                        $frecuenciaNombre = $frecuenciaData['nombre'];
                    }
                }

                // Preparar dias_semana como JSON
                $diasSemana = null;
                if (!empty($prestacion['dias_semana']) && is_array($prestacion['dias_semana'])) {
                    $diasSemana = json_encode($prestacion['dias_semana']);
                }

                $prestacionData = [
                    'id_paciente' => $patientId,
                    'id_tipo_prestacion' => $prestacion['id_tipo_prestacion'],
                    'id_profesional' => $prestacion['id_profesional'],
                    'id_empresa' => !empty($prestacion['id_empresa']) ? $prestacion['id_empresa'] : null,
                    'fecha_inicio' => $data['fecha_ingreso'],
                    'fecha_fin' => $data['fecha_finalizacion'],
                    'es_recurrente' => $data['paciente_recurrente'],
                    'id_frecuencia' => !empty($prestacion['id_frecuencia']) ? $prestacion['id_frecuencia'] : null,
                    'sesiones_personalizadas' => !empty($prestacion['sesiones_personalizadas']) ? $prestacion['sesiones_personalizadas'] : null,
                    'frecuencia_servicio' => $frecuenciaNombre,
                    'horas_semana' => !empty($prestacion['horas_semana']) ? number_format((float)$prestacion['horas_semana'], 2, '.', '') : null,
                    'dias_semana' => $diasSemana,
                    'valor_profesional' => !empty($prestacion['valor_profesional']) ? $prestacion['valor_profesional'] : null,
                    'valor_empresa' => !empty($prestacion['valor_empresa']) ? $prestacion['valor_empresa'] : null,
                    'observaciones' => $data['observaciones'],
                    'estado' => 'activo'
                ];

                if ($this->prestacionPacienteModel->create($prestacionData)) {
                    $prestacionesCreadas++;
                }
            }

            if ($prestacionesCreadas > 0) {
                setFlash('success', "Paciente creado exitosamente con {$prestacionesCreadas} prestacion(es).");
                redirect(baseUrl('patients'));
            } else {
                setFlash('warning', 'Paciente creado pero no se pudieron agregar las prestaciones.');
                redirect(baseUrl('patients'));
            }
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

        // Obtener TODAS las prestaciones del paciente
        $prestaciones = $this->prestacionPacienteModel->getByPaciente($id);

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

        // Validar que haya al menos una prestación
        if (empty($_POST['prestaciones']) || !is_array($_POST['prestaciones'])) {
            setFlash('error', 'Debe tener al menos una prestación para el paciente.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('patients/edit/' . $id));
            return;
        }

        // Si es paciente recurrente, fecha_finalizacion debe ser NULL
        $fechaFinalizacion = null;
        if (empty($_POST['paciente_recurrente']) && !empty($_POST['fecha_finalizacion'])) {
            $fechaFinalizacion = $_POST['fecha_finalizacion'];
        }

        // Preparar datos del paciente
        $data = [
            'nombre_completo' => trim($_POST['nombre_completo']),
            'dni' => trim($_POST['dni'] ?? ''),
            'id_provincia' => !empty($_POST['id_provincia']) ? $_POST['id_provincia'] : null,
            'localidad' => trim($_POST['localidad'] ?? ''),
            'id_obra_social' => !empty($_POST['id_obra_social']) ? $_POST['id_obra_social'] : null,
            'frecuencia_servicio' => '', // Ahora cada prestación tiene su propia frecuencia
            'id_profesional' => null,
            'id_empresa' => null,
            'id_prestacion' => null,
            'fecha_ingreso' => $_POST['fecha_ingreso'],
            'fecha_finalizacion' => $fechaFinalizacion,
            'paciente_recurrente' => !empty($_POST['paciente_recurrente']) ? 1 : 0,
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'valor_profesional' => null,
            'valor_empresa' => null,
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Actualizar datos del paciente
        if (!$this->patientModel->update($id, $data)) {
            setFlash('error', 'Error al actualizar el paciente.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('patients/edit/' . $id));
            return;
        }

        // Obtener IDs de prestaciones actuales
        $prestacionesActuales = $this->prestacionPacienteModel->getByPaciente($id);
        $idsActuales = array_column($prestacionesActuales, 'id');

        // Obtener IDs de prestaciones enviadas (existentes)
        $idsEnviados = [];
        foreach ($_POST['prestaciones'] as $prestacion) {
            if (!empty($prestacion['id'])) {
                $idsEnviados[] = $prestacion['id'];
            }
        }

        // Eliminar prestaciones que no fueron enviadas (soft delete)
        foreach ($idsActuales as $idActual) {
            if (!in_array($idActual, $idsEnviados)) {
                $this->prestacionPacienteModel->changeStatus($idActual, 'finalizado');
            }
        }

        // Procesar prestaciones (crear nuevas o actualizar existentes)
        $prestacionesActualizadas = 0;
        $prestacionesCreadas = 0;

        foreach ($_POST['prestaciones'] as $prestacion) {
            // Validar datos mínimos
            if (empty($prestacion['id_profesional']) || empty($prestacion['id_tipo_prestacion'])) {
                continue;
            }

            // Obtener nombre de frecuencia desde el id
            $frecuenciaNombre = '';
            if (!empty($prestacion['id_frecuencia'])) {
                $frecuenciaData = $this->frequencyModel->getById($prestacion['id_frecuencia']);
                if ($frecuenciaData) {
                    $frecuenciaNombre = $frecuenciaData['nombre'];
                }
            }

            // Preparar dias_semana como JSON
            $diasSemana = null;
            if (!empty($prestacion['dias_semana']) && is_array($prestacion['dias_semana'])) {
                $diasSemana = json_encode($prestacion['dias_semana']);
            }

            $prestacionData = [
                'id_paciente' => $id,
                'id_tipo_prestacion' => $prestacion['id_tipo_prestacion'],
                'id_profesional' => $prestacion['id_profesional'],
                'id_empresa' => !empty($prestacion['id_empresa']) ? $prestacion['id_empresa'] : null,
                'fecha_inicio' => $data['fecha_ingreso'],
                'fecha_fin' => $data['fecha_finalizacion'],
                'es_recurrente' => $data['paciente_recurrente'],
                'id_frecuencia' => !empty($prestacion['id_frecuencia']) ? $prestacion['id_frecuencia'] : null,
                'sesiones_personalizadas' => !empty($prestacion['sesiones_personalizadas']) ? $prestacion['sesiones_personalizadas'] : null,
                'frecuencia_servicio' => $frecuenciaNombre,
                'horas_semana' => !empty($prestacion['horas_semana']) ? number_format((float)$prestacion['horas_semana'], 2, '.', '') : null,
                'dias_semana' => $diasSemana,
                'valor_profesional' => !empty($prestacion['valor_profesional']) ? $prestacion['valor_profesional'] : null,
                'valor_empresa' => !empty($prestacion['valor_empresa']) ? $prestacion['valor_empresa'] : null,
                'observaciones' => $data['observaciones'],
                'estado' => 'activo'
            ];

            // Si tiene ID, actualizar; si no, crear
            if (!empty($prestacion['id'])) {
                if ($this->prestacionPacienteModel->update($prestacion['id'], $prestacionData)) {
                    $prestacionesActualizadas++;
                }
            } else {
                if ($this->prestacionPacienteModel->create($prestacionData)) {
                    $prestacionesCreadas++;
                }
            }
        }

        $mensaje = "Paciente actualizado exitosamente.";
        if ($prestacionesCreadas > 0 || $prestacionesActualizadas > 0) {
            $mensaje .= " ({$prestacionesCreadas} nueva(s), {$prestacionesActualizadas} actualizada(s))";
        }

        setFlash('success', $mensaje);
        redirect(baseUrl('patients'));
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

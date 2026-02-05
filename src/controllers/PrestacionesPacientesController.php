<?php

require_once __DIR__ . '/../models/PrestacionPaciente.php';
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/TipoPrestacion.php';
require_once __DIR__ . '/../models/Professional.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/Frequency.php';
require_once __DIR__ . '/../middleware/Auth.php';

class PrestacionesPacientesController
{
    private $prestacionPacienteModel;
    private $patientModel;
    private $tipoPrestacionModel;
    private $professionalModel;
    private $companyModel;
    private $frequencyModel;

    public function __construct()
    {
        Auth::requireAuth();
        Auth::checkTimeout();
        $this->prestacionPacienteModel = new PrestacionPaciente();
        $this->patientModel = new Patient();
        $this->tipoPrestacionModel = new TipoPrestacion();
        $this->professionalModel = new Professional();
        $this->companyModel = new Company();
        $this->frequencyModel = new Frequency();
    }

    /**
     * Mostrar formulario para agregar prestación a paciente
     */
    public function create($idPaciente)
    {
        $patient = $this->patientModel->getById($idPaciente);

        if (!$patient) {
            setFlash('error', 'Paciente no encontrado.');
            redirect(baseUrl('patients'));
            return;
        }

        // Obtener datos para selects
        $prestaciones = $this->tipoPrestacionModel->getAll(['estado' => 'activo']);
        $professionals = $this->professionalModel->getAll(['estado' => 'activo']);
        $companies = $this->companyModel->getAll(['estado' => 'activo']);
        $frecuencias = $this->frequencyModel->getAll();

        $title = 'Asignar Prestación - ' . $patient['nombre_completo'];
        include __DIR__ . '/../../views/prestaciones_pacientes/create.php';
    }

    /**
     * Guardar nuevo servicio para paciente
     */
    public function store($idPaciente)
    {
        $this->validateCSRFToken();

        $patient = $this->patientModel->getById($idPaciente);

        if (!$patient) {
            setFlash('error', 'Paciente no encontrado.');
            redirect(baseUrl('patients'));
            return;
        }

        // Validar datos
        $errors = $this->validateData($_POST);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('patients/view/' . $idPaciente));
            return;
        }

        // Si es recurrente, fecha_fin debe ser NULL
        $fechaFin = null;
        if (empty($_POST['es_recurrente']) && !empty($_POST['fecha_fin'])) {
            $fechaFin = $_POST['fecha_fin'];
        }

        // Preparar dias_semana como JSON si viene del formulario
        $diasSemana = null;
        if (!empty($_POST['dias_semana']) && is_array($_POST['dias_semana'])) {
            $diasSemana = json_encode($_POST['dias_semana']);
        }

        // Preparar datos
        $data = [
            'id_paciente' => $idPaciente,
            'id_tipo_prestacion' => $_POST['id_tipo_prestacion'],
            'id_profesional' => $_POST['id_profesional'],
            'id_empresa' => !empty($_POST['id_empresa']) ? $_POST['id_empresa'] : null,
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $fechaFin,
            'es_recurrente' => !empty($_POST['es_recurrente']) ? 1 : 0,
            'id_frecuencia' => !empty($_POST['id_frecuencia']) ? $_POST['id_frecuencia'] : null,
            'sesiones_personalizadas' => !empty($_POST['sesiones_personalizadas']) ? $_POST['sesiones_personalizadas'] : null,
            'frecuencia_servicio' => trim($_POST['frecuencia_servicio'] ?? ''),
            'horas_semana' => !empty($_POST['horas_semana']) ? number_format((float)$_POST['horas_semana'], 2, '.', '') : null,
            'dias_semana' => $diasSemana,
            'valor_profesional' => !empty($_POST['valor_profesional']) ? number_format((float)$_POST['valor_profesional'], 2, '.', '') : null,
            'valor_empresa' => !empty($_POST['valor_empresa']) ? number_format((float)$_POST['valor_empresa'], 2, '.', '') : null,
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Crear prestación
        $id = $this->prestacionPacienteModel->create($data);

        if ($id) {
            setFlash('success', 'Servicio asignado exitosamente.');
            redirect(baseUrl('patients/view/' . $idPaciente));
        } else {
            setFlash('error', 'Error al asignar el servicio.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('patients/view/' . $idPaciente));
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $prestacion = $this->prestacionPacienteModel->getById($id);

        if (!$prestacion) {
            setFlash('error', 'Servicio no encontrado.');
            redirect(baseUrl('patients'));
            return;
        }

        $patient = $this->patientModel->getById($prestacion['id_paciente']);

        // Obtener datos para selects
        $prestaciones = $this->tipoPrestacionModel->getAll(['estado' => 'activo']);
        $professionals = $this->professionalModel->getAll(['estado' => 'activo']);
        $companies = $this->companyModel->getAll(['estado' => 'activo']);
        $frecuencias = $this->frequencyModel->getAll();

        $title = 'Editar Prestación - ' . $patient['nombre_completo'];
        include __DIR__ . '/../../views/prestaciones_pacientes/edit.php';
    }

    /**
     * Actualizar servicio
     */
    public function update($id)
    {
        $this->validateCSRFToken();

        $prestacion = $this->prestacionPacienteModel->getById($id);

        if (!$prestacion) {
            setFlash('error', 'Servicio no encontrado.');
            redirect(baseUrl('patients'));
            return;
        }

        // Validar datos
        $errors = $this->validateData($_POST);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('prestaciones-pacientes/edit/' . $id));
            return;
        }

        // Si es recurrente, fecha_fin debe ser NULL
        $fechaFin = null;
        if (empty($_POST['es_recurrente']) && !empty($_POST['fecha_fin'])) {
            $fechaFin = $_POST['fecha_fin'];
        }

        // Preparar dias_semana como JSON si viene del formulario
        $diasSemana = null;
        if (!empty($_POST['dias_semana']) && is_array($_POST['dias_semana'])) {
            $diasSemana = json_encode($_POST['dias_semana']);
        }

        // Preparar datos
        $data = [
            'id_tipo_prestacion' => $_POST['id_tipo_prestacion'],
            'id_profesional' => $_POST['id_profesional'],
            'id_empresa' => !empty($_POST['id_empresa']) ? $_POST['id_empresa'] : null,
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $fechaFin,
            'es_recurrente' => !empty($_POST['es_recurrente']) ? 1 : 0,
            'id_frecuencia' => !empty($_POST['id_frecuencia']) ? $_POST['id_frecuencia'] : null,
            'sesiones_personalizadas' => !empty($_POST['sesiones_personalizadas']) ? $_POST['sesiones_personalizadas'] : null,
            'frecuencia_servicio' => trim($_POST['frecuencia_servicio'] ?? ''),
            'horas_semana' => !empty($_POST['horas_semana']) ? number_format((float)$_POST['horas_semana'], 2, '.', '') : null,
            'dias_semana' => $diasSemana,
            'valor_profesional' => !empty($_POST['valor_profesional']) ? number_format((float)$_POST['valor_profesional'], 2, '.', '') : null,
            'valor_empresa' => !empty($_POST['valor_empresa']) ? number_format((float)$_POST['valor_empresa'], 2, '.', '') : null,
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Actualizar
        if ($this->prestacionPacienteModel->update($id, $data)) {
            setFlash('success', 'Servicio actualizado exitosamente.');
            redirect(baseUrl('patients/view/' . $prestacion['id_paciente']));
        } else {
            setFlash('error', 'Error al actualizar el servicio.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('prestaciones-pacientes/edit/' . $id));
        }
    }

    /**
     * Obtener modo de frecuencia de un tipo de prestación (AJAX)
     */
    public function getModoFrecuencia($idTipoPrestacion)
    {
        header('Content-Type: application/json');

        $tipo = $this->tipoPrestacionModel->getById($idTipoPrestacion);

        if ($tipo) {
            echo json_encode([
                'success' => true,
                'modo' => $tipo['modo_frecuencia'] ?? 'sesiones'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'modo' => 'sesiones'
            ]);
        }
        exit;
    }

    /**
     * Eliminar (finalizar) servicio
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

        $prestacion = $this->prestacionPacienteModel->getById($id);

        if (!$prestacion) {
            setFlash('error', 'Servicio no encontrado.');
            redirect(baseUrl('patients'));
            return;
        }

        if ($this->prestacionPacienteModel->delete($id)) {
            setFlash('success', 'Servicio finalizado exitosamente.');
        } else {
            setFlash('error', 'Error al finalizar el servicio.');
        }

        redirect(baseUrl('patients/view/' . $prestacion['id_paciente']));
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
     * Validar datos
     */
    private function validateData($data)
    {
        $errors = [];

        // Prestación
        if (empty($data['id_tipo_prestacion'])) {
            $errors['id_tipo_prestacion'] = 'Debe seleccionar una prestación.';
        }

        // Profesional
        if (empty($data['id_profesional'])) {
            $errors['id_profesional'] = 'Debe seleccionar un profesional.';
        }

        // Fecha inicio
        if (empty($data['fecha_inicio'])) {
            $errors['fecha_inicio'] = 'La fecha de inicio es obligatoria.';
        }

        // Fecha fin (solo si NO es recurrente)
        if (empty($data['es_recurrente']) && empty($data['fecha_fin'])) {
            $errors['fecha_fin'] = 'La fecha de finalización es obligatoria para servicios no recurrentes.';
        }

        // Validar que fecha_fin sea mayor a fecha_inicio
        if (!empty($data['fecha_inicio']) && !empty($data['fecha_fin'])) {
            if (strtotime($data['fecha_fin']) < strtotime($data['fecha_inicio'])) {
                $errors['fecha_fin'] = 'La fecha de finalización debe ser posterior a la fecha de inicio.';
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

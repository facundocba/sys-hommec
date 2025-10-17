<?php

require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../models/TipoPrestacion.php';
require_once __DIR__ . '/../middleware/Auth.php';

class ServicesController
{
    private $serviceModel;
    private $tipoPrestacionModel;

    public function __construct()
    {
        Auth::requireAuth();
        Auth::checkTimeout();
        $this->serviceModel = new Service();
        $this->tipoPrestacionModel = new TipoPrestacion();
    }

    /**
     * Listado de prestaciones
     */
    public function index()
    {
        // Obtener filtros
        $filters = [
            'search' => $_GET['search'] ?? '',
            'tipo_prestacion' => $_GET['tipo_prestacion'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];

        // Obtener prestaciones con estadísticas
        $services = $this->serviceModel->getAllWithStats($filters);

        // Obtener tipos de prestación para filtros
        $tiposPrestacion = $this->tipoPrestacionModel->getAll(['estado' => 'activo']);

        // Renderizar vista
        $title = 'Gestión de Prestaciones';
        include __DIR__ . '/../../views/services/index.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        // Obtener tipos de prestación para el select
        $tiposPrestacion = $this->tipoPrestacionModel->getAll(['estado' => 'activo']);

        $title = 'Nueva Prestación';
        include __DIR__ . '/../../views/services/create.php';
    }

    /**
     * Guardar nueva prestación
     */
    public function store()
    {
        $this->validateCSRFToken();

        // Validar datos
        $errors = $this->validateServiceData($_POST);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('services/create'));
            return;
        }

        // Verificar código único (solo si se proporciona)
        if (!empty($_POST['codigo']) && $this->serviceModel->codigoExists($_POST['codigo'])) {
            setFlash('error', 'El código ya está registrado.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('services/create'));
            return;
        }

        // Preparar datos
        $data = [
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'codigo' => trim($_POST['codigo'] ?? ''),
            'id_tipo_prestacion' => !empty($_POST['id_tipo_prestacion']) ? $_POST['id_tipo_prestacion'] : null,
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Crear prestación
        $serviceId = $this->serviceModel->create($data);

        if ($serviceId) {
            setFlash('success', 'Prestación creada exitosamente.');
            redirect(baseUrl('services'));
        } else {
            setFlash('error', 'Error al crear la prestación.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('services/create'));
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $service = $this->serviceModel->getById($id);

        if (!$service) {
            setFlash('error', 'Prestación no encontrada.');
            redirect(baseUrl('services'));
            return;
        }

        // Obtener tipos de prestación para el select
        $tiposPrestacion = $this->tipoPrestacionModel->getAll(['estado' => 'activo']);

        $title = 'Editar Prestación';
        include __DIR__ . '/../../views/services/edit.php';
    }

    /**
     * Actualizar prestación
     */
    public function update($id)
    {
        $this->validateCSRFToken();

        $service = $this->serviceModel->getById($id);

        if (!$service) {
            setFlash('error', 'Prestación no encontrada.');
            redirect(baseUrl('services'));
            return;
        }

        // Validar datos
        $errors = $this->validateServiceData($_POST, $id);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('services/edit/' . $id));
            return;
        }

        // Verificar código único (solo si se proporciona)
        if (!empty($_POST['codigo']) && $this->serviceModel->codigoExists($_POST['codigo'], $id)) {
            setFlash('error', 'El código ya está registrado.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('services/edit/' . $id));
            return;
        }

        // Preparar datos
        $data = [
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'codigo' => trim($_POST['codigo'] ?? ''),
            'id_tipo_prestacion' => !empty($_POST['id_tipo_prestacion']) ? $_POST['id_tipo_prestacion'] : null,
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Actualizar prestación
        if ($this->serviceModel->update($id, $data)) {
            setFlash('success', 'Prestación actualizada exitosamente.');
            redirect(baseUrl('services'));
        } else {
            setFlash('error', 'Error al actualizar la prestación.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('services/edit/' . $id));
        }
    }

    /**
     * Eliminar (desactivar) prestación
     */
    public function delete($id)
    {
        // Solo admin puede eliminar
        if (!hasRole('administrador')) {
            setFlash('error', 'No tiene permisos para realizar esta acción.');
            redirect(baseUrl('services'));
            return;
        }

        $this->validateCSRFToken();

        if ($this->serviceModel->delete($id)) {
            setFlash('success', 'Prestación desactivada exitosamente.');
        } else {
            setFlash('error', 'Error al desactivar la prestación.');
        }

        redirect(baseUrl('services'));
    }

    /**
     * Validar CSRF token
     */
    private function validateCSRFToken()
    {
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlash('error', 'Token de seguridad inválido.');
            redirect(baseUrl('services'));
            exit;
        }
    }

    /**
     * Validar datos de prestación
     */
    private function validateServiceData($data, $excludeId = null)
    {
        $errors = [];

        // Nombre
        if (empty($data['nombre'])) {
            $errors['nombre'] = 'El nombre es obligatorio.';
        } elseif (strlen($data['nombre']) < 3) {
            $errors['nombre'] = 'El nombre debe tener al menos 3 caracteres.';
        }

        // Código (opcional pero debe tener formato válido si se proporciona)
        if (!empty($data['codigo']) && strlen($data['codigo']) < 2) {
            $errors['codigo'] = 'El código debe tener al menos 2 caracteres.';
        }

        return $errors;
    }
}

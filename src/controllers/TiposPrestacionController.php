<?php

require_once __DIR__ . '/../models/TipoPrestacion.php';
require_once __DIR__ . '/../middleware/Auth.php';

class TiposPrestacionController
{
    private $tipoPrestacionModel;

    public function __construct()
    {
        Auth::requireAuth();
        Auth::checkTimeout();
        $this->tipoPrestacionModel = new TipoPrestacion();
    }

    /**
     * Listado de tipos de prestación
     */
    public function index()
    {
        // Obtener filtros
        $filters = [
            'search' => $_GET['search'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];

        // Obtener tipos
        $tiposPrestacion = $this->tipoPrestacionModel->getAll($filters);

        // Renderizar vista
        $title = 'Tipos de Prestación';
        include __DIR__ . '/../../views/tipos_prestacion/index.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $title = 'Nuevo Tipo de Prestación';
        include __DIR__ . '/../../views/tipos_prestacion/create.php';
    }

    /**
     * Guardar nuevo tipo
     */
    public function store()
    {
        $this->validateCSRFToken();

        // Validar datos
        $errors = $this->validateData($_POST);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('tipos-prestacion/create'));
            return;
        }

        // Preparar datos
        $data = [
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Crear tipo
        $id = $this->tipoPrestacionModel->create($data);

        if ($id) {
            setFlash('success', 'Tipo de prestación creado exitosamente.');
            redirect(baseUrl('tipos-prestacion'));
        } else {
            setFlash('error', 'Error al crear el tipo de prestación.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('tipos-prestacion/create'));
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $tipoPrestacion = $this->tipoPrestacionModel->getById($id);

        if (!$tipoPrestacion) {
            setFlash('error', 'Tipo de prestación no encontrado.');
            redirect(baseUrl('tipos-prestacion'));
            return;
        }

        $title = 'Editar Tipo de Prestación';
        include __DIR__ . '/../../views/tipos_prestacion/edit.php';
    }

    /**
     * Actualizar tipo
     */
    public function update($id)
    {
        $this->validateCSRFToken();

        $tipoPrestacion = $this->tipoPrestacionModel->getById($id);

        if (!$tipoPrestacion) {
            setFlash('error', 'Tipo de prestación no encontrado.');
            redirect(baseUrl('tipos-prestacion'));
            return;
        }

        // Validar datos
        $errors = $this->validateData($_POST, $id);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('tipos-prestacion/edit/' . $id));
            return;
        }

        // Preparar datos
        $data = [
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Actualizar tipo
        if ($this->tipoPrestacionModel->update($id, $data)) {
            setFlash('success', 'Tipo de prestación actualizado exitosamente.');
            redirect(baseUrl('tipos-prestacion'));
        } else {
            setFlash('error', 'Error al actualizar el tipo de prestación.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('tipos-prestacion/edit/' . $id));
        }
    }

    /**
     * Eliminar tipo
     */
    public function delete($id)
    {
        // Solo admin puede eliminar
        if (!hasRole('administrador')) {
            setFlash('error', 'No tiene permisos para realizar esta acción.');
            redirect(baseUrl('tipos-prestacion'));
            return;
        }

        $this->validateCSRFToken();

        if ($this->tipoPrestacionModel->delete($id)) {
            setFlash('success', 'Tipo de prestación desactivado exitosamente.');
        } else {
            setFlash('error', 'Error al desactivar el tipo de prestación.');
        }

        redirect(baseUrl('tipos-prestacion'));
    }

    /**
     * Validar CSRF token
     */
    private function validateCSRFToken()
    {
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlash('error', 'Token de seguridad inválido.');
            redirect(baseUrl('tipos-prestacion'));
            exit;
        }
    }

    /**
     * Validar datos
     */
    private function validateData($data, $excludeId = null)
    {
        $errors = [];

        // Nombre
        if (empty($data['nombre'])) {
            $errors['nombre'] = 'El nombre es obligatorio.';
        } elseif (strlen($data['nombre']) < 2) {
            $errors['nombre'] = 'El nombre debe tener al menos 2 caracteres.';
        } elseif ($this->tipoPrestacionModel->nameExists($data['nombre'], $excludeId)) {
            $errors['nombre'] = 'Ya existe un tipo de prestación con este nombre.';
        }

        return $errors;
    }
}

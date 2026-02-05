<?php

require_once __DIR__ . '/../models/TipoPrestacion.php';
require_once __DIR__ . '/../middleware/Auth.php';

class PrestacionesController
{
    private $tipoPrestacionModel;

    public function __construct()
    {
        Auth::requireAuth();
        Auth::checkTimeout();
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
            'estado' => $_GET['estado'] ?? ''
        ];

        // Obtener prestaciones
        $prestaciones = $this->tipoPrestacionModel->getAll($filters);

        // Renderizar vista
        $title = 'Prestaciones';
        include __DIR__ . '/../../views/prestaciones/index.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $title = 'Nueva Prestación';
        include __DIR__ . '/../../views/prestaciones/create.php';
    }

    /**
     * Guardar nueva prestación
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
            redirect(baseUrl('prestaciones/create'));
            return;
        }

        // Preparar datos
        $data = [
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'modo_frecuencia' => $_POST['modo_frecuencia'] ?? 'sesiones',
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Crear prestación
        $id = $this->tipoPrestacionModel->create($data);

        if ($id) {
            setFlash('success', 'Prestación creada exitosamente.');
            redirect(baseUrl('prestaciones'));
        } else {
            setFlash('error', 'Error al crear la prestación.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('prestaciones/create'));
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $prestacion = $this->tipoPrestacionModel->getById($id);

        if (!$prestacion) {
            setFlash('error', 'Prestación no encontrada.');
            redirect(baseUrl('prestaciones'));
            return;
        }

        $title = 'Editar Prestación';
        include __DIR__ . '/../../views/prestaciones/edit.php';
    }

    /**
     * Actualizar prestación
     */
    public function update($id)
    {
        $this->validateCSRFToken();

        $prestacion = $this->tipoPrestacionModel->getById($id);

        if (!$prestacion) {
            setFlash('error', 'Prestación no encontrada.');
            redirect(baseUrl('prestaciones'));
            return;
        }

        // Validar datos
        $errors = $this->validateData($_POST, $id);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('prestaciones/edit/' . $id));
            return;
        }

        // Preparar datos
        $data = [
            'nombre' => trim($_POST['nombre']),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'modo_frecuencia' => $_POST['modo_frecuencia'] ?? 'sesiones',
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Actualizar prestación
        if ($this->tipoPrestacionModel->update($id, $data)) {
            setFlash('success', 'Prestación actualizada exitosamente.');
            redirect(baseUrl('prestaciones'));
        } else {
            setFlash('error', 'Error al actualizar la prestación.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('prestaciones/edit/' . $id));
        }
    }

    /**
     * Eliminar prestación
     */
    public function delete($id)
    {
        // Solo admin puede eliminar
        if (!hasRole('administrador')) {
            setFlash('error', 'No tiene permisos para realizar esta acción.');
            redirect(baseUrl('prestaciones'));
            return;
        }

        $this->validateCSRFToken();

        if ($this->tipoPrestacionModel->delete($id)) {
            setFlash('success', 'Prestación desactivada exitosamente.');
        } else {
            setFlash('error', 'Error al desactivar la prestación.');
        }

        redirect(baseUrl('prestaciones'));
    }

    /**
     * Validar CSRF token
     */
    private function validateCSRFToken()
    {
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlash('error', 'Token de seguridad inválido.');
            redirect(baseUrl('prestaciones'));
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
            $errors['nombre'] = 'Ya existe una prestación con este nombre.';
        }

        return $errors;
    }
}

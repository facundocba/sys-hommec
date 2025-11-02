<?php

require_once __DIR__ . '/../models/ObraSocial.php';
require_once __DIR__ . '/../middleware/Auth.php';

class ObrasSocialesController
{
    private $obraSocialModel;

    public function __construct()
    {
        Auth::requireAuth();
        Auth::checkTimeout();
        $this->obraSocialModel = new ObraSocial();
    }

    /**
     * Listado de obras sociales
     */
    public function index()
    {
        // Obtener filtros
        $filters = [
            'search' => $_GET['search'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];

        // Configuración de paginación
        $itemsPerPage = 8;
        $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $offset = ($currentPage - 1) * $itemsPerPage;

        // Obtener total de obras sociales con filtros
        $totalItems = $this->obraSocialModel->count($filters);
        $totalPages = ceil($totalItems / $itemsPerPage);

        // Obtener obras sociales con paginación
        $obrasSociales = $this->obraSocialModel->getAll($filters, $itemsPerPage, $offset);

        // Datos de paginación
        $pagination = [
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
            'items_per_page' => $itemsPerPage,
            'offset' => $offset
        ];

        // Renderizar vista
        $title = 'Obras Sociales';
        include __DIR__ . '/../../views/obras_sociales/index.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $title = 'Nueva Obra Social';
        include __DIR__ . '/../../views/obras_sociales/create.php';
    }

    /**
     * Guardar nueva obra social
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
            redirect(baseUrl('obras-sociales/create'));
            return;
        }

        // Preparar datos
        $data = [
            'nombre' => trim($_POST['nombre']),
            'sigla' => trim($_POST['sigla'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Crear obra social
        $id = $this->obraSocialModel->create($data);

        if ($id) {
            setFlash('success', 'Obra social creada exitosamente.');
            redirect(baseUrl('obras-sociales'));
        } else {
            setFlash('error', 'Error al crear la obra social.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('obras-sociales/create'));
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $obraSocial = $this->obraSocialModel->getById($id);

        if (!$obraSocial) {
            setFlash('error', 'Obra social no encontrada.');
            redirect(baseUrl('obras-sociales'));
            return;
        }

        $title = 'Editar Obra Social';
        include __DIR__ . '/../../views/obras_sociales/edit.php';
    }

    /**
     * Actualizar obra social
     */
    public function update($id)
    {
        $this->validateCSRFToken();

        $obraSocial = $this->obraSocialModel->getById($id);

        if (!$obraSocial) {
            setFlash('error', 'Obra social no encontrada.');
            redirect(baseUrl('obras-sociales'));
            return;
        }

        // Validar datos
        $errors = $this->validateData($_POST, $id);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('obras-sociales/edit/' . $id));
            return;
        }

        // Preparar datos
        $data = [
            'nombre' => trim($_POST['nombre']),
            'sigla' => trim($_POST['sigla'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Actualizar obra social
        if ($this->obraSocialModel->update($id, $data)) {
            setFlash('success', 'Obra social actualizada exitosamente.');
            redirect(baseUrl('obras-sociales'));
        } else {
            setFlash('error', 'Error al actualizar la obra social.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('obras-sociales/edit/' . $id));
        }
    }

    /**
     * Eliminar obra social
     */
    public function delete($id)
    {
        // Solo admin puede eliminar
        if (!hasRole('administrador')) {
            setFlash('error', 'No tiene permisos para realizar esta acción.');
            redirect(baseUrl('obras-sociales'));
            return;
        }

        $this->validateCSRFToken();

        if ($this->obraSocialModel->delete($id)) {
            setFlash('success', 'Obra social desactivada exitosamente.');
        } else {
            setFlash('error', 'Error al desactivar la obra social.');
        }

        redirect(baseUrl('obras-sociales'));
    }

    /**
     * Validar CSRF token
     */
    private function validateCSRFToken()
    {
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlash('error', 'Token de seguridad inválido.');
            redirect(baseUrl('obras-sociales'));
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
        } elseif ($this->obraSocialModel->nameExists($data['nombre'], $excludeId)) {
            $errors['nombre'] = 'Ya existe una obra social con este nombre.';
        }

        // Email (opcional pero debe ser válido)
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El email no es válido.';
        }

        return $errors;
    }
}

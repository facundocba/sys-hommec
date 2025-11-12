<?php

require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../middleware/Auth.php';

class CompaniesController
{
    private $companyModel;

    public function __construct()
    {
        Auth::requireAuth();
        Auth::checkTimeout();
        $this->companyModel = new Company();
    }

    /**
     * Listado de empresas
     */
    public function index()
    {

        // Obtener filtros
        $filters = [
            'search' => $_GET['search'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];

        // Obtener empresas
        $companies = $this->companyModel->getAll($filters);

        // Renderizar vista
        $title = 'Gestión de Empresas';
        include __DIR__ . '/../../views/companies/index.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        // Admin y coordinador pueden crear empresas
        // La autenticación ya se verificó en el constructor

        $title = 'Nueva Empresa';
        include __DIR__ . '/../../views/companies/create.php';
    }

    /**
     * Guardar nueva empresa
     */
    public function store()
    {
        $this->validateCSRFToken();

        // Validar datos
        $errors = $this->validateCompanyData($_POST);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('companies/create'));
            return;
        }

        // Verificar email único
        if ($this->companyModel->emailExists($_POST['email'])) {
            setFlash('error', 'El email ya está registrado.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('companies/create'));
            return;
        }

        // Preparar datos
        $data = [
            'nombre' => trim($_POST['nombre']),
            'email' => trim($_POST['email']),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Crear empresa
        $companyId = $this->companyModel->create($data);

        if ($companyId) {
            setFlash('success', 'Empresa creada exitosamente.');
            redirect(baseUrl('companies'));
        } else {
            setFlash('error', 'Error al crear la empresa.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('companies/create'));
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {

        $company = $this->companyModel->getById($id);

        if (!$company) {
            setFlash('error', 'Empresa no encontrada.');
            redirect(baseUrl('companies'));
            return;
        }

        $title = 'Editar Empresa';
        include __DIR__ . '/../../views/companies/edit.php';
    }

    /**
     * Actualizar empresa
     */
    public function update($id)
    {
        $this->validateCSRFToken();

        $company = $this->companyModel->getById($id);

        if (!$company) {
            setFlash('error', 'Empresa no encontrada.');
            redirect(baseUrl('companies'));
            return;
        }

        // Validar datos
        $errors = $this->validateCompanyData($_POST, $id);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('companies/edit/' . $id));
            return;
        }

        // Verificar email único
        if ($this->companyModel->emailExists($_POST['email'], $id)) {
            setFlash('error', 'El email ya está registrado.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('companies/edit/' . $id));
            return;
        }

        // Preparar datos
        $data = [
            'nombre' => trim($_POST['nombre']),
            'email' => trim($_POST['email']),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Actualizar empresa
        if ($this->companyModel->update($id, $data)) {
            setFlash('success', 'Empresa actualizada exitosamente.');
            redirect(baseUrl('companies'));
        } else {
            setFlash('error', 'Error al actualizar la empresa.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('companies/edit/' . $id));
        }
    }

    /**
     * Eliminar empresa (hard delete)
     */
    public function delete($id)
    {
        // Solo admin puede eliminar
        if (!hasRole('administrador')) {
            setFlash('error', 'No tiene permisos para realizar esta acción.');
            redirect(baseUrl('companies'));
            return;
        }

        $this->validateCSRFToken();

        // Verificar si la empresa existe
        $company = $this->companyModel->getById($id);
        if (!$company) {
            setFlash('error', 'Empresa no encontrada.');
            redirect(baseUrl('companies'));
            return;
        }

        // Verificar si tiene registros relacionados
        $relations = $this->companyModel->hasRelatedRecords($id);

        if (!empty($relations)) {
            // Construir mensaje detallado
            $mensaje = 'No se puede eliminar la empresa "' . htmlspecialchars($company['nombre']) . '" porque tiene registros relacionados: ';
            $detalles = [];

            if (isset($relations['pacientes'])) {
                $detalles[] = $relations['pacientes'] . ' paciente(s)';
            }
            if (isset($relations['prestaciones_pacientes'])) {
                $detalles[] = $relations['prestaciones_pacientes'] . ' prestación(es) de pacientes';
            }
            if (isset($relations['prestaciones_empresas'])) {
                $detalles[] = $relations['prestaciones_empresas'] . ' prestación(es) configurada(s)';
            }

            $mensaje .= implode(', ', $detalles) . '. Elimine primero estos registros o considere desactivar la empresa.';

            setFlash('error', $mensaje);
            redirect(baseUrl('companies'));
            return;
        }

        // Si no tiene relaciones, proceder a eliminar
        if ($this->companyModel->delete($id)) {
            setFlash('success', 'Empresa "' . htmlspecialchars($company['nombre']) . '" eliminada exitosamente.');
        } else {
            setFlash('error', 'Error al eliminar la empresa.');
        }

        redirect(baseUrl('companies'));
    }

    /**
     * Validar CSRF token
     */
    private function validateCSRFToken()
    {
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlash('error', 'Token de seguridad inválido.');
            redirect(baseUrl('companies'));
            exit;
        }
    }

    /**
     * Validar datos de empresa
     */
    private function validateCompanyData($data, $excludeId = null)
    {
        $errors = [];

        // Nombre
        if (empty($data['nombre'])) {
            $errors['nombre'] = 'El nombre es obligatorio.';
        } elseif (strlen($data['nombre']) < 3) {
            $errors['nombre'] = 'El nombre debe tener al menos 3 caracteres.';
        }

        // Email
        if (empty($data['email'])) {
            $errors['email'] = 'El email es obligatorio.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El email no es válido.';
        }

        // Teléfono (opcional pero debe tener formato válido si se proporciona)
        if (!empty($data['telefono']) && strlen($data['telefono']) < 7) {
            $errors['telefono'] = 'El teléfono debe tener al menos 7 dígitos.';
        }

        return $errors;
    }
}

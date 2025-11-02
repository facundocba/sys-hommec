<?php

require_once __DIR__ . '/../models/PrestacionEmpresa.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/TipoPrestacion.php';
require_once __DIR__ . '/../middleware/Auth.php';

class PrestacionesEmpresasController
{
    private $prestacionEmpresaModel;
    private $companyModel;
    private $tipoPrestacionModel;

    public function __construct()
    {
        Auth::requireAuth();
        Auth::checkTimeout();
        $this->prestacionEmpresaModel = new PrestacionEmpresa();
        $this->companyModel = new Company();
        $this->tipoPrestacionModel = new TipoPrestacion();
    }

    /**
     * Validar CSRF Token
     */
    private function validateCSRFToken()
    {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            setFlash('error', 'Token de seguridad inválido.');
            redirect(baseUrl('prestaciones-empresas'));
            exit;
        }
    }

    /**
     * Listado de configuraciones prestación-empresa
     */
    public function index()
    {
        // Obtener filtros
        $filters = [
            'search' => $_GET['search'] ?? '',
            'id_empresa' => $_GET['id_empresa'] ?? '',
            'id_tipo_prestacion' => $_GET['id_tipo_prestacion'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];

        // Obtener datos
        $prestacionesEmpresas = $this->prestacionEmpresaModel->getAll($filters);
        $companies = $this->companyModel->getAll(['estado' => 'activo']);
        $tiposPrestacion = $this->tipoPrestacionModel->getAll(['estado' => 'activo']);
        $stats = $this->prestacionEmpresaModel->getStats();

        // Renderizar vista
        $title = 'Configuración de Prestaciones por Empresa';
        include __DIR__ . '/../../views/prestaciones_empresas/index.php';
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        // Solo administradores
        if (!hasRole('administrador')) {
            setFlash('error', 'No tienes permisos para realizar esta acción.');
            redirect(baseUrl('prestaciones-empresas'));
            return;
        }

        $companies = $this->companyModel->getAll(['estado' => 'activo']);
        $tiposPrestacion = $this->tipoPrestacionModel->getAll(['estado' => 'activo']);

        $title = 'Nueva Configuración Prestación-Empresa';
        include __DIR__ . '/../../views/prestaciones_empresas/create.php';
    }

    /**
     * Guardar nueva configuración
     */
    public function store()
    {
        $this->validateCSRFToken();

        // Solo administradores
        if (!hasRole('administrador')) {
            setFlash('error', 'No tienes permisos para realizar esta acción.');
            redirect(baseUrl('prestaciones-empresas'));
            return;
        }

        // Validar datos requeridos
        if (empty($_POST['id_empresa']) || empty($_POST['id_tipo_prestacion']) || !isset($_POST['valor_empresa'])) {
            setFlash('error', 'Todos los campos son requeridos.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('prestaciones-empresas/create'));
            return;
        }

        // Validar valor numérico
        if (!is_numeric($_POST['valor_empresa']) || $_POST['valor_empresa'] < 0) {
            setFlash('error', 'El valor debe ser un número válido mayor o igual a cero.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('prestaciones-empresas/create'));
            return;
        }

        $data = [
            'id_empresa' => $_POST['id_empresa'],
            'id_tipo_prestacion' => $_POST['id_tipo_prestacion'],
            'valor_empresa' => $_POST['valor_empresa'],
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        try {
            $id = $this->prestacionEmpresaModel->create($data);

            if ($id) {
                setFlash('success', 'Configuración creada exitosamente.');
                redirect(baseUrl('prestaciones-empresas'));
            } else {
                setFlash('error', 'Error al crear la configuración.');
                $_SESSION['form_data'] = $_POST;
                redirect(baseUrl('prestaciones-empresas/create'));
            }
        } catch (Exception $e) {
            setFlash('error', $e->getMessage());
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('prestaciones-empresas/create'));
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        // Solo administradores
        if (!hasRole('administrador')) {
            setFlash('error', 'No tienes permisos para realizar esta acción.');
            redirect(baseUrl('prestaciones-empresas'));
            return;
        }

        $prestacionEmpresa = $this->prestacionEmpresaModel->getById($id);

        if (!$prestacionEmpresa) {
            setFlash('error', 'Configuración no encontrada.');
            redirect(baseUrl('prestaciones-empresas'));
            return;
        }

        $companies = $this->companyModel->getAll(['estado' => 'activo']);
        $tiposPrestacion = $this->tipoPrestacionModel->getAll(['estado' => 'activo']);

        $title = 'Editar Configuración Prestación-Empresa';
        include __DIR__ . '/../../views/prestaciones_empresas/edit.php';
    }

    /**
     * Actualizar configuración
     */
    public function update($id)
    {
        $this->validateCSRFToken();

        // Solo administradores
        if (!hasRole('administrador')) {
            setFlash('error', 'No tienes permisos para realizar esta acción.');
            redirect(baseUrl('prestaciones-empresas'));
            return;
        }

        // Validar datos requeridos
        if (empty($_POST['id_empresa']) || empty($_POST['id_tipo_prestacion']) || !isset($_POST['valor_empresa'])) {
            setFlash('error', 'Todos los campos son requeridos.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('prestaciones-empresas/edit/' . $id));
            return;
        }

        // Validar valor numérico
        if (!is_numeric($_POST['valor_empresa']) || $_POST['valor_empresa'] < 0) {
            setFlash('error', 'El valor debe ser un número válido mayor o igual a cero.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('prestaciones-empresas/edit/' . $id));
            return;
        }

        $data = [
            'id_empresa' => $_POST['id_empresa'],
            'id_tipo_prestacion' => $_POST['id_tipo_prestacion'],
            'valor_empresa' => $_POST['valor_empresa'],
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        try {
            if ($this->prestacionEmpresaModel->update($id, $data)) {
                setFlash('success', 'Configuración actualizada exitosamente.');
                redirect(baseUrl('prestaciones-empresas'));
            } else {
                setFlash('error', 'Error al actualizar la configuración.');
                $_SESSION['form_data'] = $_POST;
                redirect(baseUrl('prestaciones-empresas/edit/' . $id));
            }
        } catch (Exception $e) {
            setFlash('error', $e->getMessage());
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('prestaciones-empresas/edit/' . $id));
        }
    }

    /**
     * Eliminar configuración
     */
    public function delete($id)
    {
        $this->validateCSRFToken();

        // Solo administradores
        if (!hasRole('administrador')) {
            setFlash('error', 'No tienes permisos para realizar esta acción.');
            redirect(baseUrl('prestaciones-empresas'));
            return;
        }

        if ($this->prestacionEmpresaModel->delete($id)) {
            setFlash('success', 'Configuración eliminada exitosamente.');
        } else {
            setFlash('error', 'Error al eliminar la configuración.');
        }

        redirect(baseUrl('prestaciones-empresas'));
    }

    /**
     * Cambiar estado
     */
    public function changeStatus($id)
    {
        $this->validateCSRFToken();

        // Solo administradores
        if (!hasRole('administrador')) {
            setFlash('error', 'No tienes permisos para realizar esta acción.');
            redirect(baseUrl('prestaciones-empresas'));
            return;
        }

        $status = $_POST['status'] ?? 'activo';

        if ($this->prestacionEmpresaModel->changeStatus($id, $status)) {
            setFlash('success', 'Estado actualizado exitosamente.');
        } else {
            setFlash('error', 'Error al actualizar el estado.');
        }

        redirect(baseUrl('prestaciones-empresas'));
    }

    /**
     * Vista de configuración de prestaciones para una empresa específica
     */
    public function config($idEmpresa)
    {
        // Obtener datos de la empresa
        $empresa = $this->companyModel->getById($idEmpresa);

        if (!$empresa) {
            setFlash('error', 'Empresa no encontrada.');
            redirect(baseUrl('companies'));
            return;
        }

        // Obtener prestaciones configuradas para esta empresa
        $prestacionesConfiguradas = $this->prestacionEmpresaModel->getPrestacionesByEmpresa($idEmpresa);

        // Obtener todas las prestaciones activas para saber cuáles faltan
        $todasPrestaciones = $this->tipoPrestacionModel->getAll(['estado' => 'activo']);

        // Crear un mapa de prestaciones configuradas
        $configuradas = [];
        foreach ($prestacionesConfiguradas as $pc) {
            $configuradas[$pc['id_tipo_prestacion']] = $pc;
        }

        $title = 'Configuración de Prestaciones - ' . $empresa['nombre'];
        include __DIR__ . '/../../views/prestaciones_empresas/config.php';
    }

    /**
     * Guardar configuración desde la vista por empresa (AJAX)
     */
    public function saveConfig()
    {
        header('Content-Type: application/json');

        $this->validateCSRFToken();

        $idEmpresa = $_POST['id_empresa'] ?? null;
        $idTipoPrestacion = $_POST['id_tipo_prestacion'] ?? null;
        $valorEmpresa = $_POST['valor_empresa'] ?? null;

        if (!$idEmpresa || !$idTipoPrestacion || !isset($valorEmpresa)) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }

        try {
            // Verificar si ya existe
            $existing = $this->prestacionEmpresaModel->findByEmpresaPrestacion($idEmpresa, $idTipoPrestacion);

            if ($existing) {
                // Actualizar
                $data = [
                    'id_empresa' => $idEmpresa,
                    'id_tipo_prestacion' => $idTipoPrestacion,
                    'valor_empresa' => $valorEmpresa,
                    'estado' => 'activo'
                ];
                $this->prestacionEmpresaModel->update($existing['id'], $data);
                echo json_encode(['success' => true, 'message' => 'Valor actualizado correctamente']);
            } else {
                // Crear
                $data = [
                    'id_empresa' => $idEmpresa,
                    'id_tipo_prestacion' => $idTipoPrestacion,
                    'valor_empresa' => $valorEmpresa,
                    'estado' => 'activo'
                ];
                $this->prestacionEmpresaModel->create($data);
                echo json_encode(['success' => true, 'message' => 'Configuración creada correctamente']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API: Obtener prestaciones de una empresa (para AJAX)
     */
    public function getPrestacionesEmpresa()
    {
        header('Content-Type: application/json');

        $idEmpresa = $_GET['id_empresa'] ?? null;

        if (!$idEmpresa) {
            echo json_encode(['success' => false, 'message' => 'ID de empresa requerido']);
            return;
        }

        $prestaciones = $this->prestacionEmpresaModel->getPrestacionesByEmpresa($idEmpresa);

        echo json_encode([
            'success' => true,
            'count' => count($prestaciones),
            'prestaciones' => $prestaciones,
            'prestacion_ids' => array_map(fn($p) => (int)$p['id_tipo_prestacion'], $prestaciones)
        ]);
    }

    /**
     * API: Obtener valor empresa para una prestación (para AJAX)
     */
    public function getValorEmpresa()
    {
        header('Content-Type: application/json');

        $idEmpresa = $_GET['id_empresa'] ?? null;
        $idTipoPrestacion = $_GET['id_tipo_prestacion'] ?? null;

        if (!$idEmpresa || !$idTipoPrestacion) {
            echo json_encode(['success' => false, 'message' => 'Parámetros incompletos']);
            return;
        }

        $valor = $this->prestacionEmpresaModel->getValorByEmpresaPrestacion($idEmpresa, $idTipoPrestacion);

        echo json_encode([
            'success' => $valor !== null,
            'valor_empresa' => $valor,
            'message' => $valor !== null ? 'Valor encontrado' : 'No hay valor configurado para esta combinación'
        ]);
    }
}

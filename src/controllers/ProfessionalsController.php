<?php

require_once __DIR__ . '/../models/Professional.php';
require_once __DIR__ . '/../models/Frequency.php';
require_once __DIR__ . '/../models/LiquidacionMensual.php';
require_once __DIR__ . '/../middleware/Auth.php';

class ProfessionalsController
{
    /**
     * Campos financieros de empresa que deben ocultarse al rol Coordinador.
     * Lista blanca por nombre exacto de clave — NO usar substring match porque
     * borraría `empresa_nombre`, que sí debe verse.
     */
    private const CAMPOS_EMPRESA_SANITIZAR = [
        'valor_empresa',
        'total_empresa',
        'total_valor_empresa',
        'acumulado_empresa_30',
        'acumulado_empresa_60',
        'acumulado_empresa_90',
        'acum_emp_30',
        'acum_emp_60',
        'acum_emp_90',
        'mensual_empresa',
    ];

    private $professionalModel;
    private $frequencyModel;
    private $liquidacionModel;

    public function __construct()
    {
        Auth::requireAuth();
        Auth::checkTimeout();
        $this->professionalModel = new Professional();
        $this->frequencyModel = new Frequency();
        $this->liquidacionModel = new LiquidacionMensual();
    }

    /**
     * Listado de profesionales
     */
    public function index()
    {
        // Obtener filtros
        $filters = [
            'search' => $_GET['search'] ?? '',
            'especialidad' => $_GET['especialidad'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];

        // Configuración de paginación
        $itemsPerPage = 15;
        $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $offset = ($currentPage - 1) * $itemsPerPage;

        // Obtener total de profesionales con filtros
        $totalItems = $this->professionalModel->count($filters);
        $totalPages = ceil($totalItems / $itemsPerPage);

        // Obtener profesionales con paginación
        $professionals = $this->professionalModel->getAll($filters, $itemsPerPage, $offset);

        // Obtener especialidades para el filtro
        $especialidades = $this->professionalModel->getEspecialidades();

        // Datos de paginación
        $pagination = [
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
            'items_per_page' => $itemsPerPage,
            'offset' => $offset
        ];

        // Renderizar vista
        $title = 'Gestión de Profesionales';
        include __DIR__ . '/../../views/professionals/index.php';
    }

    /**
     * Vista de reportes financieros con liquidaciones
     */
    public function reports()
    {
        $periodo = $_GET['periodo'] ?? date('Y-m');
        $filters = [
            'period' => $_GET['period'] ?? 30,
            'professional' => $_GET['professional'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];

        // Obtener profesionales para el filtro
        $professionals = $this->professionalModel->getAll(['estado' => 'activo']);

        // Obtener datos de liquidación real para el período seleccionado
        $resumenLiquidacion = $this->liquidacionModel->getResumenByPeriodo($periodo, [
            'profesional' => $filters['professional'],
            'search' => $filters['search']
        ]);
        $totalesLiquidacion = $this->liquidacionModel->getTotalesByPeriodo($periodo);
        $periodosDisponibles = $this->liquidacionModel->getPeriodosDisponibles();
        $tieneLiquidacion = ($totalesLiquidacion && $totalesLiquidacion['total_prestaciones'] > 0);

        // Agregar período actual si no está en la lista
        if (!in_array($periodo, $periodosDisponibles)) {
            array_unshift($periodosDisponibles, $periodo);
        }

        // Obtener datos financieros teóricos
        $reportData = $this->getFinancialReport($filters['period'], $filters['professional'], $filters['search']);

        // Configuración de paginación (para vista teórica)
        $itemsPerPage = 6;
        $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $offset = ($currentPage - 1) * $itemsPerPage;
        $totalItems = count($reportData['professionals']);
        $totalPages = ceil($totalItems / $itemsPerPage);
        $paginatedProfessionals = array_slice($reportData['professionals'], $offset, $itemsPerPage);

        $pagination = [
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'total_items' => $totalItems,
            'items_per_page' => $itemsPerPage,
            'offset' => $offset
        ];

        $reportData['professionals'] = $paginatedProfessionals;

        $this->sanitizeEmpresaForRole($resumenLiquidacion);
        if (is_array($totalesLiquidacion)) {
            $this->sanitizeEmpresaForRole($totalesLiquidacion);
        }
        $this->sanitizeEmpresaForRole($reportData);

        $title = 'Reportes Financieros - Profesionales';
        include __DIR__ . '/../../views/professionals/reports.php';
    }

    /**
     * Obtener reporte financiero
     * Usa sesiones realizadas de liquidaciones_mensuales cuando existen
     */
    private function getFinancialReport($period, $professionalId = '', $searchName = '')
    {
        $db = Database::getInstance()->getConnection();

        // Determinar período actual para buscar liquidaciones
        $periodoActual = date('Y-m');

        // Verificar si la tabla liquidaciones_mensuales existe
        $tablaLiqExiste = false;
        try {
            $checkStmt = $db->query("SELECT 1 FROM liquidaciones_mensuales LIMIT 0");
            $tablaLiqExiste = true;
        } catch (\PDOException $e) {
            $tablaLiqExiste = false;
        }

        // Construir query base - incluye finalizados que tengan fecha_fin en el último período
        $liqSelect = $tablaLiqExiste
            ? "lm.sesiones_realizadas as liq_sesiones_realizadas,
               lm.total_profesional as liq_total_profesional,
               lm.total_empresa as liq_total_empresa"
            : "NULL as liq_sesiones_realizadas,
               NULL as liq_total_profesional,
               NULL as liq_total_empresa";

        $liqJoin = $tablaLiqExiste
            ? "LEFT JOIN liquidaciones_mensuales lm ON lm.id_prestacion_paciente = pp.id AND lm.periodo = ?"
            : "";

        $query = "
            SELECT
                prof.id as profesional_id,
                prof.nombre as profesional_nombre,
                prof.especialidad,
                pac.id as paciente_id,
                pac.nombre_completo as paciente_nombre,
                pp.id as prestacion_paciente_id,
                pp.frecuencia_servicio,
                pp.id_frecuencia,
                pp.sesiones_personalizadas,
                pp.horas_semana,
                pp.horas_por_dia,
                pp.horas_mes,
                pp.horas_mes_override,
                pp.dias_semana,
                pp.valor_profesional,
                pp.valor_empresa,
                pp.fecha_inicio,
                pp.fecha_fin,
                pp.estado as prestacion_estado,
                e.nombre as empresa_nombre,
                tp.nombre as prestacion_nombre,
                tp.modo_frecuencia,
                f.nombre as frecuencia_nombre,
                f.sesiones_por_mes,
                {$liqSelect}
            FROM profesionales prof
            LEFT JOIN prestaciones_pacientes pp ON prof.id = pp.id_profesional
            LEFT JOIN pacientes pac ON pp.id_paciente = pac.id
            LEFT JOIN empresas e ON pp.id_empresa = e.id
            LEFT JOIN tipos_prestacion tp ON pp.id_tipo_prestacion = tp.id
            LEFT JOIN frecuencias f ON pp.id_frecuencia = f.id
            {$liqJoin}
            WHERE prof.estado = 'activo'
            AND (
                pp.estado = 'activo'
                OR (pp.estado = 'finalizado' AND pp.fecha_fin >= DATE_SUB(NOW(), INTERVAL ? DAY))
                OR pp.estado IS NULL
            )
        ";

        $params = [];
        if ($tablaLiqExiste) {
            $params[] = $periodoActual;
        }
        $params[] = $period;

        // Filtrar por profesional específico (por ID)
        if (!empty($professionalId)) {
            $query .= " AND prof.id = ?";
            $params[] = $professionalId;
        }

        // Filtrar por búsqueda de nombre de profesional
        if (!empty($searchName)) {
            $query .= " AND prof.nombre LIKE ?";
            $params[] = '%' . $searchName . '%';
        }

        $query .= " ORDER BY prof.nombre, pac.nombre_completo";

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $data = $stmt->fetchAll();

        // Procesar datos por profesional
        $reportByProfessional = [];
        $totals = [
            'total_profesionales' => 0,
            'total_pacientes' => 0,
            'total_sesiones' => 0,
            'total_valor_profesional' => 0,
            'total_valor_empresa' => 0
        ];

        foreach ($data as $row) {
            $profId = $row['profesional_id'];

            if (!isset($reportByProfessional[$profId])) {
                $reportByProfessional[$profId] = [
                    'profesional_id' => $profId,
                    'profesional_nombre' => $row['profesional_nombre'],
                    'especialidad' => $row['especialidad'],
                    'pacientes' => [],
                    'total_pacientes' => 0,
                    'total_sesiones' => 0,
                    'acumulado_profesional_30' => 0,
                    'acumulado_empresa_30' => 0,
                    'acumulado_profesional_60' => 0,
                    'acumulado_empresa_60' => 0,
                    'acumulado_profesional_90' => 0,
                    'acumulado_empresa_90' => 0
                ];
                $totals['total_profesionales']++;
            }

            // Si el profesional tiene pacientes
            if ($row['paciente_id']) {
                // Detectar modo de frecuencia
                $modo = $row['modo_frecuencia'] ?? 'sesiones';
                $valorProf = floatval($row['valor_profesional'] ?? 0);
                $valorEmp = floatval($row['valor_empresa'] ?? 0);

                // Verificar si hay liquidación real cargada
                $usaLiquidacionReal = ($row['liq_sesiones_realizadas'] !== null);

                if ($usaLiquidacionReal) {
                    // Usar datos de liquidación real
                    $unidadesMes = floatval($row['liq_sesiones_realizadas']);
                    $unidades30 = $unidadesMes;
                    // Para 60/90, usar el valor real del mes (no multiplicar)
                    $unidades60 = $unidadesMes * 2;
                    $unidades90 = $unidadesMes * 3;

                    if ($modo === 'horas') {
                        $frecuenciaDisplay = $this->frequencyModel->formatFrecuencia($row, $period) . ' (real)';
                    } else {
                        $frecuenciaDisplay = ($row['frecuencia_nombre'] ?? $row['frecuencia_servicio'] ?? '') . ' (real)';
                    }
                } elseif ($modo === 'horas') {
                    // Modo horas: calcular horas por mes según el período
                    $horasPorMes = $this->frequencyModel->getHoursPerMonth($row, $period);

                    $unidades30 = $horasPorMes;
                    $unidades60 = $horasPorMes * 2;
                    $unidades90 = $horasPorMes * 3;

                    $frecuenciaDisplay = $this->frequencyModel->formatFrecuencia($row, $period);
                    $unidadesMes = $horasPorMes;
                } else {
                    // Modo sesiones: usar frecuencia estandarizada o calcular desde texto
                    if ($row['id_frecuencia']) {
                        $sesionesPorMes = $this->frequencyModel->getSessionsPerMonth(
                            $row['id_frecuencia'],
                            $row['sesiones_personalizadas']
                        );
                        $frecuenciaDisplay = $row['frecuencia_nombre'];
                    } else {
                        $frecuencia = $row['frecuencia_servicio'] ?? '';
                        $sesionesPorMes = $this->calculateSessionsPerMonth($frecuencia);
                        $frecuenciaDisplay = $frecuencia;
                    }

                    $unidades30 = $sesionesPorMes;
                    $unidades60 = $sesionesPorMes * 2;
                    $unidades90 = $sesionesPorMes * 3;
                    $unidadesMes = $sesionesPorMes;
                }

                // Acumulados por período
                $reportByProfessional[$profId]['acumulado_profesional_30'] += $valorProf * $unidades30;
                $reportByProfessional[$profId]['acumulado_empresa_30'] += $valorEmp * $unidades30;
                $reportByProfessional[$profId]['acumulado_profesional_60'] += $valorProf * $unidades60;
                $reportByProfessional[$profId]['acumulado_empresa_60'] += $valorEmp * $unidades60;
                $reportByProfessional[$profId]['acumulado_profesional_90'] += $valorProf * $unidades90;
                $reportByProfessional[$profId]['acumulado_empresa_90'] += $valorEmp * $unidades90;

                $reportByProfessional[$profId]['total_sesiones'] += $unidadesMes;
                $reportByProfessional[$profId]['total_pacientes']++;

                // Agregar detalle del paciente
                $reportByProfessional[$profId]['pacientes'][] = [
                    'paciente_nombre' => $row['paciente_nombre'],
                    'empresa_nombre' => $row['empresa_nombre'],
                    'prestacion_nombre' => $row['prestacion_nombre'],
                    'frecuencia' => $frecuenciaDisplay,
                    'sesiones_mes' => $unidadesMes,
                    'modo_frecuencia' => $modo,
                    'valor_profesional' => $valorProf,
                    'valor_empresa' => $valorEmp,
                    'usa_liquidacion_real' => $usaLiquidacionReal,
                    'acum_prof_30' => $valorProf * $unidades30,
                    'acum_emp_30' => $valorEmp * $unidades30,
                    'acum_prof_60' => $valorProf * $unidades60,
                    'acum_emp_60' => $valorEmp * $unidades60,
                    'acum_prof_90' => $valorProf * $unidades90,
                    'acum_emp_90' => $valorEmp * $unidades90
                ];

                // Totales generales
                $totals['total_pacientes']++;
                $totals['total_sesiones'] += $unidadesMes;
                $totals['total_valor_profesional'] += $valorProf * $unidades30;
                $totals['total_valor_empresa'] += $valorEmp * $unidades30;
            }
        }

        $professionalsList = array_values($reportByProfessional);
        usort($professionalsList, function ($a, $b) {
            return $b['acumulado_profesional_30'] <=> $a['acumulado_profesional_30'];
        });

        return [
            'professionals' => $professionalsList,
            'totals' => $totals,
            'period' => $period
        ];
    }

    /**
     * Calcular sesiones por mes según frecuencia
     */
    private function calculateSessionsPerMonth($frecuencia)
    {
        if (empty($frecuencia)) {
            return 4; // Default: 1 vez por semana
        }

        $frecuencia = strtolower(trim($frecuencia));

        // Buscar patrón: número + (x/por/veces) + (semana/semanal)
        // Ejemplos: "2x semana", "2 por semana", "3 veces por semana", "2 x sem"
        if (preg_match('/(\d+)\s*(?:x|por|veces?)\s*(?:por\s+)?(?:la\s+)?(?:semana|sem(?:anal)?)/i', $frecuencia, $matches)) {
            return intval($matches[1]) * 4; // número × 4 semanas
        }

        // Buscar solo número seguido de semana/semanal
        // Ejemplos: "2 semana", "3 semanal"
        if (preg_match('/(\d+)\s*(?:semana|sem(?:anal)?)/i', $frecuencia, $matches)) {
            return intval($matches[1]) * 4;
        }

        // Palabras específicas
        $palabrasNumeros = [
            'un' => 1, 'una' => 1,
            'dos' => 2,
            'tres' => 3,
            'cuatro' => 4,
            'cinco' => 5,
            'seis' => 6,
            'siete' => 7
        ];

        foreach ($palabrasNumeros as $palabra => $numero) {
            if (preg_match('/\b' . $palabra . '\b.*(?:semana|sem)/i', $frecuencia)) {
                return $numero * 4;
            }
        }

        // Casos específicos comunes
        if (preg_match('/2\s*x/i', $frecuencia)) {
            return 8;
        }
        if (preg_match('/3\s*x/i', $frecuencia)) {
            return 12;
        }
        if (preg_match('/4\s*x/i', $frecuencia)) {
            return 16;
        }
        if (preg_match('/5\s*x/i', $frecuencia)) {
            return 20;
        }

        // Diario
        if (preg_match('/diario|todos?\s+los?\s+d[ií]as?|lun.*vie/i', $frecuencia)) {
            return 20; // ~20 días hábiles
        }

        // Quincenal
        if (preg_match('/quincenal|cada\s+15|c\/15|cada\s+quince/i', $frecuencia)) {
            return 2;
        }

        // Mensual
        if (preg_match('/mensual|cada\s+mes|1\s*(?:vez|x)\s*(?:al|por)\s*mes/i', $frecuencia)) {
            return 1;
        }

        // Default: 1 vez por semana = 4 sesiones al mes
        return 4;
    }

    /**
     * Ver detalle de profesional
     */
    public function view($id)
    {
        $professional = $this->professionalModel->getById($id);

        if (!$professional) {
            setFlash('error', 'Profesional no encontrado.');
            redirect(baseUrl('professionals'));
            return;
        }

        // Obtener prestaciones activas del profesional
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT
                pp.*,
                p.nombre_completo as paciente_nombre,
                tp.nombre as prestacion_nombre,
                tp.modo_frecuencia,
                e.nombre as empresa_nombre,
                f.nombre as frecuencia_nombre,
                f.sesiones_por_mes
            FROM prestaciones_pacientes pp
            INNER JOIN pacientes p ON pp.id_paciente = p.id
            INNER JOIN tipos_prestacion tp ON pp.id_tipo_prestacion = tp.id
            LEFT JOIN empresas e ON pp.id_empresa = e.id
            LEFT JOIN frecuencias f ON pp.id_frecuencia = f.id
            WHERE pp.id_profesional = ?
            ORDER BY pp.created_at DESC
        ");
        $stmt->execute([$id]);
        $prestaciones = $stmt->fetchAll();

        // Calcular estadísticas financieras
        $stats = $this->calculateFinancialStats($prestaciones);

        $title = 'Detalle de Profesional';
        include __DIR__ . '/../../views/professionals/view.php';
    }

    /**
     * Calcular estadísticas financieras
     */
    private function calculateFinancialStats($prestaciones)
    {
        $stats = [
            'total_prestaciones' => count($prestaciones),
            'prestaciones_activas' => 0,
            'prestaciones_recurrentes' => 0,
            'total_mensual_profesional' => 0,
            'total_mensual_empresa' => 0,
            'por_paciente' => []
        ];

        foreach ($prestaciones as $prestacion) {
            // Contar activas
            if ($prestacion['estado'] === 'activo') {
                $stats['prestaciones_activas']++;
            }

            // Contar recurrentes
            if ($prestacion['es_recurrente']) {
                $stats['prestaciones_recurrentes']++;
            }

            // Detectar modo de frecuencia
            $modo = $prestacion['modo_frecuencia'] ?? 'sesiones';

            if ($modo === 'horas') {
                // Modo horas: usar horas_por_dia × días del mes actual
                $multiplicador = $this->frequencyModel->getHoursPerMonth($prestacion);
                $frecuenciaDisplay = $this->frequencyModel->formatFrecuencia($prestacion);
            } else {
                // Modo sesiones: calcular monto mensual según frecuencia
                if (!empty($prestacion['id_frecuencia'])) {
                    $multiplicador = $this->frequencyModel->getSessionsPerMonth(
                        $prestacion['id_frecuencia'],
                        $prestacion['sesiones_personalizadas'] ?? null
                    );
                    $frecuenciaDisplay = $prestacion['frecuencia_nombre'] ?? $prestacion['frecuencia_servicio'] ?? '';
                } else {
                    $frecuencia = strtolower($prestacion['frecuencia_servicio'] ?? '');
                    $multiplicador = $this->getMonthlyMultiplier($frecuencia);
                    $frecuenciaDisplay = $prestacion['frecuencia_servicio'] ?? '';
                }
            }

            $montoProfesional = ($prestacion['valor_profesional'] ?? 0) * $multiplicador;
            $montoEmpresa = ($prestacion['valor_empresa'] ?? 0) * $multiplicador;

            $stats['total_mensual_profesional'] += $montoProfesional;
            $stats['total_mensual_empresa'] += $montoEmpresa;

            // Guardar por paciente para detalle
            $stats['por_paciente'][] = [
                'id' => $prestacion['id'],
                'paciente' => $prestacion['paciente_nombre'],
                'prestacion' => $prestacion['prestacion_nombre'],
                'empresa' => $prestacion['empresa_nombre'],
                'frecuencia' => $frecuenciaDisplay,
                'modo_frecuencia' => $modo,
                'valor_profesional' => $prestacion['valor_profesional'],
                'valor_empresa' => $prestacion['valor_empresa'],
                'mensual_profesional' => $montoProfesional,
                'mensual_empresa' => $montoEmpresa,
                'multiplicador' => $multiplicador,
                'estado' => $prestacion['estado'],
                'es_recurrente' => $prestacion['es_recurrente']
            ];
        }

        return $stats;
    }

    /**
     * Obtener multiplicador mensual según frecuencia
     */
    private function getMonthlyMultiplier($frecuencia)
    {
        // Patrones comunes de frecuencia
        if (empty($frecuencia)) {
            return 4; // Default: 1 vez por semana = 4 veces al mes
        }

        $frecuencia = strtolower($frecuencia);

        // Diario
        if (preg_match('/diario|todos los d[ií]as|lunes.*viernes/i', $frecuencia)) {
            return 20; // ~20 días hábiles
        }

        // Varias veces por semana
        if (preg_match('/lunes.*miércoles.*viernes/i', $frecuencia)) {
            return 12; // 3 veces por semana
        }

        if (preg_match('/lunes.*jueves/i', $frecuencia) ||
            preg_match('/martes.*viernes/i', $frecuencia)) {
            return 8; // 2 veces por semana
        }

        // Una vez por semana
        if (preg_match('/semanal|1.*semana|una.*semana/i', $frecuencia)) {
            return 4;
        }

        // Quincenal
        if (preg_match('/quincenal|cada 15|c\/15/i', $frecuencia)) {
            return 2;
        }

        // Mensual
        if (preg_match('/mensual|1.*mes|una.*mes/i', $frecuencia)) {
            return 1;
        }

        // Por defecto: contar número de días mencionados
        $diasSemana = ['lunes', 'martes', 'miércoles', 'miercoles', 'jueves', 'viernes', 'sábado', 'sabado', 'domingo'];
        $count = 0;
        foreach ($diasSemana as $dia) {
            if (stripos($frecuencia, $dia) !== false) {
                $count++;
            }
        }

        return $count > 0 ? $count * 4 : 4; // Default: 4 veces al mes
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $title = 'Nuevo Profesional';
        include __DIR__ . '/../../views/professionals/create.php';
    }

    /**
     * Guardar nuevo profesional
     */
    public function store()
    {
        $this->validateCSRFToken();

        // Validar datos
        $errors = $this->validateProfessionalData($_POST);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('professionals/create'));
            return;
        }

        // Verificar email único (solo si se proporciona)
        if (!empty($_POST['email']) && $this->professionalModel->emailExists($_POST['email'])) {
            setFlash('error', 'El email ya está registrado.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('professionals/create'));
            return;
        }

        // Preparar datos
        $data = [
            'nombre' => trim($_POST['nombre']),
            'especialidad' => trim($_POST['especialidad'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Crear profesional
        $professionalId = $this->professionalModel->create($data);

        if ($professionalId) {
            setFlash('success', 'Profesional creado exitosamente.');
            redirect(baseUrl('professionals'));
        } else {
            setFlash('error', 'Error al crear el profesional.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('professionals/create'));
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $professional = $this->professionalModel->getById($id);

        if (!$professional) {
            setFlash('error', 'Profesional no encontrado.');
            redirect(baseUrl('professionals'));
            return;
        }

        $title = 'Editar Profesional';
        include __DIR__ . '/../../views/professionals/edit.php';
    }

    /**
     * Actualizar profesional
     */
    public function update($id)
    {
        $this->validateCSRFToken();

        $professional = $this->professionalModel->getById($id);

        if (!$professional) {
            setFlash('error', 'Profesional no encontrado.');
            redirect(baseUrl('professionals'));
            return;
        }

        // Validar datos
        $errors = $this->validateProfessionalData($_POST, $id);

        if (!empty($errors)) {
            setFlash('error', 'Por favor corrija los errores en el formulario.');
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('professionals/edit/' . $id));
            return;
        }

        // Verificar email único (solo si se proporciona)
        if (!empty($_POST['email']) && $this->professionalModel->emailExists($_POST['email'], $id)) {
            setFlash('error', 'El email ya está registrado.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('professionals/edit/' . $id));
            return;
        }

        // Preparar datos
        $data = [
            'nombre' => trim($_POST['nombre']),
            'especialidad' => trim($_POST['especialidad'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'estado' => $_POST['estado'] ?? 'activo'
        ];

        // Actualizar profesional
        if ($this->professionalModel->update($id, $data)) {
            setFlash('success', 'Profesional actualizado exitosamente.');
            redirect(baseUrl('professionals'));
        } else {
            setFlash('error', 'Error al actualizar el profesional.');
            $_SESSION['form_data'] = $_POST;
            redirect(baseUrl('professionals/edit/' . $id));
        }
    }

    /**
     * Eliminar (desactivar) profesional
     */
    public function delete($id)
    {
        // Solo admin puede eliminar
        if (!hasRole('administrador')) {
            setFlash('error', 'No tiene permisos para realizar esta acción.');
            redirect(baseUrl('professionals'));
            return;
        }

        $this->validateCSRFToken();

        if ($this->professionalModel->delete($id)) {
            setFlash('success', 'Profesional desactivado exitosamente.');
        } else {
            setFlash('error', 'Error al desactivar el profesional.');
        }

        redirect(baseUrl('professionals'));
    }

    /**
     * Formulario para cargar sesiones realizadas de un mes
     */
    public function cargar()
    {
        $periodo = $_GET['periodo'] ?? date('Y-m');
        $filtroProf = $_GET['profesional'] ?? '';

        $prestaciones = $this->getPrestacionesParaPeriodo($periodo, $filtroProf);

        // Cargar liquidaciones existentes para este período
        foreach ($prestaciones as &$prest) {
            $liq = $this->liquidacionModel->getByPrestacionPeriodo($prest['id'], $periodo);
            if ($liq) {
                $prest['sesiones_realizadas_guardadas'] = $liq['sesiones_realizadas'];
                $prest['observaciones_guardadas'] = $liq['observaciones'];
            }
        }
        unset($prest);

        $professionals = $this->professionalModel->getAll(['estado' => 'activo']);

        $this->sanitizeEmpresaForRole($prestaciones);

        $title = 'Cargar Sesiones Realizadas';
        include __DIR__ . '/../../views/professionals/cargar.php';
    }

    /**
     * Guardar sesiones realizadas
     */
    public function storeLiquidacion()
    {
        $this->validateCSRFToken();

        $periodo = $_POST['periodo'] ?? '';
        $prestaciones = $_POST['prestaciones'] ?? [];

        if (empty($periodo) || empty($prestaciones)) {
            setFlash('error', 'Datos incompletos.');
            redirect(baseUrl('professionals/cargar?periodo=' . $periodo));
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmtPrest = $db->prepare("SELECT pp.id, pp.valor_profesional, pp.valor_empresa,
                                          pp.horas_semana, pp.horas_por_dia, pp.horas_mes, pp.horas_mes_override,
                                          pp.id_frecuencia, pp.sesiones_personalizadas,
                                          tp.modo_frecuencia
                                   FROM prestaciones_pacientes pp
                                   INNER JOIN tipos_prestacion tp ON pp.id_tipo_prestacion = tp.id
                                   WHERE pp.id = ?");

        $items = [];
        foreach ($prestaciones as $idPrestacion => $data) {
            if (!isset($data['sesiones_realizadas']) || $data['sesiones_realizadas'] === '') {
                continue;
            }

            $stmtPrest->execute([intval($idPrestacion)]);
            $prest = $stmtPrest->fetch();
            if (!$prest) {
                continue;
            }

            $prest['_periodo_liquidacion'] = $periodo;
            $items[] = [
                'id_prestacion_paciente' => intval($idPrestacion),
                'periodo' => $periodo,
                'sesiones_esperadas' => $this->calcularSesionesEsperadas($prest),
                'sesiones_realizadas' => floatval($data['sesiones_realizadas']),
                'valor_profesional' => floatval($prest['valor_profesional']),
                'valor_empresa' => floatval($prest['valor_empresa']),
                'observaciones' => trim($data['observaciones'] ?? '')
            ];
        }

        if (empty($items)) {
            setFlash('error', 'No se ingresaron sesiones realizadas.');
            redirect(baseUrl('professionals/cargar?periodo=' . $periodo));
            return;
        }

        if ($this->liquidacionModel->saveBatch($items)) {
            setFlash('success', 'Liquidación guardada exitosamente para el período ' . $this->formatPeriodo($periodo) . '.');
            redirect(baseUrl('professionals/reports?periodo=' . $periodo));
        } else {
            setFlash('error', 'Error al guardar la liquidación.');
            redirect(baseUrl('professionals/cargar?periodo=' . $periodo));
        }
    }

    /**
     * Ver detalle de liquidación de un profesional en un período
     */
    public function detalleLiquidacion($profesionalId)
    {
        $periodo = $_GET['periodo'] ?? date('Y-m');

        $detalle = $this->liquidacionModel->getDetalleByProfesionalPeriodo($profesionalId, $periodo);
        $professional = $this->professionalModel->getById($profesionalId);

        if (!$professional) {
            setFlash('error', 'Profesional no encontrado.');
            redirect(baseUrl('professionals/reports'));
            return;
        }

        $this->sanitizeEmpresaForRole($detalle);

        $title = 'Detalle Liquidación - ' . $professional['nombre'];
        include __DIR__ . '/../../views/professionals/detalle-liquidacion.php';
    }

    /**
     * Obtener prestaciones elegibles para un período
     */
    private function getPrestacionesParaPeriodo($periodo, $filtroProf = '')
    {
        $db = Database::getInstance()->getConnection();

        $primerDia = $periodo . '-01';
        $ultimoDia = date('Y-m-t', strtotime($primerDia));

        $query = "SELECT
                    pp.id,
                    pp.id_paciente,
                    pp.id_profesional,
                    pp.id_empresa,
                    pp.id_frecuencia,
                    pp.sesiones_personalizadas,
                    pp.frecuencia_servicio,
                    pp.horas_semana,
                    pp.horas_por_dia,
                    pp.horas_mes,
                    pp.horas_mes_override,
                    pp.dias_semana,
                    pp.valor_profesional,
                    pp.valor_empresa,
                    pp.fecha_inicio,
                    pp.fecha_fin,
                    pp.es_recurrente,
                    pp.estado as prestacion_estado,
                    pac.nombre_completo as paciente_nombre,
                    prof.nombre as profesional_nombre,
                    prof.especialidad,
                    tp.nombre as prestacion_nombre,
                    tp.modo_frecuencia,
                    e.nombre as empresa_nombre,
                    f.nombre as frecuencia_nombre,
                    f.sesiones_por_mes
                  FROM prestaciones_pacientes pp
                  INNER JOIN pacientes pac ON pp.id_paciente = pac.id
                  INNER JOIN profesionales prof ON pp.id_profesional = prof.id
                  INNER JOIN tipos_prestacion tp ON pp.id_tipo_prestacion = tp.id
                  LEFT JOIN empresas e ON pp.id_empresa = e.id
                  LEFT JOIN frecuencias f ON pp.id_frecuencia = f.id
                  WHERE (
                    pp.estado = 'activo'
                    OR (pp.estado = 'finalizado' AND pp.fecha_fin >= ?)
                  )
                  AND pp.fecha_inicio <= ?";

        $params = [$primerDia, $ultimoDia];

        if (!empty($filtroProf)) {
            $query .= " AND pp.id_profesional = ?";
            $params[] = $filtroProf;
        }

        $query .= " ORDER BY prof.nombre, pac.nombre_completo";

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $prestaciones = $stmt->fetchAll();

        foreach ($prestaciones as &$prest) {
            $prest['_periodo_liquidacion'] = $periodo;
            $prest['sesiones_esperadas'] = $this->calcularSesionesEsperadas($prest);
        }
        unset($prest);

        return $prestaciones;
    }

    /**
     * Calcular sesiones esperadas según frecuencia
     */
    private function calcularSesionesEsperadas($prestacion)
    {
        $modo = $prestacion['modo_frecuencia'] ?? 'sesiones';

        if ($modo === 'horas') {
            $periodo = $prestacion['_periodo_liquidacion'] ?? null;
            return $this->frequencyModel->getHoursPerMonth($prestacion, $periodo);
        }

        if ($prestacion['id_frecuencia']) {
            return $this->frequencyModel->getSessionsPerMonth(
                $prestacion['id_frecuencia'],
                $prestacion['sesiones_personalizadas']
            );
        }

        return 4;
    }

    /**
     * Formatear período para mostrar
     */
    private function formatPeriodo($periodo)
    {
        $meses = [
            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
            '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
            '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
            '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
        ];
        $parts = explode('-', $periodo);
        return ($meses[$parts[1]] ?? $parts[1]) . ' ' . $parts[0];
    }

    /**
     * Validar CSRF token
     */
    private function validateCSRFToken()
    {
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlash('error', 'Token de seguridad inválido.');
            redirect(baseUrl('professionals'));
            exit;
        }
    }

    /**
     * Validar datos de profesional
     */
    private function validateProfessionalData($data, $excludeId = null)
    {
        $errors = [];

        // Nombre
        if (empty($data['nombre'])) {
            $errors['nombre'] = 'El nombre es obligatorio.';
        } elseif (strlen($data['nombre']) < 3) {
            $errors['nombre'] = 'El nombre debe tener al menos 3 caracteres.';
        }

        // Especialidad
        if (empty($data['especialidad'])) {
            $errors['especialidad'] = 'La especialidad es obligatoria.';
        } elseif (strlen($data['especialidad']) < 3) {
            $errors['especialidad'] = 'La especialidad debe tener al menos 3 caracteres.';
        }

        // Email (opcional pero debe ser válido si se proporciona)
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El email no es válido.';
        }

        // Teléfono (opcional pero debe tener formato válido si se proporciona)
        if (!empty($data['telefono']) && strlen($data['telefono']) < 7) {
            $errors['telefono'] = 'El teléfono debe tener al menos 7 dígitos.';
        }

        return $errors;
    }

    /**
     * Si el usuario es Coordinador, sobrescribe los campos financieros de empresa
     * a 0 recursivamente sobre arrays anidados, antes de pasar los datos a la vista.
     * Defensa en profundidad: aunque una vista olvide el gating, no expone montos.
     */
    private function sanitizeEmpresaForRole(array &$data): void
    {
        if (!isCoordinator()) {
            return;
        }

        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $this->sanitizeEmpresaForRole($value);
            } elseif (in_array($key, self::CAMPOS_EMPRESA_SANITIZAR, true)) {
                $value = 0;
            }
        }
    }
}

<?php
/**
 * Dashboard Controller
 * MedFlow - Sistema Administrativo Médico
 */

require_once __DIR__ . '/../middleware/Auth.php';

class DashboardController {

    public function __construct() {
        Auth::requireAuth();
        Auth::checkTimeout();
    }

    /**
     * Show dashboard based on user role
     */
    public function index() {
        $user = getCurrentUser();

        if ($user['rol'] === 'administrador') {
            $this->adminDashboard();
        } else {
            $this->coordinatorDashboard();
        }
    }

    /**
     * Admin dashboard
     */
    private function adminDashboard() {
        $data = $this->getDashboardData();
        view('dashboard/admin', $data);
    }

    /**
     * Coordinator dashboard
     */
    private function coordinatorDashboard() {
        $data = $this->getDashboardData();
        view('dashboard/coordinator', $data);
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardData() {
        $db = Database::getInstance()->getConnection();

        // Get patient statistics
        $stmt = $db->query("
            SELECT
                COUNT(*) as total_pacientes,
                SUM(CASE WHEN estado = 'activo' THEN 1 ELSE 0 END) as pacientes_activos,
                SUM(CASE WHEN estado = 'pausado' THEN 1 ELSE 0 END) as pacientes_pausados,
                SUM(CASE WHEN estado = 'finalizado' THEN 1 ELSE 0 END) as pacientes_finalizados
            FROM pacientes
        ");
        $patientStats = $stmt->fetch();

        // Get prestaciones statistics
        $stmt = $db->query("
            SELECT
                COUNT(*) as total_prestaciones,
                SUM(CASE WHEN es_recurrente = 1 THEN 1 ELSE 0 END) as prestaciones_recurrentes,
                SUM(CASE WHEN es_recurrente = 0 THEN 1 ELSE 0 END) as prestaciones_temporales
            FROM prestaciones_pacientes
        ");
        $prestacionStats = $stmt->fetch();

        // Get prestaciones expiring soon
        $config = require __DIR__ . '/../../config/config.php';
        $alertDays = $config['notifications']['patient_expiration_alert_days'];

        $stmt = $db->prepare("
            SELECT COUNT(DISTINCT pp.id_paciente) as proximos_vencer
            FROM prestaciones_pacientes pp
            WHERE pp.es_recurrente = 0
            AND pp.fecha_fin IS NOT NULL
            AND DATEDIFF(pp.fecha_fin, CURDATE()) BETWEEN 0 AND ?
        ");
        $stmt->execute([$alertDays]);
        $expiringCount = $stmt->fetchColumn();

        // Get professional count
        $stmt = $db->query("
            SELECT COUNT(*) FROM profesionales WHERE estado = 'activo'
        ");
        $professionalCount = $stmt->fetchColumn();

        // Get company count
        $stmt = $db->query("
            SELECT COUNT(*) FROM empresas WHERE estado = 'activo'
        ");
        $companyCount = $stmt->fetchColumn();

        // Get total revenue (sum of valores profesional y empresa)
        $stmt = $db->query("
            SELECT
                COALESCE(SUM(valor_profesional), 0) as total_profesional,
                COALESCE(SUM(valor_empresa), 0) as total_empresa
            FROM prestaciones_pacientes
        ");
        $revenueStats = $stmt->fetch();

        // Get recent patients with their prestaciones
        $stmt = $db->query("
            SELECT p.*,
                   COUNT(DISTINCT pp.id) as total_prestaciones,
                   GROUP_CONCAT(DISTINCT tp.nombre SEPARATOR ', ') as prestaciones_nombres,
                   prof.nombre as profesional_nombre,
                   emp.nombre as empresa_nombre
            FROM pacientes p
            LEFT JOIN prestaciones_pacientes pp ON p.id = pp.id_paciente
            LEFT JOIN tipos_prestacion tp ON pp.id_tipo_prestacion = tp.id
            LEFT JOIN profesionales prof ON p.id_profesional = prof.id
            LEFT JOIN empresas emp ON p.id_empresa = emp.id
            WHERE p.estado = 'activo'
            GROUP BY p.id
            ORDER BY p.fecha_creacion DESC
            LIMIT 5
        ");
        $recentPatients = $stmt->fetchAll();

        // Get top professionals by number of prestaciones
        $stmt = $db->query("
            SELECT pr.nombre, pr.especialidad, COUNT(pp.id) as total_prestaciones
            FROM profesionales pr
            INNER JOIN prestaciones_pacientes pp ON pr.id = pp.id_profesional
            WHERE pr.estado = 'activo'
            GROUP BY pr.id
            ORDER BY total_prestaciones DESC
            LIMIT 5
        ");
        $topProfessionals = $stmt->fetchAll();

        // Get prestaciones by type distribution
        $stmt = $db->query("
            SELECT tp.nombre, COUNT(pp.id) as total
            FROM tipos_prestacion tp
            LEFT JOIN prestaciones_pacientes pp ON tp.id = pp.id_tipo_prestacion
            GROUP BY tp.id
            ORDER BY total DESC
        ");
        $prestacionesByType = $stmt->fetchAll();

        // Get unread notifications count
        $userId = $_SESSION['user_id'];
        $stmt = $db->prepare("
            SELECT COUNT(*) FROM notificaciones
            WHERE (id_usuario_destino = ? OR id_usuario_destino IS NULL)
            AND leida = 0
        ");
        $stmt->execute([$userId]);
        $unreadNotifications = $stmt->fetchColumn();

        // Get total files uploaded
        $stmt = $db->query("
            SELECT COUNT(*) FROM archivos_paciente
        ");
        $totalFiles = $stmt->fetchColumn();

        // Get new patients this month
        $stmt = $db->query("
            SELECT COUNT(*) FROM pacientes
            WHERE MONTH(fecha_creacion) = MONTH(CURDATE())
            AND YEAR(fecha_creacion) = YEAR(CURDATE())
        ");
        $newPatientsMonth = $stmt->fetchColumn();

        // Get expired prestaciones
        $stmt = $db->query("
            SELECT COUNT(*) FROM prestaciones_pacientes
            WHERE es_recurrente = 0
            AND fecha_fin IS NOT NULL
            AND fecha_fin < CURDATE()
        ");
        $expiredPrestaciones = $stmt->fetchColumn();

        // Get average prestaciones per patient
        $stmt = $db->query("
            SELECT AVG(prestaciones_count) as avg_prestaciones
            FROM (
                SELECT COUNT(pp.id) as prestaciones_count
                FROM pacientes p
                LEFT JOIN prestaciones_pacientes pp ON p.id = pp.id_paciente
                WHERE p.estado = 'activo'
                GROUP BY p.id
            ) as subquery
        ");
        $avgPrestaciones = $stmt->fetchColumn();

        // Get top companies by patient count
        $stmt = $db->query("
            SELECT e.nombre, COUNT(p.id) as total_pacientes
            FROM empresas e
            INNER JOIN pacientes p ON e.id = p.id_empresa
            WHERE e.estado = 'activo' AND p.estado = 'activo'
            GROUP BY e.id
            ORDER BY total_pacientes DESC
            LIMIT 5
        ");
        $topCompanies = $stmt->fetchAll();

        // Get patients by status for chart
        $stmt = $db->query("
            SELECT estado, COUNT(*) as total
            FROM pacientes
            GROUP BY estado
        ");
        $patientsByStatus = $stmt->fetchAll();

        // Get prestaciones expiring this week
        $stmt = $db->query("
            SELECT COUNT(*) FROM prestaciones_pacientes
            WHERE es_recurrente = 0
            AND fecha_fin IS NOT NULL
            AND DATEDIFF(fecha_fin, CURDATE()) BETWEEN 0 AND 7
        ");
        $expiringThisWeek = $stmt->fetchColumn();

        // Get monthly patient growth (last 6 months)
        $stmt = $db->query("
            SELECT
                DATE_FORMAT(fecha_creacion, '%Y-%m') as mes,
                COUNT(*) as total
            FROM pacientes
            WHERE fecha_creacion >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY mes
            ORDER BY mes ASC
        ");
        $monthlyGrowth = $stmt->fetchAll();

        return [
            'total_pacientes' => $patientStats['total_pacientes'] ?? 0,
            'pacientes_activos' => $patientStats['pacientes_activos'] ?? 0,
            'pacientes_pausados' => $patientStats['pacientes_pausados'] ?? 0,
            'pacientes_finalizados' => $patientStats['pacientes_finalizados'] ?? 0,
            'total_prestaciones' => $prestacionStats['total_prestaciones'] ?? 0,
            'prestaciones_recurrentes' => $prestacionStats['prestaciones_recurrentes'] ?? 0,
            'prestaciones_temporales' => $prestacionStats['prestaciones_temporales'] ?? 0,
            'proximos_vencer' => $expiringCount ?? 0,
            'total_profesionales' => $professionalCount ?? 0,
            'total_empresas' => $companyCount ?? 0,
            'total_profesional' => $revenueStats['total_profesional'] ?? 0,
            'total_empresa' => $revenueStats['total_empresa'] ?? 0,
            'pacientes_recientes' => $recentPatients ?? [],
            'top_profesionales' => $topProfessionals ?? [],
            'prestaciones_por_tipo' => $prestacionesByType ?? [],
            'notificaciones_pendientes' => $unreadNotifications ?? 0,
            'total_archivos' => $totalFiles ?? 0,
            'nuevos_pacientes_mes' => $newPatientsMonth ?? 0,
            'prestaciones_vencidas' => $expiredPrestaciones ?? 0,
            'promedio_prestaciones' => round($avgPrestaciones ?? 0, 1),
            'top_empresas' => $topCompanies ?? [],
            'pacientes_por_estado' => $patientsByStatus ?? [],
            'vencen_esta_semana' => $expiringThisWeek ?? 0,
            'crecimiento_mensual' => $monthlyGrowth ?? []
        ];
    }
}

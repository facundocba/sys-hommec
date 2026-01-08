<?php
$title = 'Dashboard';
include __DIR__ . '/../layouts/header.php';
?>

<style>
    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(136, 219, 242, 0.25);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg,
            var(--stormy-cyan) 0%,
            var(--stormy-light) 50%,
            var(--stormy-cyan) 100%
        );
    }

    .page-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .page-header-left {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .page-header-icon {
        width: 72px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        box-shadow: 0 8px 24px rgba(106, 137, 167, 0.35);
        flex-shrink: 0;
    }

    .page-header-icon i {
        font-size: 2rem;
        color: white;
    }

    .page-header-text h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.02em;
    }

    .page-header-text p {
        color: var(--stormy-blue);
        margin: 0;
        font-size: 0.9375rem;
        font-weight: 500;
    }

    .page-header-right {
        display: flex;
        gap: 1rem;
    }

    .header-stat-pill {
        background: rgba(136, 219, 242, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 12px;
        padding: 0.75rem 1.25rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
    }

    .header-stat-pill-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--stormy-dark);
        line-height: 1;
    }

    .header-stat-pill-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--stormy-blue);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Main Grid Layout */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 2rem;
        margin-bottom: 2rem;
    }

    /* Metrics Cards */
    .metrics-section {
        grid-column: span 8;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .metric-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(136, 219, 242, 0.3);
        border-radius: 20px;
        padding: 2rem;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.12),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--stormy-cyan), var(--stormy-blue));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .metric-card:hover {
        transform: translateY(-8px);
        box-shadow:
            0 16px 48px rgba(56, 73, 89, 0.18),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        border-color: rgba(136, 219, 242, 0.5);
    }

    .metric-card:hover::before {
        opacity: 1;
    }

    .metric-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .metric-label {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--stormy-blue);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metric-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .metric-badge.success {
        background: rgba(46, 204, 113, 0.15);
        color: #27ae60;
    }

    .metric-badge.warning {
        background: rgba(243, 156, 18, 0.15);
        color: #d68910;
    }

    .metric-badge.info {
        background: rgba(136, 219, 242, 0.15);
        color: var(--stormy-blue);
    }

    .metric-value {
        font-size: 3rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
        margin-bottom: 0.75rem;
    }

    .metric-description {
        font-size: 0.9375rem;
        color: var(--stormy-blue);
        font-weight: 500;
    }

    /* Featured Card */
    .featured-card {
        grid-column: span 4;
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow:
            0 16px 48px rgba(106, 137, 167, 0.35),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .featured-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .featured-content {
        position: relative;
        z-index: 1;
    }

    .featured-label {
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
        margin-bottom: 1rem;
    }

    .featured-value {
        font-size: 2.75rem;
        font-weight: 900;
        line-height: 1;
        margin-bottom: 1rem;
    }

    .featured-description {
        font-size: 0.9375rem;
        opacity: 0.9;
        margin-bottom: 1.5rem;
    }

    .featured-breakdown {
        display: flex;
        gap: 1.5rem;
    }

    .featured-item {
        flex: 1;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .featured-item-label {
        font-size: 0.75rem;
        opacity: 0.8;
        margin-bottom: 0.5rem;
    }

    .featured-item-value {
        font-size: 1.25rem;
        font-weight: 700;
    }

    /* Charts Section */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(136, 219, 242, 0.3);
        border-radius: 20px;
        padding: 2rem;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.12),
            inset 0 1px 0 rgba(255, 255, 255, 1);
    }

    .chart-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(136, 219, 242, 0.15);
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--stormy-dark);
        margin-bottom: 0.25rem;
    }

    .chart-subtitle {
        font-size: 0.875rem;
        color: var(--stormy-blue);
    }

    /* Ranking Items */
    .ranking-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .ranking-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .ranking-name {
        font-size: 1rem;
        font-weight: 700;
        color: var(--stormy-dark);
    }

    .ranking-subtitle {
        font-size: 0.8125rem;
        color: var(--stormy-blue);
        margin-top: 0.25rem;
    }

    .ranking-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--stormy-cyan);
    }

    .ranking-bar-container {
        height: 12px;
        background: rgba(136, 219, 242, 0.1);
        border-radius: 12px;
        overflow: hidden;
        position: relative;
    }

    .ranking-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);
        border-radius: 12px;
        transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 0 16px rgba(136, 219, 242, 0.5);
    }

    /* Activity Feed */
    .activity-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(136, 219, 242, 0.3);
        border-radius: 20px;
        padding: 2rem;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.12),
            inset 0 1px 0 rgba(255, 255, 255, 1);
    }

    .activity-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(136, 219, 242, 0.15);
    }

    .activity-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--stormy-dark);
        margin-bottom: 0.25rem;
    }

    .activity-subtitle {
        font-size: 0.875rem;
        color: var(--stormy-blue);
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .activity-item {
        padding: 1.25rem;
        background: rgba(189, 221, 252, 0.08);
        border-radius: 12px;
        border-left: 3px solid var(--stormy-cyan);
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: rgba(136, 219, 242, 0.12);
        transform: translateX(8px);
        box-shadow: 0 4px 16px rgba(136, 219, 242, 0.15);
    }

    .activity-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .activity-item-title {
        font-size: 0.9375rem;
        font-weight: 700;
        color: var(--stormy-dark);
    }

    .activity-item-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .activity-item-badge.success {
        background: rgba(46, 204, 113, 0.15);
        color: #27ae60;
    }

    .activity-item-badge.info {
        background: rgba(136, 219, 242, 0.15);
        color: var(--stormy-blue);
    }

    .activity-item-badge.warning {
        background: rgba(243, 156, 18, 0.15);
        color: #d68910;
    }

    .activity-item-subtitle {
        font-size: 0.8125rem;
        color: var(--stormy-blue);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .metrics-section,
        .featured-card {
            grid-column: span 1;
        }

        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        /* Page Header Mobile */
        .page-header {
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
        }

        .page-header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.25rem;
        }

        .page-header-left {
            width: 100%;
            gap: 1rem;
        }

        .page-header-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
        }

        .page-header-icon i {
            font-size: 1.5rem;
        }

        .page-header-text h1 {
            font-size: 1.5rem;
            margin-bottom: 0.375rem;
        }

        .page-header-text p {
            font-size: 0.8125rem;
        }

        .page-header-right {
            width: 100%;
            flex-direction: column;
            gap: 0.75rem;
        }

        .header-stat-pill {
            width: 100%;
            flex-direction: row;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-radius: 12px;
        }

        .header-stat-pill-value {
            font-size: 1.75rem;
        }

        .header-stat-pill-label {
            font-size: 0.8125rem;
        }

        /* Dashboard Grid Mobile */
        .dashboard-grid {
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        /* Metrics Section Mobile */
        .metrics-section {
            grid-template-columns: 1fr;
        }

        .metric-card {
            padding: 1.5rem;
            border-radius: 16px;
        }

        .metric-header {
            margin-bottom: 1.25rem;
        }

        .metric-label {
            font-size: 0.75rem;
        }

        .metric-badge {
            padding: 0.375rem 0.75rem;
            font-size: 0.6875rem;
        }

        .metric-value {
            font-size: 2.5rem;
            margin-bottom: 0.625rem;
        }

        .metric-description {
            font-size: 0.875rem;
        }

        /* Featured Card Mobile */
        .featured-card {
            padding: 1.75rem;
            border-radius: 18px;
        }

        .featured-label {
            font-size: 0.8125rem;
            margin-bottom: 0.75rem;
        }

        .featured-value {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
        }

        .featured-description {
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .featured-breakdown {
            flex-direction: column;
            gap: 1rem;
        }

        .featured-item {
            padding: 1rem 1.25rem;
        }

        .featured-item-label {
            font-size: 0.75rem;
        }

        .featured-item-value {
            font-size: 1.125rem;
        }

        /* Charts Grid Mobile */
        .charts-grid {
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .chart-card {
            padding: 1.5rem;
            border-radius: 16px;
        }

        .chart-header {
            margin-bottom: 1.5rem;
            padding-bottom: 0.875rem;
        }

        .chart-title {
            font-size: 1.125rem;
        }

        .chart-subtitle {
            font-size: 0.8125rem;
        }

        /* Ranking Items Mobile */
        .ranking-list {
            gap: 1.25rem;
        }

        .ranking-header {
            margin-bottom: 0.625rem;
        }

        .ranking-name {
            font-size: 0.9375rem;
        }

        .ranking-subtitle {
            font-size: 0.75rem;
        }

        .ranking-value {
            font-size: 1.25rem;
        }

        .ranking-bar-container {
            height: 10px;
        }

        /* Activity Card Mobile */
        .activity-card {
            padding: 1.5rem;
            border-radius: 16px;
        }

        .activity-header {
            margin-bottom: 1.5rem;
            padding-bottom: 0.875rem;
        }

        .activity-title {
            font-size: 1.125rem;
        }

        .activity-subtitle {
            font-size: 0.8125rem;
        }

        .activity-list {
            gap: 0.875rem;
        }

        .activity-item {
            padding: 1rem;
            border-radius: 10px;
        }

        .activity-item:hover {
            transform: translateX(4px);
        }

        .activity-item-header {
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.625rem;
        }

        .activity-item-title {
            font-size: 0.875rem;
        }

        .activity-item-badge {
            padding: 0.25rem 0.625rem;
            font-size: 0.6875rem;
        }

        .activity-item-subtitle {
            font-size: 0.75rem;
        }
    }

    /* Extra Small Devices */
    @media (max-width: 480px) {
        .page-header {
            padding: 1.25rem;
        }

        .page-header-icon {
            width: 48px;
            height: 48px;
        }

        .page-header-icon i {
            font-size: 1.25rem;
        }

        .page-header-text h1 {
            font-size: 1.25rem;
        }

        .metric-value {
            font-size: 2rem;
        }

        .featured-value {
            font-size: 2rem;
        }

        .metric-card,
        .chart-card,
        .activity-card {
            padding: 1.25rem;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-header-left">
            <div class="page-header-icon">
                <i class="bi bi-speedometer2"></i>
            </div>
            <div class="page-header-text">
                <h1>Dashboard</h1>
                <p>Bienvenido, <?= htmlspecialchars(getCurrentUser()['nombre']) ?> • <?= date('d M Y') ?></p>
            </div>
        </div>
        <div class="page-header-right">
            <div class="header-stat-pill">
                <span class="header-stat-pill-value"><?= $total_profesionales ?></span>
                <span class="header-stat-pill-label">Profesionales</span>
            </div>
            <div class="header-stat-pill">
                <span class="header-stat-pill-value"><?= $total_empresas ?></span>
                <span class="header-stat-pill-label">Empresas</span>
            </div>
            <div class="header-stat-pill">
                <span class="header-stat-pill-value"><?= $total_archivos ?></span>
                <span class="header-stat-pill-label">Archivos</span>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Metrics Section -->
    <div class="metrics-section">
        <!-- Pacientes -->
        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-label">Pacientes</span>
                <span class="metric-badge info">
                    <i class="bi bi-arrow-up"></i>
                    <?= $nuevos_pacientes_mes ?> este mes
                </span>
            </div>
            <div class="metric-value"><?= $total_pacientes ?></div>
            <div class="metric-description"><?= $pacientes_activos ?> activos • <?= $pacientes_pausados ?> pausados</div>
        </div>

        <!-- Prestaciones -->
        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-label">Prestaciones</span>
                <span class="metric-badge success">
                    <i class="bi bi-check-circle"></i>
                    <?= $promedio_prestaciones ?> promedio
                </span>
            </div>
            <div class="metric-value"><?= $total_prestaciones ?></div>
            <div class="metric-description"><?= $prestaciones_recurrentes ?> recurrentes • <?= $prestaciones_temporales ?> temporales</div>
        </div>

        <!-- Próximos a Vencer -->
        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-label">Próximos a vencer</span>
                <span class="metric-badge warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    Urgente
                </span>
            </div>
            <div class="metric-value"><?= $proximos_vencer ?></div>
            <div class="metric-description"><?= $vencen_esta_semana ?> vencen esta semana</div>
        </div>

        <!-- Vencidas -->
        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-label">Vencidas</span>
                <span class="metric-badge warning">
                    <i class="bi bi-calendar-x"></i>
                    Atención
                </span>
            </div>
            <div class="metric-value"><?= $prestaciones_vencidas ?></div>
            <div class="metric-description">Prestaciones que requieren renovación</div>
        </div>
    </div>

    <!-- Featured Value Card -->
    <div class="featured-card">
        <div class="featured-content">
            <div class="featured-label">Valor Total del Sistema</div>
            <div class="featured-value"><?= formatCurrency($total_profesional + $total_empresa) ?></div>
            <div class="featured-description">Valor combinado de prestaciones activas</div>
            <div class="featured-breakdown">
                <div class="featured-item">
                    <div class="featured-item-label">Profesionales</div>
                    <div class="featured-item-value"><?= formatCurrency($total_profesional) ?></div>
                </div>
                <div class="featured-item">
                    <div class="featured-item-label">Empresas</div>
                    <div class="featured-item-value"><?= formatCurrency($total_empresa) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="charts-grid">
    <!-- Top Profesionales -->
    <div class="chart-card">
        <div class="chart-header">
            <h2 class="chart-title">Top Profesionales</h2>
            <p class="chart-subtitle">Por número de prestaciones</p>
        </div>

        <?php if (!empty($top_profesionales)): ?>
            <div class="ranking-list">
                <?php
                $maxPrestaciones = max(array_column($top_profesionales, 'total_prestaciones'));
                foreach ($top_profesionales as $prof):
                    $percentage = $maxPrestaciones > 0 ? ($prof['total_prestaciones'] / $maxPrestaciones) * 100 : 0;
                ?>
                    <div class="ranking-item">
                        <div class="ranking-header">
                            <div>
                                <div class="ranking-name"><?= htmlspecialchars($prof['nombre']) ?></div>
                                <div class="ranking-subtitle"><?= htmlspecialchars($prof['especialidad'] ?? 'Sin especialidad') ?></div>
                            </div>
                            <div class="ranking-value"><?= $prof['total_prestaciones'] ?></div>
                        </div>
                        <div class="ranking-bar-container">
                            <div class="ranking-bar" style="width: <?= $percentage ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Top Empresas -->
    <div class="chart-card">
        <div class="chart-header">
            <h2 class="chart-title">Top Empresas</h2>
            <p class="chart-subtitle">Por cantidad de pacientes</p>
        </div>

        <?php if (!empty($top_empresas)): ?>
            <div class="ranking-list">
                <?php
                $maxEmpresas = max(array_column($top_empresas, 'total_pacientes'));
                foreach ($top_empresas as $empresa):
                    $percentage = $maxEmpresas > 0 ? ($empresa['total_pacientes'] / $maxEmpresas) * 100 : 0;
                ?>
                    <div class="ranking-item">
                        <div class="ranking-header">
                            <div>
                                <div class="ranking-name"><?= htmlspecialchars($empresa['nombre']) ?></div>
                                <div class="ranking-subtitle">Pacientes asignados</div>
                            </div>
                            <div class="ranking-value"><?= $empresa['total_pacientes'] ?></div>
                        </div>
                        <div class="ranking-bar-container">
                            <div class="ranking-bar" style="width: <?= $percentage ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Activity Feed -->
<div class="activity-card">
    <div class="activity-header">
        <h2 class="activity-title">Actividad Reciente</h2>
        <p class="activity-subtitle">Últimos pacientes registrados</p>
    </div>

    <?php if (!empty($pacientes_recientes)): ?>
        <div class="activity-list">
            <?php foreach ($pacientes_recientes as $paciente): ?>
                <a href="<?= baseUrl('patients/view/' . $paciente['id']) ?>" style="text-decoration: none; color: inherit;">
                    <div class="activity-item">
                        <div class="activity-item-header">
                            <div class="activity-item-title"><?= htmlspecialchars($paciente['nombre_completo']) ?></div>
                            <?php if ($paciente['paciente_recurrente']): ?>
                                <span class="activity-item-badge info">
                                    <i class="bi bi-arrow-repeat"></i> Recurrente
                                </span>
                            <?php elseif ($paciente['total_prestaciones'] > 0): ?>
                                <span class="activity-item-badge success">
                                    <i class="bi bi-check-circle"></i> Activo
                                </span>
                            <?php else: ?>
                                <span class="activity-item-badge warning">
                                    <i class="bi bi-clock"></i> Nuevo
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="activity-item-subtitle">
                            <?php if ($paciente['profesional_nombre']): ?>
                                <?= htmlspecialchars($paciente['profesional_nombre']) ?>
                            <?php else: ?>
                                Sin profesional asignado
                            <?php endif; ?>
                            <?php if ($paciente['total_prestaciones'] > 0): ?>
                                • <?= $paciente['total_prestaciones'] ?> prestación(es)
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

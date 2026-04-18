<?php
include __DIR__ . '/../layouts/header.php';

$meses = [
    '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
    '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
    '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
    '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
];

function formatPeriodoLabel($p, $meses) {
    $parts = explode('-', $p);
    return ($meses[$parts[1]] ?? $parts[1]) . ' ' . $parts[0];
}

$periodoDisplay = formatPeriodoLabel($periodo, $meses);
?>

<style>
    .page-header {
        background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(250,252,255,0.95) 100%);
        border: 1px solid rgba(136,219,242,0.2);
        border-radius: 24px;
        padding: 2.5rem 3rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 60px rgba(56,73,89,0.08), inset 0 1px 0 rgba(255,255,255,1);
    }
    .page-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .page-header h1 {
        font-size: 2.25rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 60%, var(--stormy-cyan) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    .btn-cargar {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        font-size: 0.9375rem;
    }
    .btn-cargar:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(16,185,129,0.3); }

    .filters-card {
        background: rgba(255,255,255,0.98);
        border: 1px solid rgba(136,219,242,0.2);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(56,73,89,0.06);
    }
    .filters-row {
        display: grid;
        grid-template-columns: 200px 1fr 200px auto;
        gap: 1.5rem;
        align-items: end;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--stormy-dark);
        font-size: 0.875rem;
        text-transform: uppercase;
    }
    .form-control {
        width: 100%;
        padding: 0.875rem 1.25rem;
        border: 2px solid rgba(136,219,242,0.2);
        border-radius: 12px;
        font-size: 0.9375rem;
        transition: all 0.3s ease;
        font-family: inherit;
    }
    .form-control:focus { outline: none; border-color: var(--stormy-cyan); box-shadow: 0 0 0 4px rgba(136,219,242,0.12); }
    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%236A89A7' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 3rem;
    }
    .btn-filter {
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-filter:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(106,137,167,0.3); }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(255,255,255,0.95) 100%);
        border: 1px solid rgba(136,219,242,0.2);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 24px rgba(56,73,89,0.06);
        position: relative;
        overflow: hidden;
    }
    .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--stormy-cyan), var(--stormy-blue)); }
    .stat-label { font-size: 0.875rem; font-weight: 600; color: var(--stormy-blue); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.75rem; }
    .stat-value { font-size: 2rem; font-weight: 800; background: linear-gradient(135deg, var(--stormy-dark), var(--stormy-blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    /* Tabs */
    .tabs-container {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }
    .tab-btn {
        padding: 0.75rem 1.75rem;
        border: 2px solid rgba(136,219,242,0.2);
        border-radius: 12px;
        background: rgba(255,255,255,0.9);
        color: var(--stormy-blue);
        font-weight: 700;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
    }
    .tab-btn:hover { border-color: var(--stormy-cyan); background: rgba(136,219,242,0.08); }
    .tab-btn.active {
        background: linear-gradient(135deg, var(--stormy-blue), var(--stormy-cyan));
        color: white;
        border-color: var(--stormy-cyan);
    }
    .tab-content { display: none; }
    .tab-content.active { display: block; }

    /* Tabla de liquidaciones */
    .liq-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: rgba(255,255,255,0.98);
        border: 1px solid rgba(136,219,242,0.2);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(56,73,89,0.06);
    }
    .liq-table thead th {
        background: linear-gradient(135deg, rgba(136,219,242,0.08) 0%, rgba(189,221,252,0.05) 100%);
        padding: 1.25rem 1.5rem;
        font-weight: 700;
        color: var(--stormy-dark);
        text-transform: uppercase;
        font-size: 0.8125rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(136,219,242,0.2);
        text-align: left;
    }
    .liq-table tbody td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(136,219,242,0.1);
        font-size: 0.9375rem;
        color: var(--stormy-dark);
    }
    .liq-table tbody tr:last-child td { border-bottom: none; }
    .liq-table tbody tr:hover { background: rgba(136,219,242,0.04); }
    .badge-diff { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; }
    .badge-diff.positive { background: rgba(16,185,129,0.1); color: #059669; }
    .badge-diff.negative { background: rgba(239,68,68,0.1); color: #dc2626; }
    .badge-diff.neutral { background: rgba(136,219,242,0.1); color: var(--stormy-blue); }
    .valor-prof { color: #10b981; font-weight: 700; }
    .valor-emp { color: #6366f1; font-weight: 700; }
    .btn-detalle {
        background: rgba(136,219,242,0.1);
        color: var(--stormy-blue);
        border: 1px solid rgba(136,219,242,0.3);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8125rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-detalle:hover { background: rgba(136,219,242,0.2); border-color: var(--stormy-cyan); }

    /* Cards de proyección teórica */
    .professionals-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .professional-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(250,252,255,0.95) 100%);
        border: 2px solid rgba(136,219,242,0.25);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(56,73,89,0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .professional-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px; background: linear-gradient(90deg, var(--stormy-cyan) 0%, var(--stormy-blue) 50%, var(--stormy-cyan) 100%); }
    .professional-card:hover { transform: translateY(-8px); box-shadow: 0 20px 60px rgba(136,219,242,0.25); border-color: var(--stormy-cyan); }
    .prof-card-header { padding: 2rem 2rem 1.5rem; border-bottom: 2px solid rgba(136,219,242,0.15); background: linear-gradient(135deg, rgba(136,219,242,0.05) 0%, transparent 100%); }
    .prof-name { font-size: 1.5rem; font-weight: 800; background: linear-gradient(135deg, var(--stormy-dark), var(--stormy-blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin: 0 0 0.5rem 0; }
    .prof-specialty { font-size: 0.9375rem; color: var(--stormy-blue); font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .prof-specialty::before { content: ''; width: 8px; height: 8px; background: var(--stormy-cyan); border-radius: 50%; box-shadow: 0 0 8px rgba(136,219,242,0.6); }
    .prof-meta { display: flex; gap: 1.5rem; margin-top: 1rem; }
    .prof-meta-item { display: flex; flex-direction: column; gap: 0.25rem; }
    .prof-meta-label { font-size: 0.75rem; font-weight: 600; color: var(--stormy-blue); text-transform: uppercase; }
    .prof-meta-value { font-size: 1.25rem; font-weight: 800; color: var(--stormy-dark); }
    .prof-card-body { padding: 1.5rem 2rem 2rem; }
    .periods-compact { display: flex; flex-direction: column; gap: 1rem; }
    .period-row { background: linear-gradient(135deg, rgba(136,219,242,0.05) 0%, rgba(189,221,252,0.03) 100%); border: 1px solid rgba(136,219,242,0.2); border-radius: 16px; padding: 1.25rem; transition: all 0.3s ease; }
    .period-row:hover { background: linear-gradient(135deg, rgba(136,219,242,0.1) 0%, rgba(189,221,252,0.05) 100%); border-color: var(--stormy-cyan); transform: translateX(4px); }
    .period-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid rgba(136,219,242,0.2); }
    .period-label { font-size: 0.875rem; font-weight: 700; color: var(--stormy-dark); text-transform: uppercase; display: flex; align-items: center; gap: 0.5rem; }
    .period-label svg { width: 16px; height: 16px; stroke: var(--stormy-cyan); }
    .period-total { font-size: 1.25rem; font-weight: 800; background: linear-gradient(135deg, var(--stormy-dark), var(--stormy-blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .period-breakdown { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .breakdown-item { display: flex; flex-direction: column; gap: 0.375rem; }
    .breakdown-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; display: flex; align-items: center; gap: 0.375rem; }
    .breakdown-label.prof { color: #10b981; }
    .breakdown-label.emp { color: #6366f1; }
    .breakdown-label::before { content: ''; width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
    .breakdown-label.prof::before { background: #10b981; box-shadow: 0 0 8px rgba(16,185,129,0.4); }
    .breakdown-label.emp::before { background: #6366f1; box-shadow: 0 0 8px rgba(99,102,241,0.4); }
    .breakdown-value { font-size: 1.125rem; font-weight: 700; }
    .breakdown-value.prof { color: #10b981; }
    .breakdown-value.emp { color: #6366f1; }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--stormy-blue);
        background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(250,252,255,0.95) 100%);
        border: 2px dashed rgba(136,219,242,0.4);
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(56,73,89,0.06);
    }
    .empty-state h3 {
        font-size: 1.375rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-dark), var(--stormy-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 0.5rem 0;
    }
    .empty-state p { margin: 0 0 1.5rem 0; font-size: 0.9375rem; }
    .periodo-badge { display: inline-block; background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue)); color: white; padding: 0.375rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.9375rem; }

    .pagination-wrapper { display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2rem; background: linear-gradient(135deg, rgba(255,255,255,0.98) 0%, rgba(250,252,255,0.95) 100%); border: 1px solid rgba(136,219,242,0.2); border-radius: 20px; margin-top: 1.5rem; box-shadow: 0 8px 32px rgba(56,73,89,0.08), inset 0 1px 0 rgba(255,255,255,1); }
    .pagination-info { color: var(--stormy-blue); font-size: 0.9375rem; font-weight: 600; }
    .pagination { display: flex; gap: 0.5rem; align-items: center; list-style: none; margin: 0; padding: 0; }
    .pagination-btn { display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 1rem; background: rgba(255,255,255,0.9); border: 2px solid rgba(136,219,242,0.2); border-radius: 10px; color: var(--stormy-dark); font-weight: 600; font-size: 0.9375rem; cursor: pointer; transition: all 0.3s cubic-bezier(0.4,0,0.2,1); text-decoration: none; }
    .pagination-btn:hover:not(.disabled):not(.active) { background: rgba(136,219,242,0.1); border-color: var(--stormy-cyan); transform: translateY(-2px); }
    .pagination-btn.active { background: linear-gradient(135deg, var(--stormy-blue), var(--stormy-cyan)); border-color: var(--stormy-cyan); color: var(--white); }
    .pagination-btn.disabled { opacity: 0.4; cursor: not-allowed; pointer-events: none; }
    .pagination-btn svg { width: 18px; height: 18px; stroke: currentColor; }
    .pagination-ellipsis { display: inline-flex; align-items: center; padding: 0 0.5rem; color: var(--stormy-blue); font-weight: 600; }

    @media (max-width: 768px) {
        .page-header-content { flex-direction: column; }
        .filters-row { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .professionals-grid { grid-template-columns: 1fr; }
        .pagination-wrapper { flex-direction: column; gap: 1rem; }
        .tabs-container { flex-wrap: wrap; }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue)); border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(106,137,167,0.35);">
                <svg style="width: 28px; height: 28px; stroke: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="20" x2="12" y2="10"/>
                    <line x1="18" y1="20" x2="18" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="16"/>
                </svg>
            </div>
            <div>
                <h1>Reportes Financieros</h1>
                <p style="margin: 0.5rem 0 0 0; color: var(--stormy-blue);">Liquidaciones y proyecciones por profesional</p>
            </div>
        </div>
        <a href="<?= baseUrl('professionals/cargar?periodo=' . htmlspecialchars($periodo)) ?>" class="btn-cargar">
            <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Cargar Sesiones
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="filters-card">
    <form method="GET" action="<?= baseUrl('professionals/reports') ?>">
        <div class="filters-row">
            <div class="form-group">
                <label>Período</label>
                <select name="periodo" class="form-control form-select">
                    <?php
                    $opciones = [];
                    for ($i = 0; $i < 12; $i++) {
                        $m = date('Y-m', strtotime("-$i months"));
                        $opciones[$m] = formatPeriodoLabel($m, $meses);
                    }
                    foreach ($periodosDisponibles as $p) {
                        if (!isset($opciones[$p])) {
                            $opciones[$p] = formatPeriodoLabel($p, $meses);
                        }
                    }
                    krsort($opciones);
                    foreach ($opciones as $val => $label): ?>
                        <option value="<?= $val ?>" <?= $val === $periodo ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Buscar Profesional</label>
                <input type="text" name="search" class="form-control" placeholder="Nombre del profesional..." value="<?= htmlspecialchars($filters['search']) ?>">
            </div>

            <div class="form-group">
                <label>Profesional</label>
                <select name="professional" class="form-control form-select">
                    <option value="">Todos</option>
                    <?php foreach ($professionals as $prof): ?>
                        <option value="<?= $prof['id'] ?>" <?= $filters['professional'] == $prof['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($prof['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-filter">Filtrar</button>
            </div>
        </div>
    </form>
</div>

<!-- Tabs: Liquidación Real vs Proyección Teórica -->
<div class="tabs-container">
    <button class="tab-btn <?= $tieneLiquidacion ? 'active' : '' ?>" onclick="switchTab('liquidacion')">
        Liquidación Real — <?= $periodoDisplay ?>
    </button>
    <button class="tab-btn <?= !$tieneLiquidacion ? 'active' : '' ?>" onclick="switchTab('proyeccion')">
        Proyección Teórica
    </button>
</div>

<!-- TAB: Liquidación Real -->
<div id="tab-liquidacion" class="tab-content <?= $tieneLiquidacion ? 'active' : '' ?>">
    <?php if (!$tieneLiquidacion): ?>
        <div class="empty-state">
            <svg style="width: 64px; height: 64px; stroke: var(--stormy-cyan); margin-bottom: 1rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                <line x1="1" y1="10" x2="23" y2="10"/>
            </svg>
            <h3>No hay liquidación cargada para <?= $periodoDisplay ?></h3>
            <p>Cargue las sesiones realizadas para generar la liquidación de este período.</p>
            <a href="<?= baseUrl('professionals/cargar?periodo=' . htmlspecialchars($periodo)) ?>" class="btn-cargar" style="margin-top: 1rem;">
                Cargar Sesiones del Período
            </a>
        </div>
    <?php else: ?>
        <!-- Stats de liquidación -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Período</div>
                <div style="margin-top: 0.5rem;"><span class="periodo-badge"><?= $periodoDisplay ?></span></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Profesionales</div>
                <div class="stat-value"><?= $totalesLiquidacion['total_profesionales'] ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Sesiones Realizadas</div>
                <div class="stat-value"><?= number_format($totalesLiquidacion['total_sesiones_realizadas']) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Profesional</div>
                <div class="stat-value valor-prof">$<?= number_format($totalesLiquidacion['total_profesional'], 2) ?></div>
            </div>
            <?php if (!isCoordinator()): ?>
            <div class="stat-card">
                <div class="stat-label">Total Empresa</div>
                <div class="stat-value valor-emp">$<?= number_format($totalesLiquidacion['total_empresa'], 2) ?></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tabla de resumen -->
        <table class="liq-table">
            <thead>
                <tr>
                    <th>Profesional</th>
                    <th>Especialidad</th>
                    <th>Prestaciones</th>
                    <th>Ses. Esperadas</th>
                    <th>Ses. Realizadas</th>
                    <th>Diferencia</th>
                    <th>Total Profesional</th>
                    <?php if (!isCoordinator()): ?>
                    <th>Total Empresa</th>
                    <?php endif; ?>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resumenLiquidacion as $row): ?>
                <tr>
                    <td style="font-weight: 700;"><?= htmlspecialchars($row['profesional_nombre']) ?></td>
                    <td><?= htmlspecialchars($row['especialidad'] ?? '-') ?></td>
                    <td><?= $row['total_prestaciones'] ?></td>
                    <td style="text-align: center;"><?= number_format($row['total_sesiones_esperadas'], 1) ?></td>
                    <td style="text-align: center; font-weight: 700;"><?= number_format($row['total_sesiones_realizadas'], 1) ?></td>
                    <td style="text-align: center;">
                        <?php
                        $diff = $row['total_sesiones_realizadas'] - $row['total_sesiones_esperadas'];
                        $class = $diff > 0 ? 'positive' : ($diff < 0 ? 'negative' : 'neutral');
                        $sign = $diff > 0 ? '+' : '';
                        ?>
                        <span class="badge-diff <?= $class ?>"><?= $sign . number_format($diff, 1) ?></span>
                    </td>
                    <td class="valor-prof">$<?= number_format($row['total_profesional'], 2) ?></td>
                    <?php if (!isCoordinator()): ?>
                    <td class="valor-emp">$<?= number_format($row['total_empresa'], 2) ?></td>
                    <?php endif; ?>
                    <td>
                        <a href="<?= baseUrl('professionals/detalle-liquidacion/' . $row['profesional_id'] . '?periodo=' . htmlspecialchars($periodo)) ?>" class="btn-detalle">
                            Ver Detalle
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- TAB: Proyección Teórica -->
<div id="tab-proyeccion" class="tab-content <?= !$tieneLiquidacion ? 'active' : '' ?>">
    <!-- Stats teóricos -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Profesionales</div>
            <div class="stat-value"><?= $reportData['totals']['total_profesionales'] ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Pacientes</div>
            <div class="stat-value"><?= $reportData['totals']['total_pacientes'] ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Sesiones/Mes</div>
            <div class="stat-value"><?= number_format($reportData['totals']['total_sesiones']) ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Ingreso Profesional (30d)</div>
            <div class="stat-value" style="color: #10b981;">$<?= number_format($reportData['totals']['total_valor_profesional'], 2) ?></div>
        </div>
        <?php if (!isCoordinator()): ?>
        <div class="stat-card">
            <div class="stat-label">Ingreso Empresa (30d)</div>
            <div class="stat-value" style="color: #6366f1;">$<?= number_format($reportData['totals']['total_valor_empresa'], 2) ?></div>
        </div>
        <?php endif; ?>
    </div>

    <?php if (empty($reportData['professionals'])): ?>
        <div class="empty-state">
            <h3>No se encontraron datos</h3>
            <p>Intente ajustar los filtros de búsqueda</p>
        </div>
    <?php else: ?>
        <div class="professionals-grid">
        <?php foreach ($reportData['professionals'] as $prof): ?>
            <div class="professional-card">
                <div class="prof-card-header">
                    <h3 class="prof-name"><?= htmlspecialchars($prof['profesional_nombre']) ?></h3>
                    <div class="prof-specialty"><?= htmlspecialchars($prof['especialidad'] ?? 'Sin especialidad') ?></div>
                    <div class="prof-meta">
                        <div class="prof-meta-item">
                            <span class="prof-meta-label">Pacientes</span>
                            <span class="prof-meta-value"><?= $prof['total_pacientes'] ?></span>
                        </div>
                        <div class="prof-meta-item">
                            <span class="prof-meta-label">Sesiones/Mes</span>
                            <span class="prof-meta-value"><?= $prof['total_sesiones'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="prof-card-body">
                    <div class="periods-compact">
                        <?php
                        $periods = [
                            ['label' => '30 Días', 'prof' => $prof['acumulado_profesional_30'], 'emp' => $prof['acumulado_empresa_30']],
                            ['label' => '60 Días', 'prof' => $prof['acumulado_profesional_60'], 'emp' => $prof['acumulado_empresa_60']],
                            ['label' => '90 Días', 'prof' => $prof['acumulado_profesional_90'], 'emp' => $prof['acumulado_empresa_90']],
                        ];
                        foreach ($periods as $p): ?>
                        <div class="period-row">
                            <div class="period-header">
                                <span class="period-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    <?= $p['label'] ?>
                                </span>
                                <span class="period-total">$<?= number_format(isCoordinator() ? $p['prof'] : $p['prof'] + $p['emp'], 2) ?></span>
                            </div>
                            <div class="period-breakdown">
                                <div class="breakdown-item">
                                    <span class="breakdown-label prof">Profesional</span>
                                    <span class="breakdown-value prof">$<?= number_format($p['prof'], 2) ?></span>
                                </div>
                                <?php if (!isCoordinator()): ?>
                                <div class="breakdown-item">
                                    <span class="breakdown-label emp">Empresa</span>
                                    <span class="breakdown-value emp">$<?= number_format($p['emp'], 2) ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

        <?php if ($pagination['total_pages'] > 1): ?>
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Mostrando <?= $pagination['offset'] + 1 ?> - <?= min($pagination['offset'] + $pagination['items_per_page'], $pagination['total_items']) ?> de <?= $pagination['total_items'] ?> profesionales
            </div>
            <ul class="pagination">
                <li>
                    <?php if ($pagination['current_page'] > 1): ?>
                        <a href="<?= baseUrl('professionals/reports?' . http_build_query(array_merge(['periodo' => $periodo], $filters, ['page' => $pagination['current_page'] - 1]))) ?>" class="pagination-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                        </a>
                    <?php else: ?>
                        <span class="pagination-btn disabled"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></span>
                    <?php endif; ?>
                </li>
                <?php
                $range = 2;
                $start = max(1, $pagination['current_page'] - $range);
                $end = min($pagination['total_pages'], $pagination['current_page'] + $range);
                if ($start > 1): ?>
                    <li><a href="<?= baseUrl('professionals/reports?' . http_build_query(array_merge(['periodo' => $periodo], $filters, ['page' => 1]))) ?>" class="pagination-btn">1</a></li>
                    <?php if ($start > 2): ?><li class="pagination-ellipsis">...</li><?php endif;
                endif;
                for ($i = $start; $i <= $end; $i++): ?>
                    <li>
                        <?php if ($i == $pagination['current_page']): ?>
                            <span class="pagination-btn active"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= baseUrl('professionals/reports?' . http_build_query(array_merge(['periodo' => $periodo], $filters, ['page' => $i]))) ?>" class="pagination-btn"><?= $i ?></a>
                        <?php endif; ?>
                    </li>
                <?php endfor;
                if ($end < $pagination['total_pages']): ?>
                    <?php if ($end < $pagination['total_pages'] - 1): ?><li class="pagination-ellipsis">...</li><?php endif; ?>
                    <li><a href="<?= baseUrl('professionals/reports?' . http_build_query(array_merge(['periodo' => $periodo], $filters, ['page' => $pagination['total_pages']]))) ?>" class="pagination-btn"><?= $pagination['total_pages'] ?></a></li>
                <?php endif; ?>
                <li>
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                        <a href="<?= baseUrl('professionals/reports?' . http_build_query(array_merge(['periodo' => $periodo], $filters, ['page' => $pagination['current_page'] + 1]))) ?>" class="pagination-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                    <?php else: ?>
                        <span class="pagination-btn disabled"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

    document.getElementById('tab-' + tab).classList.add('active');
    event.target.classList.add('active');
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<?php
include __DIR__ . '/../layouts/header.php';

$meses = [
    '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
    '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
    '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
    '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
];
$parts = explode('-', $periodo);
$periodoDisplay = ($meses[$parts[1]] ?? $parts[1]) . ' ' . $parts[0];

$totalSesEsperadas = 0;
$totalSesRealizadas = 0;
$totalProf = 0;
$totalEmp = 0;
foreach ($detalle as $row) {
    $totalSesEsperadas += $row['sesiones_esperadas'];
    $totalSesRealizadas += $row['sesiones_realizadas'];
    $totalProf += $row['total_profesional'];
    $totalEmp += $row['total_empresa'];
}
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
    .page-header h1 {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 60%, var(--stormy-cyan) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
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
        padding: 1.5rem;
        box-shadow: 0 8px 24px rgba(56,73,89,0.06);
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--stormy-cyan), var(--stormy-blue));
    }
    .stat-label { font-size: 0.8125rem; font-weight: 600; color: var(--stormy-blue); text-transform: uppercase; margin-bottom: 0.5rem; }
    .stat-value { font-size: 1.75rem; font-weight: 800; color: var(--stormy-dark); }
    .detalle-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: rgba(255,255,255,0.98);
        border: 1px solid rgba(136,219,242,0.2);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(56,73,89,0.06);
    }
    .detalle-table thead th {
        background: linear-gradient(135deg, rgba(136,219,242,0.08) 0%, rgba(189,221,252,0.05) 100%);
        padding: 1.25rem 1.5rem;
        font-weight: 700;
        color: var(--stormy-dark);
        text-transform: uppercase;
        font-size: 0.8125rem;
        border-bottom: 2px solid rgba(136,219,242,0.2);
        text-align: left;
    }
    .detalle-table tbody td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(136,219,242,0.1);
        font-size: 0.9375rem;
    }
    .detalle-table tbody tr:last-child td { border-bottom: none; }
    .detalle-table tbody tr:hover { background: rgba(136,219,242,0.04); }
    .detalle-table tfoot td {
        padding: 1.25rem 1.5rem;
        font-weight: 800;
        font-size: 1rem;
        border-top: 2px solid rgba(136,219,242,0.2);
        background: rgba(136,219,242,0.03);
    }
    .badge-diff { display: inline-block; padding: 0.25rem 0.75rem; border-radius: 8px; font-size: 0.8125rem; font-weight: 600; }
    .badge-diff.positive { background: rgba(16,185,129,0.1); color: #059669; }
    .badge-diff.negative { background: rgba(239,68,68,0.1); color: #dc2626; }
    .badge-diff.neutral { background: rgba(136,219,242,0.1); color: var(--stormy-blue); }
    .valor-prof { color: #10b981; font-weight: 700; }
    .valor-emp { color: #6366f1; font-weight: 700; }
    .btn-volver {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(136,219,242,0.1);
        color: var(--stormy-blue);
        border: 2px solid rgba(136,219,242,0.3);
        padding: 0.875rem 2rem;
        border-radius: 14px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.9375rem;
        margin-top: 2rem;
    }
    .btn-volver:hover { background: rgba(136,219,242,0.2); border-color: var(--stormy-cyan); }
    .obs-text { font-size: 0.8125rem; color: var(--stormy-blue); font-style: italic; }
</style>

<div class="page-header">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue)); border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(106,137,167,0.35);">
            <svg style="width: 28px; height: 28px; stroke: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="8.5" cy="7" r="4"/>
                <polyline points="17 11 19 13 23 9"/>
            </svg>
        </div>
        <div>
            <h1><?= htmlspecialchars($professional['nombre']) ?></h1>
            <p style="margin: 0.5rem 0 0 0; color: var(--stormy-blue);">
                Liquidación de <strong><?= $periodoDisplay ?></strong>
                <?php if (!empty($professional['especialidad'])): ?>
                    &mdash; <?= htmlspecialchars($professional['especialidad']) ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Prestaciones</div>
        <div class="stat-value"><?= count($detalle) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ses. Esperadas</div>
        <div class="stat-value"><?= number_format($totalSesEsperadas, 1) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ses. Realizadas</div>
        <div class="stat-value"><?= number_format($totalSesRealizadas, 1) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Profesional</div>
        <div class="stat-value valor-prof">$<?= number_format($totalProf, 2) ?></div>
    </div>
    <?php if (!isCoordinator()): ?>
    <div class="stat-card">
        <div class="stat-label">Total Empresa</div>
        <div class="stat-value valor-emp">$<?= number_format($totalEmp, 2) ?></div>
    </div>
    <?php endif; ?>
</div>

<?php if (empty($detalle)): ?>
    <div style="text-align: center; padding: 3rem; color: var(--stormy-blue);">
        <h3>No hay liquidaciones cargadas para este profesional en <?= $periodoDisplay ?></h3>
    </div>
<?php else: ?>
<table class="detalle-table">
    <thead>
        <tr>
            <th>Paciente</th>
            <th>Prestación</th>
            <th>Empresa</th>
            <th>Ses. Esperadas</th>
            <th>Ses. Realizadas</th>
            <th>Diferencia</th>
            <th>Valor Unit.</th>
            <th>Total Profesional</th>
            <?php if (!isCoordinator()): ?>
            <th>Total Empresa</th>
            <?php endif; ?>
            <th>Observaciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($detalle as $row):
            $diff = $row['sesiones_realizadas'] - $row['sesiones_esperadas'];
            $diffClass = $diff > 0 ? 'positive' : ($diff < 0 ? 'negative' : 'neutral');
            $diffSign = $diff > 0 ? '+' : '';
        ?>
        <tr>
            <td style="font-weight: 600;"><?= htmlspecialchars($row['paciente_nombre']) ?></td>
            <td><?= htmlspecialchars($row['prestacion_nombre']) ?></td>
            <td><?= htmlspecialchars($row['empresa_nombre'] ?? '-') ?></td>
            <td style="text-align: center;"><?= number_format($row['sesiones_esperadas'], 1) ?></td>
            <td style="text-align: center; font-weight: 700;"><?= number_format($row['sesiones_realizadas'], 1) ?></td>
            <td style="text-align: center;">
                <span class="badge-diff <?= $diffClass ?>"><?= $diffSign . number_format($diff, 1) ?></span>
            </td>
            <td style="text-align: center;">$<?= number_format($row['valor_profesional'], 2) ?></td>
            <td class="valor-prof" style="text-align: center;">$<?= number_format($row['total_profesional'], 2) ?></td>
            <?php if (!isCoordinator()): ?>
            <td class="valor-emp" style="text-align: center;">$<?= number_format($row['total_empresa'], 2) ?></td>
            <?php endif; ?>
            <td>
                <?php if (!empty($row['observaciones'])): ?>
                    <span class="obs-text"><?= htmlspecialchars($row['observaciones']) ?></span>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: right;">TOTALES</td>
            <td style="text-align: center;"><?= number_format($totalSesEsperadas, 1) ?></td>
            <td style="text-align: center;"><?= number_format($totalSesRealizadas, 1) ?></td>
            <td style="text-align: center;">
                <?php
                $diffTotal = $totalSesRealizadas - $totalSesEsperadas;
                $diffTotalClass = $diffTotal > 0 ? 'positive' : ($diffTotal < 0 ? 'negative' : 'neutral');
                $diffTotalSign = $diffTotal > 0 ? '+' : '';
                ?>
                <span class="badge-diff <?= $diffTotalClass ?>"><?= $diffTotalSign . number_format($diffTotal, 1) ?></span>
            </td>
            <td></td>
            <td class="valor-prof" style="text-align: center; font-size: 1.125rem;">$<?= number_format($totalProf, 2) ?></td>
            <?php if (!isCoordinator()): ?>
            <td class="valor-emp" style="text-align: center; font-size: 1.125rem;">$<?= number_format($totalEmp, 2) ?></td>
            <?php endif; ?>
            <td></td>
        </tr>
    </tfoot>
</table>
<?php endif; ?>

<a href="<?= baseUrl('professionals/reports?periodo=' . htmlspecialchars($periodo)) ?>" class="btn-volver">
    <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6"/>
    </svg>
    Volver a Reportes
</a>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

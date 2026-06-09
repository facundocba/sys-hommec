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

// Agrupar prestaciones por profesional
$porProfesional = [];
foreach ($prestaciones as $prest) {
    $profId = $prest['id_profesional'];
    if (!isset($porProfesional[$profId])) {
        $porProfesional[$profId] = [
            'nombre' => $prest['profesional_nombre'],
            'especialidad' => $prest['especialidad'] ?? '',
            'prestaciones' => []
        ];
    }
    $porProfesional[$profId]['prestaciones'][] = $prest;
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
    .page-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .page-header h1 {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 60%, var(--stormy-cyan) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    .periodo-selector select {
        padding: 0.75rem 2.5rem 0.75rem 1rem;
        border: 2px solid rgba(136,219,242,0.3);
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        font-family: inherit;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%236A89A7' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
    }
    .periodo-selector select:focus { outline: none; border-color: var(--stormy-cyan); }
    .filters-card {
        background: rgba(255,255,255,0.98);
        border: 1px solid rgba(136,219,242,0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(56,73,89,0.06);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    .filters-card label {
        font-weight: 600;
        color: var(--stormy-dark);
        font-size: 0.875rem;
        text-transform: uppercase;
    }
    .filters-card select {
        padding: 0.75rem 2.5rem 0.75rem 1rem;
        border: 2px solid rgba(136,219,242,0.2);
        border-radius: 12px;
        font-size: 0.9375rem;
        font-family: inherit;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%236A89A7' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
    }
    .prof-section {
        background: rgba(255,255,255,0.98);
        border: 2px solid rgba(136,219,242,0.2);
        border-radius: 20px;
        margin-bottom: 2rem;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(56,73,89,0.06);
    }
    .prof-section-header {
        background: linear-gradient(135deg, rgba(136,219,242,0.08) 0%, transparent 100%);
        padding: 1.5rem 2rem;
        border-bottom: 2px solid rgba(136,219,242,0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .prof-section-header h3 { margin: 0; font-size: 1.25rem; font-weight: 800; color: var(--stormy-dark); }
    .prof-section-header .specialty { font-size: 0.875rem; color: var(--stormy-blue); font-weight: 600; }
    .prest-table { width: 100%; border-collapse: collapse; }
    .prest-table thead th {
        padding: 1rem 1.25rem;
        font-weight: 700;
        color: var(--stormy-dark);
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 1px solid rgba(136,219,242,0.15);
        text-align: left;
        background: rgba(136,219,242,0.03);
    }
    .prest-table tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(136,219,242,0.08);
        font-size: 0.9375rem;
        vertical-align: middle;
    }
    .prest-table tbody tr:last-child td { border-bottom: none; }
    .prest-table tbody tr:hover { background: rgba(136,219,242,0.03); }
    .input-sesiones {
        width: 90px;
        padding: 0.625rem 0.75rem;
        border: 2px solid rgba(136,219,242,0.25);
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 700;
        text-align: center;
        font-family: inherit;
        transition: all 0.3s ease;
    }
    .input-sesiones:focus { outline: none; border-color: var(--stormy-cyan); box-shadow: 0 0 0 4px rgba(136,219,242,0.12); }
    .input-sesiones.modified { border-color: #10b981; background: rgba(16,185,129,0.05); }
    .input-valor {
        width: 110px;
        padding: 0.5rem 0.625rem;
        border: 2px solid rgba(136,219,242,0.25);
        border-radius: 10px;
        font-size: 0.9375rem;
        font-weight: 600;
        text-align: right;
        font-family: inherit;
        transition: all 0.2s ease;
    }
    .input-valor:focus { outline: none; border-color: var(--stormy-cyan); box-shadow: 0 0 0 4px rgba(136,219,242,0.12); }
    .input-valor.valor-ok { border-color: #10b981; background: rgba(16,185,129,0.06); }
    .input-valor.valor-error { border-color: #dc2626; background: rgba(239,68,68,0.06); }
    .input-obs {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid rgba(136,219,242,0.2);
        border-radius: 8px;
        font-size: 0.8125rem;
        font-family: inherit;
        resize: none;
    }
    .input-obs:focus { outline: none; border-color: var(--stormy-cyan); }
    .btn-guardar {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 1rem 3rem;
        border-radius: 14px;
        font-weight: 700;
        font-size: 1.0625rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-guardar:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(16,185,129,0.3); }
    .btn-volver {
        background: rgba(136,219,242,0.1);
        color: var(--stormy-blue);
        border: 2px solid rgba(136,219,242,0.3);
        padding: 1rem 2rem;
        border-radius: 14px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.9375rem;
    }
    .btn-volver:hover { background: rgba(136,219,242,0.2); border-color: var(--stormy-cyan); }
    .actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem 0;
        gap: 1rem;
    }
    .badge-estado { display: inline-block; padding: 0.25rem 0.625rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
    .badge-estado.activo { background: rgba(16,185,129,0.1); color: #059669; }
    .badge-estado.finalizado { background: rgba(245,158,11,0.1); color: #d97706; }
    .empty-state { text-align: center; padding: 4rem 2rem; color: var(--stormy-blue); }
    @media (max-width: 768px) {
        .page-header-content { flex-direction: column; }
        .filters-card { flex-direction: column; align-items: stretch; }
        .prest-table { font-size: 0.8125rem; }
        .prest-table thead th, .prest-table tbody td { padding: 0.75rem; }
        .actions-bar { flex-direction: column; }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(16,185,129,0.35);">
                <svg style="width: 28px; height: 28px; stroke: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </div>
            <div>
                <h1>Cargar Sesiones Realizadas</h1>
                <p style="margin: 0.5rem 0 0 0; color: var(--stormy-blue);">
                    Ingrese las sesiones realmente realizadas para <strong><?= $periodoDisplay ?></strong>
                </p>
            </div>
        </div>
        <div class="periodo-selector">
            <select onchange="window.location.href='<?= baseUrl('professionals/cargar') ?>?periodo=' + this.value + '<?= !empty($filtroProf) ? '&profesional=' . htmlspecialchars($filtroProf) : '' ?>'">
                <?php for ($i = 0; $i < 12; $i++):
                    $m = date('Y-m', strtotime("-$i months"));
                    $label = ($meses[date('m', strtotime("-$i months"))] ?? '') . ' ' . date('Y', strtotime("-$i months"));
                ?>
                    <option value="<?= $m ?>" <?= $m === $periodo ? 'selected' : '' ?>><?= $label ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>
</div>

<!-- Filtro por profesional -->
<div class="filters-card">
    <label>Filtrar por profesional:</label>
    <select onchange="window.location.href='<?= baseUrl('professionals/cargar') ?>?periodo=<?= htmlspecialchars($periodo) ?>&profesional=' + this.value">
        <option value="">Todos los profesionales</option>
        <?php foreach ($professionals as $prof): ?>
            <option value="<?= $prof['id'] ?>" <?= $filtroProf == $prof['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($prof['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<?php if (empty($porProfesional)): ?>
    <div class="empty-state">
        <h3>No hay prestaciones activas para este período</h3>
        <p>No se encontraron prestaciones activas o finalizadas durante <?= $periodoDisplay ?>.</p>
    </div>
<?php else: ?>
<form method="POST" action="<?= baseUrl('professionals/store-liquidacion') ?>" id="formLiquidacion">
    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
    <input type="hidden" name="periodo" value="<?= htmlspecialchars($periodo) ?>">

    <?php foreach ($porProfesional as $profId => $profData): ?>
    <div class="prof-section">
        <div class="prof-section-header">
            <div>
                <h3><?= htmlspecialchars($profData['nombre']) ?></h3>
                <?php if ($profData['especialidad']): ?>
                    <span class="specialty"><?= htmlspecialchars($profData['especialidad']) ?></span>
                <?php endif; ?>
            </div>
            <div>
                <span style="font-size: 0.875rem; color: var(--stormy-blue); font-weight: 600;">
                    <?= count($profData['prestaciones']) ?> prestaci<?= count($profData['prestaciones']) === 1 ? 'ón' : 'ones' ?>
                </span>
            </div>
        </div>

        <table class="prest-table">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Prestación</th>
                    <th>Empresa</th>
                    <th>Estado</th>
                    <th>Ses. Esperadas</th>
                    <th>Ses. Realizadas</th>
                    <th>Valor Prof.</th>
                    <?php if (!isCoordinator()): ?>
                    <th>Valor Emp.</th>
                    <?php endif; ?>
                    <th>Total Prof.</th>
                    <th>Obs.</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($profData['prestaciones'] as $prest):
                    $sesEsperadas = $prest['sesiones_esperadas'];
                    $sesRealizadas = $prest['sesiones_realizadas_guardadas'] ?? '';
                    $obsGuardadas = $prest['observaciones_guardadas'] ?? '';
                    $valorProf = floatval($prest['valor_profesional']);
                    $valorEmp = floatval($prest['valor_empresa']);
                    $modo = $prest['modo_frecuencia'] ?? 'sesiones';
                    $unidadLabel = $modo === 'horas' ? 'hs' : 'ses';
                ?>
                <tr>
                    <td style="font-weight: 600;"><?= htmlspecialchars($prest['paciente_nombre']) ?></td>
                    <td><?= htmlspecialchars($prest['prestacion_nombre']) ?></td>
                    <td><?= htmlspecialchars($prest['empresa_nombre'] ?? '-') ?></td>
                    <td>
                        <span class="badge-estado <?= $prest['prestacion_estado'] ?>">
                            <?= ucfirst($prest['prestacion_estado']) ?>
                        </span>
                    </td>
                    <td style="text-align: center; font-weight: 600;">
                        <?= number_format($sesEsperadas, 1) ?> <?= $unidadLabel ?>
                    </td>
                    <td style="text-align: center;">
                        <input type="number"
                               name="prestaciones[<?= $prest['id'] ?>][sesiones_realizadas]"
                               class="input-sesiones"
                               value="<?= $sesRealizadas !== '' ? $sesRealizadas : '' ?>"
                               placeholder="<?= number_format($sesEsperadas, 1) ?>"
                               step="0.5" min="0"
                               data-esperadas="<?= $sesEsperadas ?>"
                               data-valor-prof="<?= $valorProf ?>"
                               onchange="calcularTotal(this)"
                               oninput="calcularTotal(this)">
                    </td>
                    <td style="text-align: center;">
                        <input type="number"
                               class="input-valor input-valor-prof"
                               data-id="<?= $prest['id'] ?>"
                               data-campo="valor_profesional"
                               value="<?= $valorProf ?>"
                               step="0.01" min="0"
                               onchange="guardarValor(this)">
                    </td>
                    <?php if (!isCoordinator()): ?>
                    <td style="text-align: center;">
                        <input type="number"
                               class="input-valor input-valor-emp"
                               data-id="<?= $prest['id'] ?>"
                               data-campo="valor_empresa"
                               value="<?= $valorEmp ?>"
                               step="0.01" min="0"
                               onchange="guardarValor(this)">
                    </td>
                    <?php endif; ?>
                    <td class="total-cell-prof" style="text-align: center; font-weight: 700; color: #10b981;">
                        <?php if ($sesRealizadas !== ''): ?>
                            $<?= number_format(floatval($sesRealizadas) * $valorProf, 2) ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <textarea name="prestaciones[<?= $prest['id'] ?>][observaciones]"
                                  class="input-obs" rows="1"
                                  placeholder="Obs..."><?= htmlspecialchars($obsGuardadas) ?></textarea>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; ?>

    <div class="actions-bar">
        <a href="<?= baseUrl('professionals/reports?periodo=' . htmlspecialchars($periodo)) ?>" class="btn-volver">
            Volver a Reportes
        </a>
        <button type="submit" class="btn-guardar">
            <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/>
                <polyline points="7 3 7 8 15 8"/>
            </svg>
            Guardar Liquidación
        </button>
    </div>
</form>
<?php endif; ?>

<script>
const URL_UPDATE_VALOR = '<?= baseUrl('professionals/update-valor-prestacion') ?>';
const CSRF_TOKEN = '<?= generateCSRFToken() ?>';

// Valor profesional vigente de la fila: input editable (admin) o dataset (coordinador).
function getValorProf(row) {
    const inputProf = row.querySelector('.input-valor-prof');
    if (inputProf) {
        return parseFloat(inputProf.value) || 0;
    }
    const ses = row.querySelector('.input-sesiones');
    return ses ? (parseFloat(ses.dataset.valorProf) || 0) : 0;
}

function calcularTotal(input) {
    const valor = parseFloat(input.value);
    const esperadas = parseFloat(input.dataset.esperadas);
    const row = input.closest('tr');
    const totalCell = row.querySelector('.total-cell-prof');
    const valorProf = getValorProf(row);

    if (!isNaN(valor) && valor >= 0) {
        const total = valor * valorProf;
        totalCell.textContent = '$' + total.toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        input.classList.toggle('modified', valor !== esperadas);
    } else {
        totalCell.textContent = '-';
        input.classList.remove('modified');
    }
}

function guardarValor(input) {
    const valor = parseFloat(input.value);
    input.classList.remove('valor-ok', 'valor-error');

    if (isNaN(valor) || valor < 0) {
        input.classList.add('valor-error');
        return;
    }

    const fd = new FormData();
    fd.append('csrf_token', CSRF_TOKEN);
    fd.append('id_prestacion_paciente', input.dataset.id);
    fd.append('campo', input.dataset.campo);
    fd.append('valor', valor);

    fetch(URL_UPDATE_VALOR, {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data && data.ok) {
            input.classList.add('valor-ok');
            if (input.dataset.campo === 'valor_profesional') {
                const ses = input.closest('tr').querySelector('.input-sesiones');
                if (ses) calcularTotal(ses);
            }
        } else {
            input.classList.add('valor-error');
            alert((data && data.error) ? data.error : 'No se pudo guardar el valor.');
        }
    })
    .catch(() => {
        input.classList.add('valor-error');
        alert('Error de conexión al guardar el valor.');
    });
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

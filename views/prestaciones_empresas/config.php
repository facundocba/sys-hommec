<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Toast Container -->
<div id="toast-container"></div>

<style>
    .page-header {
        background: linear-gradient(135deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);
        border-radius: 20px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(136, 219, 242, 0.3);
        color: white;
    }

    .page-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 800;
    }

    .prestaciones-grid {
        display: grid;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .prestacion-card {
        background: white !important;
        border: 2px solid rgba(136, 219, 242, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        display: grid;
        grid-template-columns: 1fr auto auto;
        align-items: center;
        gap: 1.5rem;
    }

    .prestacion-card:hover {
        border-color: var(--stormy-cyan);
        box-shadow: 0 4px 20px rgba(136, 219, 242, 0.15);
    }

    .prestacion-card.configured {
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.02);
    }

    .prestacion-info h4 {
        margin: 0 0 0.25rem 0;
        font-weight: 700;
        color: var(--stormy-dark);
    }

    .prestacion-info p {
        margin: 0;
        font-size: 0.875rem;
        color: var(--stormy-blue);
    }

    .prestacion-valor {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .valor-input {
        width: 180px;
        padding: 0.625rem 1rem;
        border: 2px solid rgba(136, 219, 242, 0.25);
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .valor-input:focus {
        outline: none;
        border-color: var(--stormy-cyan);
        box-shadow: 0 0 0 4px rgba(136, 219, 242, 0.15);
    }

    .badge-configured {
        background: #10b981;
        color: white;
        padding: 0.375rem 0.875rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-pending {
        background: #f59e0b;
        color: white;
        padding: 0.375rem 0.875rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .btn-save {
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        color: white;
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(106, 137, 167, 0.3);
    }

    .btn-save:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-delete-prestacion {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        border: 2px solid rgba(239, 68, 68, 0.3);
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-delete-prestacion:hover {
        background: var(--danger);
        border-color: var(--danger);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
    }

    .btn-delete-prestacion i {
        font-size: 0.875rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--stormy-blue);
    }

    .stats-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-item {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(255, 255, 255, 0.95) 100%
        );
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(136, 219, 242, 0.08);
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 32px rgba(136, 219, 242, 0.15);
        border-color: var(--stormy-cyan);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-dark), var(--stormy-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: var(--stormy-blue);
        font-weight: 600;
        letter-spacing: 0.5px;
    }
</style>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1><?= htmlspecialchars($empresa['nombre']) ?></h1>
            <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Configuración de Prestaciones y Valores</p>
        </div>
        <a href="<?= baseUrl('companies') ?>" class="btn btn-secondary" style="background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(10px); color: white; border: 2px solid rgba(255, 255, 255, 0.4); font-weight: 600;">
            <i class="bi bi-arrow-left me-1"></i>
            Volver a Empresas
        </a>
    </div>
</div>

<!-- Estadísticas -->
<div class="stats-summary">
    <div class="stat-item">
        <div class="stat-value"><?= count($todasPrestaciones) ?></div>
        <div class="stat-label">Total Prestaciones</div>
    </div>
    <div class="stat-item">
        <div class="stat-value" style="color: #10b981;"><?= count($configuradas) ?></div>
        <div class="stat-label">Configuradas</div>
    </div>
    <div class="stat-item">
        <div class="stat-value" style="color: #f59e0b;"><?= count($todasPrestaciones) - count($configuradas) ?></div>
        <div class="stat-label">Pendientes</div>
    </div>
</div>

<?php if (empty($todasPrestaciones)): ?>
    <div class="empty-state">
        <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
        <h3>No hay prestaciones disponibles</h3>
        <p>Primero debes crear tipos de prestación en el catálogo.</p>
        <a href="<?= baseUrl('prestaciones') ?>" class="btn btn-primary mt-3">
            <i class="bi bi-plus-lg me-1"></i>
            Gestionar Prestaciones
        </a>
    </div>
<?php else: ?>
    <div class="prestaciones-grid">
        <?php foreach ($todasPrestaciones as $prestacion): ?>
            <?php
                $isConfigured = isset($configuradas[$prestacion['id']]);
                $valorActual = $isConfigured ? $configuradas[$prestacion['id']]['valor_empresa'] : '';
                $configId = $isConfigured ? $configuradas[$prestacion['id']]['id'] : '';
            ?>
            <div class="prestacion-card <?= $isConfigured ? 'configured' : '' ?>" data-prestacion-id="<?= $prestacion['id'] ?>">
                <div class="prestacion-info">
                    <h4><?= htmlspecialchars($prestacion['nombre']) ?></h4>
                    <?php if (!empty($prestacion['descripcion'])): ?>
                        <p><?= htmlspecialchars($prestacion['descripcion']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="prestacion-valor">
                    <div class="input-group" style="width: auto;">
                        <span class="input-group-text">$</span>
                        <input
                            type="number"
                            class="valor-input"
                            data-prestacion-id="<?= $prestacion['id'] ?>"
                            data-config-id="<?= $configId ?>"
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            value="<?= htmlspecialchars($valorActual) ?>"
                        >
                    </div>
                    <button
                        type="button"
                        class="btn-save"
                        onclick="saveValor(<?= $prestacion['id'] ?>)"
                        <?= !hasRole('administrador') ? 'disabled' : '' ?>
                    >
                        <i class="bi bi-check-lg"></i> Guardar
                    </button>
                    <?php if ($isConfigured && hasRole('administrador')): ?>
                        <button
                            type="button"
                            class="btn-delete-prestacion"
                            onclick="deletePrestacion(<?= $configId ?>, '<?= htmlspecialchars($prestacion['nombre'], ENT_QUOTES) ?>')"
                            title="Quitar prestación de esta empresa"
                        >
                            <i class="bi bi-x-circle"></i>
                        </button>
                    <?php endif; ?>
                </div>

                <div>
                    <?php if ($isConfigured): ?>
                        <span class="badge-configured">
                            <i class="bi bi-check-circle me-1"></i>Configurado
                        </span>
                    <?php else: ?>
                        <span class="badge-pending">
                            <i class="bi bi-exclamation-circle me-1"></i>Pendiente
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
const idEmpresa = <?= $empresa['id'] ?>;
const csrfToken = '<?= generateCSRFToken() ?>';

function saveValor(idPrestacion) {
    const input = document.querySelector(`.valor-input[data-prestacion-id="${idPrestacion}"]`);
    const button = event.target.closest('button');
    const card = document.querySelector(`.prestacion-card[data-prestacion-id="${idPrestacion}"]`);

    const valor = input.value;

    if (!valor || parseFloat(valor) < 0) {
        alert('Por favor ingrese un valor válido');
        return;
    }

    // Deshabilitar botón mientras se guarda
    button.disabled = true;
    button.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';

    fetch('<?= baseUrl('prestaciones-empresas/saveConfig') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `csrf_token=${csrfToken}&id_empresa=${idEmpresa}&id_tipo_prestacion=${idPrestacion}&valor_empresa=${valor}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Marcar como configurado
            card.classList.add('configured');
            const badge = card.querySelector('.badge-pending, .badge-configured');
            if (badge) {
                badge.className = 'badge-configured';
                badge.innerHTML = '<i class="bi bi-check-circle me-1"></i>Configurado';
            }

            // Mostrar mensaje de éxito
            showToast(data.message, 'success');

            // Actualizar estadísticas sin recargar
            updateStats();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error al guardar la configuración', 'error');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = '<i class="bi bi-check-lg"></i> Guardar';
    });
}

function updateStats() {
    const totalConfiguradas = document.querySelectorAll('.prestacion-card.configured').length;
    const totalPrestaciones = document.querySelectorAll('.prestacion-card').length;
    const totalPendientes = totalPrestaciones - totalConfiguradas;

    // Actualizar los valores en las estadísticas
    const stats = document.querySelectorAll('.stat-value');
    if (stats[1]) stats[1].textContent = totalConfiguradas;
    if (stats[2]) stats[2].textContent = totalPendientes;
}

function deletePrestacion(configId, prestacionNombre) {
    // Debug
    if (!configId) {
        alert('ERROR: configId está vacío. No se puede eliminar.');
        console.error('configId vacío:', configId);
        return;
    }

    console.log('Intentando eliminar config ID:', configId, 'Prestación:', prestacionNombre);

    const message = `¿Está seguro de quitar la prestación "${prestacionNombre}" de esta empresa?\n\nNOTA: Esto solo elimina la asociación/configuración de esta prestación con la empresa. La prestación seguirá disponible para otras empresas.`;

    showConfirmModal(
        message,
        'Quitar Prestación de Empresa',
        function() {
            console.log('Creando formulario para eliminar config ID:', configId);

            // Crear formulario para enviar la solicitud
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= baseUrl("prestaciones-empresas/delete/") ?>' + configId;

            console.log('Action URL:', form.action);

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = csrfToken;

            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    );
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

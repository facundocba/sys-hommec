<?php
include __DIR__ . '/../layouts/header.php';

// Recuperar datos del formulario si hay error
$formData = $_SESSION['form_data'] ?? $prestacionEmpresa;
unset($_SESSION['form_data']);
?>

<style>
    .form-card {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 8px 24px rgba(56, 73, 89, 0.08);
    }

    .form-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid rgba(136, 219, 242, 0.2);
    }

    .form-header h1 {
        font-size: 1.75rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }

    .form-label {
        font-weight: 600;
        color: var(--stormy-dark);
        margin-bottom: 0.5rem;
    }

    .form-label-required {
        color: var(--danger);
    }

    .form-control, .form-select {
        border: 2px solid rgba(136, 219, 242, 0.25);
        border-radius: 12px;
        padding: 0.75rem 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--stormy-cyan);
        box-shadow: 0 0 0 4px rgba(136, 219, 242, 0.15);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid rgba(136, 219, 242, 0.2);
    }

    .info-box {
        background: rgba(136, 219, 242, 0.08);
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
</style>

<div class="container py-4">
    <div class="form-card">
        <div class="form-header">
            <h1>Editar Configuración Prestación-Empresa</h1>
            <p class="text-muted mb-0">Modifica el valor que cobra la empresa por esta prestación</p>
        </div>

        <div class="info-box">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Última modificación:</strong> <?= date('d/m/Y H:i', strtotime($prestacionEmpresa['fecha_modificacion'])) ?>
            </small>
        </div>

        <form method="POST" action="<?= baseUrl('prestaciones-empresas/update/' . $prestacionEmpresa['id']) ?>">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <div class="row g-4">
                <!-- Empresa -->
                <div class="col-12">
                    <label for="id_empresa" class="form-label">
                        Empresa
                        <span class="form-label-required">*</span>
                    </label>
                    <select
                        id="id_empresa"
                        name="id_empresa"
                        class="form-select"
                        required
                    >
                        <option value="">Seleccionar empresa</option>
                        <?php foreach ($companies as $company): ?>
                            <option
                                value="<?= $company['id'] ?>"
                                <?= $formData['id_empresa'] == $company['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($company['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Prestación -->
                <div class="col-12">
                    <label for="id_tipo_prestacion" class="form-label">
                        Prestación
                        <span class="form-label-required">*</span>
                    </label>
                    <select
                        id="id_tipo_prestacion"
                        name="id_tipo_prestacion"
                        class="form-select"
                        required
                    >
                        <option value="">Seleccionar prestación</option>
                        <?php foreach ($tiposPrestacion as $tipo): ?>
                            <option
                                value="<?= $tipo['id'] ?>"
                                <?= $formData['id_tipo_prestacion'] == $tipo['id'] ? 'selected' : '' ?>
                            >
                                <?= htmlspecialchars($tipo['nombre']) ?>
                                <?php if (!empty($tipo['descripcion'])): ?>
                                    - <?= htmlspecialchars($tipo['descripcion']) ?>
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Valor Empresa -->
                <div class="col-md-6">
                    <label for="valor_empresa" class="form-label">
                        Valor Empresa
                        <span class="form-label-required">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input
                            type="number"
                            id="valor_empresa"
                            name="valor_empresa"
                            class="form-control"
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            value="<?= htmlspecialchars($formData['valor_empresa']) ?>"
                            required
                        >
                    </div>
                    <small class="text-muted">Monto que cobra la empresa por esta prestación</small>
                </div>

                <!-- Estado -->
                <div class="col-md-6">
                    <label for="estado" class="form-label">
                        Estado
                        <span class="form-label-required">*</span>
                    </label>
                    <select
                        id="estado"
                        name="estado"
                        class="form-select"
                        required
                    >
                        <option value="activo" <?= $formData['estado'] === 'activo' ? 'selected' : '' ?>>
                            Activo
                        </option>
                        <option value="inactivo" <?= $formData['estado'] === 'inactivo' ? 'selected' : '' ?>>
                            Inactivo
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= baseUrl('prestaciones-empresas') ?>" class="btn btn-secondary">
                    <i class="bi bi-x-lg me-1"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

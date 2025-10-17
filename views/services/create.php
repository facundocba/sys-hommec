<?php
include __DIR__ . '/../layouts/header.php';

// Recuperar datos del formulario si hay error
$formData = $_SESSION['form_data'] ?? [];
$formErrors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_data'], $_SESSION['form_errors']);
?>

<style>
    .page-header {
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(136, 219, 242, 0.25);
        border-radius: 20px;
        padding: 2rem;
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

    .page-header-text h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.02em;
        margin-bottom: 0.5rem;
    }

    .page-header-text p {
        color: var(--stormy-blue);
        margin: 0;
        font-size: 0.9375rem;
        font-weight: 500;
    }

    .form-card {
        max-width: 1000px;
        margin: 0 auto;
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(136, 219, 242, 0.3);
        border-radius: 20px;
        padding: 0;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.12),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        overflow: hidden;
    }

    .form-section {
        padding: 2.5rem;
        position: relative;
    }

    .form-section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(136, 219, 242, 0.2);
    }

    .form-section-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        box-shadow: 0 6px 20px rgba(106, 137, 167, 0.3);
    }

    .form-section-icon svg {
        width: 24px;
        height: 24px;
        stroke: var(--white);
    }

    .form-section-title {
        font-size: 1.25rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.75rem;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        color: var(--stormy-dark);
        font-weight: 600;
        font-size: 0.9375rem;
    }

    .form-label-required {
        color: var(--danger);
        font-size: 0.875rem;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1.25rem;
        font-size: 1rem;
        border: 2px solid rgba(136, 219, 242, 0.25);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.8);
        color: var(--stormy-dark);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--stormy-cyan);
        background: rgba(255, 255, 255, 1);
        box-shadow:
            0 0 0 4px rgba(136, 219, 242, 0.15),
            0 4px 12px rgba(136, 219, 242, 0.2);
        transform: translateY(-1px);
    }

    .form-control.is-invalid {
        border-color: var(--danger);
    }

    .form-control.is-invalid:focus {
        box-shadow:
            0 0 0 4px rgba(239, 68, 68, 0.15),
            0 4px 12px rgba(239, 68, 68, 0.2);
    }

    .invalid-feedback {
        color: var(--danger);
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control::placeholder {
        color: rgba(56, 73, 89, 0.4);
        font-weight: 400;
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%236A89A7' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 3rem;
    }

    .form-hint {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--stormy-blue);
        font-size: 0.8125rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    .form-hint::before {
        content: '';
        width: 4px;
        height: 4px;
        background: var(--stormy-cyan);
        border-radius: 50%;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem 2.5rem;
        background: linear-gradient(135deg, rgba(189, 221, 252, 0.1) 0%, rgba(136, 219, 242, 0.05) 100%);
        border-top: 2px solid rgba(136, 219, 242, 0.2);
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.9);
        color: var(--stormy-blue);
        border: 2px solid rgba(136, 219, 242, 0.3);
        padding: 0.875rem 1.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 1);
        border-color: var(--stormy-cyan);
        transform: translateX(-4px);
        box-shadow: 0 4px 12px rgba(136, 219, 242, 0.2);
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        color: var(--white);
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 16px rgba(106, 137, 167, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        overflow: hidden;
    }

    .btn-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(106, 137, 167, 0.4);
    }

    .btn-submit:hover::before {
        left: 100%;
    }

    .btn-submit svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-header-text">
            <h1>Crear Nueva Prestación</h1>
            <p>Complete el formulario para agregar una nueva prestación al sistema</p>
        </div>
        <a href="<?= baseUrl('services') ?>" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="<?= baseUrl('services/store') ?>">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                </div>
                <h2 class="form-section-title">Información de la Prestación</h2>
            </div>

            <div class="row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="nombre" class="form-label">
                            Nombre de la Prestación
                            <span class="form-label-required">*</span>
                        </label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            class="form-control <?= isset($formErrors['nombre']) ? 'is-invalid' : '' ?>"
                            placeholder="Ej: Sesión de Kinesiología, Consulta Psicológica"
                            value="<?= htmlspecialchars($formData['nombre'] ?? '') ?>"
                            required
                            autofocus
                        >
                        <?php if (isset($formErrors['nombre'])): ?>
                            <div class="invalid-feedback"><?= $formErrors['nombre'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="codigo" class="form-label">Código</label>
                        <input
                            type="text"
                            id="codigo"
                            name="codigo"
                            class="form-control <?= isset($formErrors['codigo']) ? 'is-invalid' : '' ?>"
                            placeholder="Ej: KIN-001, PSI-002"
                            value="<?= htmlspecialchars($formData['codigo'] ?? '') ?>"
                        >
                        <div class="form-hint">Opcional - Código interno de la prestación</div>
                        <?php if (isset($formErrors['codigo'])): ?>
                            <div class="invalid-feedback"><?= $formErrors['codigo'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="id_tipo_prestacion" class="form-label">Tipo de Prestación</label>
                        <select
                            id="id_tipo_prestacion"
                            name="id_tipo_prestacion"
                            class="form-control form-select"
                        >
                            <option value="">Seleccionar tipo</option>
                            <?php foreach ($tiposPrestacion as $tipo): ?>
                                <option
                                    value="<?= $tipo['id'] ?>"
                                    <?= ($formData['id_tipo_prestacion'] ?? '') == $tipo['id'] ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($tipo['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-hint">Opcional - Categoría a la que pertenece esta prestación</div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea
                            id="descripcion"
                            name="descripcion"
                            class="form-control"
                            placeholder="Descripción detallada de la prestación"
                            rows="4"
                        ><?= htmlspecialchars($formData['descripcion'] ?? '') ?></textarea>
                        <div class="form-hint">Opcional - Información adicional sobre la prestación</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M12 1v6m0 6v6m5.5-13.5l-4.24 4.24m-2.52 2.52L6.5 16.5m11-11l-4.24 4.24m-2.52 2.52L6.5 6.5m11 11l-4.24-4.24m-2.52-2.52L6.5 16.5"/>
                    </svg>
                </div>
                <h2 class="form-section-title">Configuración</h2>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="estado" class="form-label">
                            Estado
                            <span class="form-label-required">*</span>
                        </label>
                        <select id="estado" name="estado" class="form-control form-select" required>
                            <option value="activo" <?= ($formData['estado'] ?? 'activo') === 'activo' ? 'selected' : '' ?>>
                                Activo
                            </option>
                            <option value="inactivo" <?= ($formData['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>
                                Inactivo
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="<?= baseUrl('services') ?>" class="btn-back">
                Cancelar
            </a>
            <button type="submit" class="btn-submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Crear Prestación
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

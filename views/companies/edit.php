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

    .form-section-subtitle {
        font-size: 0.875rem;
        color: var(--stormy-blue);
        font-weight: 500;
        margin-top: 0.25rem;
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

    .info-box {
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.1) 0%, rgba(189, 221, 252, 0.08) 100%);
        border: 2px solid rgba(136, 219, 242, 0.25);
        border-radius: 16px;
        padding: 1.5rem;
        margin-top: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .info-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);
    }

    .info-box-content {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .info-box-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--stormy-blue);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .info-box-item svg {
        width: 16px;
        height: 16px;
        stroke: var(--stormy-cyan);
        flex-shrink: 0;
    }

    .info-box-label {
        font-weight: 600;
        color: var(--stormy-dark);
        min-width: 140px;
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
            <h1>Editar Empresa</h1>
            <p>Modifique la información de la empresa en el sistema</p>
        </div>
        <a href="<?= baseUrl('companies') ?>" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="<?= baseUrl('companies/update/' . $company['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                </div>
                <h2 class="form-section-title">Información de la Empresa</h2>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="nombre" class="form-label">
                            Nombre de la Empresa
                            <span class="form-label-required">*</span>
                        </label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            class="form-control <?= isset($formErrors['nombre']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($formData['nombre'] ?? $company['nombre']) ?>"
                            required
                            autofocus
                        >
                        <?php if (isset($formErrors['nombre'])): ?>
                            <div class="invalid-feedback"><?= $formErrors['nombre'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Correo Electrónico
                            <span class="form-label-required">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control <?= isset($formErrors['email']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($formData['email'] ?? $company['email']) ?>"
                            required
                        >
                        <?php if (isset($formErrors['email'])): ?>
                            <div class="invalid-feedback"><?= $formErrors['email'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input
                            type="text"
                            id="telefono"
                            name="telefono"
                            class="form-control <?= isset($formErrors['telefono']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($formData['telefono'] ?? $company['telefono'] ?? '') ?>"
                        >
                        <?php if (isset($formErrors['telefono'])): ?>
                            <div class="invalid-feedback"><?= $formErrors['telefono'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section" style="background: rgba(189, 221, 252, 0.03);">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <h2 class="form-section-title">Información Adicional</h2>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea
                            id="direccion"
                            name="direccion"
                            class="form-control"
                            rows="3"
                        ><?= htmlspecialchars($formData['direccion'] ?? $company['direccion'] ?? '') ?></textarea>
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
                            <option value="activo" <?= ($formData['estado'] ?? $company['estado']) === 'activo' ? 'selected' : '' ?>>
                                Activo
                            </option>
                            <option value="inactivo" <?= ($formData['estado'] ?? $company['estado']) === 'inactivo' ? 'selected' : '' ?>>
                                Inactivo
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="info-box">
                <div class="info-box-content">
                    <div class="info-box-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span class="info-box-label">Empresa creada:</span>
                        <span><?= formatDate($company['fecha_creacion'], 'd/m/Y H:i') ?></span>
                    </div>
                    <div class="info-box-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        <span class="info-box-label">Última modificación:</span>
                        <span><?= formatDate($company['fecha_modificacion'], 'd/m/Y H:i') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="<?= baseUrl('companies') ?>" class="btn-back">
                Cancelar
            </a>
            <button type="submit" class="btn-submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

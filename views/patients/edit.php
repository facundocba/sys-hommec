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
        max-width: 1200px;
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

    .form-section:nth-child(even) {
        background: rgba(189, 221, 252, 0.03);
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
        margin-bottom: 2.5rem;
    }

    .row.g-4 > * {
        padding-left: 1rem;
        padding-right: 1rem;
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

    .form-check {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.08) 0%, rgba(189, 221, 252, 0.05) 100%);
        border: 2px solid rgba(136, 219, 242, 0.2);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-check:hover {
        border-color: var(--stormy-cyan);
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.12) 0%, rgba(189, 221, 252, 0.08) 100%);
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .form-check-label {
        cursor: pointer;
        font-weight: 600;
        color: var(--stormy-dark);
        margin: 0;
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

    #fecha-finalizacion-group {
        transition: opacity 0.3s ease;
    }

    #fecha-finalizacion-group.disabled {
        opacity: 0.5;
        pointer-events: none;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-header-text">
            <h1>Editar Paciente</h1>
            <p>Modifique la información del paciente en el sistema</p>
        </div>
        <a href="<?= baseUrl('patients') ?>" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="<?= baseUrl('patients/update/' . $patient['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

        <!-- Sección: Datos Personales -->
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <h2 class="form-section-title">Datos Personales del Paciente</h2>
            </div>

            <div class="row g-4">
                <div class="col-12">
                    <div class="form-group">
                        <label for="nombre_completo" class="form-label">
                            Nombre Completo
                            <span class="form-label-required">*</span>
                        </label>
                        <input
                            type="text"
                            id="nombre_completo"
                            name="nombre_completo"
                            class="form-control <?= isset($formErrors['nombre_completo']) ? 'is-invalid' : '' ?>"
                            placeholder="Ingrese el nombre completo del paciente"
                            value="<?= htmlspecialchars($formData['nombre_completo'] ?? $patient['nombre_completo']) ?>"
                            required
                            autofocus
                        >
                        <?php if (isset($formErrors['nombre_completo'])): ?>
                            <div class="invalid-feedback"><?= $formErrors['nombre_completo'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="localidad" class="form-label">Localidad</label>
                        <input
                            type="text"
                            id="localidad"
                            name="localidad"
                            class="form-control"
                            placeholder="Ciudad o localidad"
                            value="<?= htmlspecialchars($formData['localidad'] ?? $patient['localidad'] ?? '') ?>"
                        >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_provincia" class="form-label">Provincia</label>
                        <select
                            id="id_provincia"
                            name="id_provincia"
                            class="form-control form-select"
                        >
                            <option value="">Seleccionar provincia</option>
                            <?php foreach ($provinces as $prov): ?>
                                <option
                                    value="<?= $prov['id'] ?>"
                                    <?= ($formData['id_provincia'] ?? $patient['id_provincia'] ?? '') == $prov['id'] ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($prov['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id_obra_social" class="form-label">Obra Social</label>
                        <select
                            id="id_obra_social"
                            name="id_obra_social"
                            class="form-control form-select"
                        >
                            <option value="">Seleccionar obra social</option>
                            <?php foreach ($obrasSociales as $os): ?>
                                <option
                                    value="<?= $os['id'] ?>"
                                    <?= ($formData['id_obra_social'] ?? $patient['id_obra_social'] ?? '') == $os['id'] ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($os['nombre']) ?>
                                    <?php if (!empty($os['sigla'])): ?>
                                        (<?= htmlspecialchars($os['sigla']) ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección: Asignación Profesional -->
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="8.5" cy="7" r="4"/>
                        <polyline points="17 11 19 13 23 9"/>
                    </svg>
                </div>
                <h2 class="form-section-title">Asignación de Profesional y Prestación</h2>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="id_profesional" class="form-label">Profesional Asignado</label>
                        <select id="id_profesional" name="id_profesional" class="form-control form-select">
                            <option value="">Seleccione un profesional</option>
                            <?php foreach ($professionals as $prof): ?>
                                <option value="<?= $prof['id'] ?>" <?= ($formData['id_profesional'] ?? $patient['id_profesional'] ?? '') == $prof['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($prof['nombre']) ?> - <?= htmlspecialchars($prof['especialidad']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="id_prestacion" class="form-label">Prestación / Servicio</label>
                        <select id="id_prestacion" name="id_prestacion" class="form-control form-select">
                            <option value="">Seleccione una prestación</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['id'] ?>" <?= ($formData['id_prestacion'] ?? $patient['id_prestacion'] ?? '') == $service['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($service['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="id_empresa" class="form-label">Empresa</label>
                        <select id="id_empresa" name="id_empresa" class="form-control form-select">
                            <option value="">Seleccione una empresa</option>
                            <?php foreach ($companies as $company): ?>
                                <option value="<?= $company['id'] ?>" <?= ($formData['id_empresa'] ?? $patient['id_empresa'] ?? '') == $company['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($company['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="frecuencia_servicio" class="form-label">Frecuencia del Servicio</label>
                        <input
                            type="text"
                            id="frecuencia_servicio"
                            name="frecuencia_servicio"
                            class="form-control"
                            placeholder="Ej: 2 veces por semana"
                            value="<?= htmlspecialchars($formData['frecuencia_servicio'] ?? $patient['frecuencia_servicio'] ?? '') ?>"
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección: Fechas y Tipo de Paciente -->
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <h2 class="form-section-title">Fechas de Atención</h2>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="fecha_ingreso" class="form-label">
                            Fecha de Ingreso
                            <span class="form-label-required">*</span>
                        </label>
                        <input
                            type="date"
                            id="fecha_ingreso"
                            name="fecha_ingreso"
                            class="form-control <?= isset($formErrors['fecha_ingreso']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($formData['fecha_ingreso'] ?? $patient['fecha_ingreso']) ?>"
                            required
                        >
                        <?php if (isset($formErrors['fecha_ingreso'])): ?>
                            <div class="invalid-feedback"><?= $formErrors['fecha_ingreso'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-6" id="fecha-finalizacion-group">
                    <div class="form-group">
                        <label for="fecha_finalizacion" class="form-label">
                            Fecha de Finalización
                            <span class="form-label-required" id="fecha-required">*</span>
                        </label>
                        <input
                            type="date"
                            id="fecha_finalizacion"
                            name="fecha_finalizacion"
                            class="form-control <?= isset($formErrors['fecha_finalizacion']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($formData['fecha_finalizacion'] ?? $patient['fecha_finalizacion'] ?? '') ?>"
                        >
                        <div class="form-hint" id="fecha-hint">Fecha estimada de finalización del tratamiento</div>
                        <?php if (isset($formErrors['fecha_finalizacion'])): ?>
                            <div class="invalid-feedback"><?= $formErrors['fecha_finalizacion'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-check">
                            <input
                                type="checkbox"
                                id="paciente_recurrente"
                                name="paciente_recurrente"
                                class="form-check-input"
                                value="1"
                                <?= !empty($formData['paciente_recurrente'] ?? $patient['paciente_recurrente']) ? 'checked' : '' ?>
                            >
                            <span class="form-check-label">
                                Paciente Recurrente
                                <small style="display: block; font-weight: 400; color: var(--stormy-blue); margin-top: 0.25rem;">
                                    Marque esta opción si el paciente no tiene fecha de finalización definida
                                </small>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección: Valores Económicos -->
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <h2 class="form-section-title">Valores Económicos</h2>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="valor_profesional" class="form-label">Valor Profesional</label>
                        <input
                            type="number"
                            id="valor_profesional"
                            name="valor_profesional"
                            class="form-control <?= isset($formErrors['valor_profesional']) ? 'is-invalid' : '' ?>"
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            value="<?= htmlspecialchars($formData['valor_profesional'] ?? $patient['valor_profesional'] ?? '') ?>"
                        >
                        <div class="form-hint">Monto que se paga al profesional por el servicio</div>
                        <?php if (isset($formErrors['valor_profesional'])): ?>
                            <div class="invalid-feedback"><?= $formErrors['valor_profesional'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="valor_empresa" class="form-label">Valor Empresa</label>
                        <input
                            type="number"
                            id="valor_empresa"
                            name="valor_empresa"
                            class="form-control <?= isset($formErrors['valor_empresa']) ? 'is-invalid' : '' ?>"
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            value="<?= htmlspecialchars($formData['valor_empresa'] ?? $patient['valor_empresa'] ?? '') ?>"
                        >
                        <div class="form-hint">Monto que cobra la empresa por el servicio</div>
                        <?php if (isset($formErrors['valor_empresa'])): ?>
                            <div class="invalid-feedback"><?= $formErrors['valor_empresa'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección: Observaciones y Estado -->
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M12 1v6m0 6v6m5.5-13.5l-4.24 4.24m-2.52 2.52L6.5 16.5m11-11l-4.24 4.24m-2.52 2.52L6.5 6.5m11 11l-4.24-4.24m-2.52-2.52L6.5 16.5"/>
                    </svg>
                </div>
                <h2 class="form-section-title">Observaciones y Estado</h2>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea
                            id="observaciones"
                            name="observaciones"
                            class="form-control"
                            placeholder="Notas adicionales sobre el paciente o tratamiento"
                            rows="4"
                        ><?= htmlspecialchars($formData['observaciones'] ?? $patient['observaciones'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="estado" class="form-label">
                            Estado
                            <span class="form-label-required">*</span>
                        </label>
                        <select id="estado" name="estado" class="form-control form-select" required>
                            <option value="activo" <?= ($formData['estado'] ?? $patient['estado']) === 'activo' ? 'selected' : '' ?>>
                                Activo
                            </option>
                            <option value="suspendido" <?= ($formData['estado'] ?? $patient['estado']) === 'suspendido' ? 'selected' : '' ?>>
                                Suspendido
                            </option>
                            <option value="finalizado" <?= ($formData['estado'] ?? $patient['estado']) === 'finalizado' ? 'selected' : '' ?>>
                                Finalizado
                            </option>
                        </select>
                    </div>
                </div>

                <div class="info-box" style="background: linear-gradient(135deg, rgba(136, 219, 242, 0.1) 0%, rgba(189, 221, 252, 0.08) 100%); border: 2px solid rgba(136, 219, 242, 0.25); border-radius: 16px; padding: 1.5rem; margin-top: 1.5rem; position: relative; overflow: hidden;">
                    <div style="content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: linear-gradient(180deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);"></div>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; position: relative; z-index: 1; padding-left: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--stormy-blue); font-size: 0.875rem; font-weight: 500;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="stroke: var(--stormy-cyan); flex-shrink: 0;">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span style="font-weight: 600; color: var(--stormy-dark); min-width: 140px;">Paciente creado:</span>
                            <span><?= formatDate($patient['fecha_creacion'], 'd/m/Y H:i') ?></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; color: var(--stormy-blue); font-size: 0.875rem; font-weight: 500;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="stroke: var(--stormy-cyan); flex-shrink: 0;">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            <span style="font-weight: 600; color: var(--stormy-dark); min-width: 140px;">Última modificación:</span>
                            <span><?= formatDate($patient['fecha_modificacion'], 'd/m/Y H:i') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="<?= baseUrl('patients') ?>" class="btn-back">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recurrenteCheckbox = document.getElementById('paciente_recurrente');
    const fechaFinGroup = document.getElementById('fecha-finalizacion-group');
    const fechaFinInput = document.getElementById('fecha_finalizacion');
    const fechaRequired = document.getElementById('fecha-required');
    const fechaHint = document.getElementById('fecha-hint');

    function toggleFechaFin() {
        if (recurrenteCheckbox.checked) {
            fechaFinGroup.classList.add('disabled');
            fechaFinInput.removeAttribute('required');
            fechaFinInput.value = '';
            fechaRequired.style.display = 'none';
            fechaHint.textContent = 'No aplica para pacientes recurrentes';
        } else {
            fechaFinGroup.classList.remove('disabled');
            fechaFinInput.setAttribute('required', 'required');
            fechaRequired.style.display = 'inline';
            fechaHint.textContent = 'Fecha estimada de finalización del tratamiento';
        }
    }

    recurrenteCheckbox.addEventListener('change', toggleFechaFin);
    toggleFechaFin(); // Ejecutar al cargar
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

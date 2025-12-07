<?php
include __DIR__ . '/../layouts/header.php';
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

    /* Password Generator Styles */
    .password-input-wrapper {
        position: relative;
        display: flex;
        gap: 0.5rem;
    }

    .password-input-wrapper .form-control {
        flex: 1;
    }

    .btn-password-action {
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        color: var(--white);
        border: none;
        padding: 0.875rem 1rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(106, 137, 167, 0.25);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        position: relative;
        overflow: hidden;
    }

    .btn-password-action::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.4s ease, height 0.4s ease;
    }

    .btn-password-action:hover::before {
        width: 200px;
        height: 200px;
    }

    .btn-password-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(106, 137, 167, 0.35);
    }

    .btn-password-action:active {
        transform: translateY(0);
    }

    .btn-password-action svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        position: relative;
        z-index: 1;
    }

    .btn-password-action span {
        position: relative;
        z-index: 1;
    }

    .btn-copy {
        background: linear-gradient(135deg, rgba(106, 137, 167, 0.15) 0%, rgba(136, 219, 242, 0.1) 100%);
        color: var(--stormy-blue);
        border: 2px solid rgba(136, 219, 242, 0.3);
        box-shadow: 0 2px 8px rgba(106, 137, 167, 0.15);
        min-width: 44px;
        padding: 0.875rem;
        justify-content: center;
    }

    .btn-copy:hover {
        background: linear-gradient(135deg, rgba(106, 137, 167, 0.25) 0%, rgba(136, 219, 242, 0.2) 100%);
        border-color: var(--stormy-cyan);
    }

    .btn-copy.copied {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        border-color: #10b981;
        color: var(--white);
    }

    .password-strength {
        margin-top: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 1px solid rgba(136, 219, 242, 0.2);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.6) 0%, rgba(250, 252, 255, 0.4) 100%);
        display: none;
    }

    .password-strength.active {
        display: block;
    }

    .strength-label {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--stormy-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .strength-bar {
        height: 6px;
        background: rgba(136, 219, 242, 0.2);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .strength-bar-fill {
        height: 100%;
        transition: all 0.3s ease;
        border-radius: 3px;
    }

    .strength-weak .strength-bar-fill {
        width: 33%;
        background: linear-gradient(90deg, #ef4444 0%, #f87171 100%);
    }

    .strength-medium .strength-bar-fill {
        width: 66%;
        background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%);
    }

    .strength-strong .strength-bar-fill {
        width: 100%;
        background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
    }

    .strength-requirements {
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
        font-size: 0.75rem;
    }

    .strength-requirement {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--stormy-blue);
    }

    .strength-requirement.met {
        color: #10b981;
    }

    .strength-requirement svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
        flex-shrink: 0;
    }

    /* Alert Modal */
    .alert-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(56, 73, 89, 0.6);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .alert-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .alert-modal {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%
        );
        border-radius: 20px;
        padding: 2rem;
        max-width: 400px;
        width: 90%;
        box-shadow:
            0 20px 60px rgba(56, 73, 89, 0.2),
            0 8px 24px rgba(136, 219, 242, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        border: 1px solid rgba(136, 219, 242, 0.3);
        transform: scale(0.9) translateY(20px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .alert-modal-overlay.active .alert-modal {
        transform: scale(1) translateY(0);
    }

    .alert-modal::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg,
            transparent 0%,
            var(--stormy-cyan) 50%,
            transparent 100%
        );
    }

    .alert-modal-content {
        text-align: center;
    }

    .alert-modal-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg,
            rgba(245, 158, 11, 0.15) 0%,
            rgba(245, 158, 11, 0.08) 100%
        );
        border: 2px solid rgba(245, 158, 11, 0.3);
    }

    .alert-modal-icon svg {
        width: 32px;
        height: 32px;
        stroke: #f59e0b;
    }

    .alert-modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--stormy-dark);
        margin-bottom: 0.5rem;
    }

    .alert-modal-message {
        font-size: 0.9375rem;
        color: var(--stormy-blue);
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .alert-modal-btn {
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        color: var(--white);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(106, 137, 167, 0.25);
    }

    .alert-modal-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(106, 137, 167, 0.35);
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-header-text">
            <h1>Editar Usuario</h1>
            <p>Modifique la información del usuario en el sistema</p>
        </div>
        <a href="<?= baseUrl('users') ?>" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="<?= baseUrl('users/update/' . $user['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <h2 class="form-section-title">Información Personal</h2>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="nombre" class="form-label">
                            Nombre Completo
                            <span class="form-label-required">*</span>
                        </label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            class="form-control"
                            value="<?= htmlspecialchars($user['nombre']) ?>"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Correo Electrónico
                            <span class="form-label-required">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="<?= htmlspecialchars($user['email']) ?>"
                            required
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section" style="background: rgba(189, 221, 252, 0.03);">
            <div class="form-section-header">
                <div class="form-section-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="form-section-title">Cambiar Contraseña</h2>
                    <p class="form-section-subtitle">Dejar en blanco para mantener la contraseña actual</p>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <div class="password-input-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Mínimo 8 caracteres"
                                minlength="8"
                            >
                            <button type="button" class="btn-password-action" onclick="generateSecurePassword()" title="Generar contraseña segura">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
                                </svg>
                                <span>Generar</span>
                            </button>
                            <button type="button" class="btn-password-action btn-copy" onclick="copyPassword(event)" title="Copiar contraseña">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                                </svg>
                            </button>
                        </div>
                        <div id="passwordStrength" class="password-strength">
                            <div class="strength-label">
                                Fortaleza: <span id="strengthText">-</span>
                            </div>
                            <div class="strength-bar">
                                <div id="strengthBarFill" class="strength-bar-fill"></div>
                            </div>
                            <div class="strength-requirements">
                                <div class="strength-requirement" id="req-length">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                    </svg>
                                    <span>Mínimo 8 caracteres</span>
                                </div>
                                <div class="strength-requirement" id="req-uppercase">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                    </svg>
                                    <span>Al menos una mayúscula</span>
                                </div>
                                <div class="strength-requirement" id="req-lowercase">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                    </svg>
                                    <span>Al menos una minúscula</span>
                                </div>
                                <div class="strength-requirement" id="req-number">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                    </svg>
                                    <span>Al menos un número</span>
                                </div>
                                <div class="strength-requirement" id="req-special">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                    </svg>
                                    <span>Al menos un carácter especial</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="password_confirm" class="form-label">Confirmar Nueva Contraseña</label>
                        <input
                            type="password"
                            id="password_confirm"
                            name="password_confirm"
                            class="form-control"
                            placeholder="Repita la contraseña"
                            minlength="8"
                        >
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
                <h2 class="form-section-title">Configuración del Usuario</h2>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="rol" class="form-label">
                            Rol del Usuario
                            <span class="form-label-required">*</span>
                        </label>
                        <select id="rol" name="rol" class="form-control form-select" required>
                            <option value="coordinador" <?= $user['rol'] === 'coordinador' ? 'selected' : '' ?>>
                                Coordinador
                            </option>
                            <option value="administrador" <?= $user['rol'] === 'administrador' ? 'selected' : '' ?>>
                                Administrador
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="estado" class="form-label">
                            Estado
                            <span class="form-label-required">*</span>
                        </label>
                        <select id="estado" name="estado" class="form-control form-select" required>
                            <option value="activo" <?= $user['estado'] === 'activo' ? 'selected' : '' ?>>
                                Activo
                            </option>
                            <option value="inactivo" <?= $user['estado'] === 'inactivo' ? 'selected' : '' ?>>
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
                        <span class="info-box-label">Usuario creado:</span>
                        <span><?= formatDate($user['fecha_creacion'], 'd/m/Y H:i') ?></span>
                    </div>
                    <div class="info-box-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        <span class="info-box-label">Última modificación:</span>
                        <span><?= formatDate($user['fecha_modificacion'], 'd/m/Y H:i') ?></span>
                    </div>
                    <div class="info-box-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        <span class="info-box-label">Último acceso:</span>
                        <span><?= $user['ultimo_acceso'] ? formatDate($user['ultimo_acceso'], 'd/m/Y H:i') : 'Nunca' ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="<?= baseUrl('users') ?>" class="btn-back">
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

<!-- Alert Modal -->
<div id="alertModal" class="alert-modal-overlay">
    <div class="alert-modal">
        <div class="alert-modal-content">
            <div class="alert-modal-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <h3 class="alert-modal-title" id="alertModalTitle">Atención</h3>
            <p class="alert-modal-message" id="alertModalMessage"></p>
            <button class="alert-modal-btn" onclick="closeAlertModal()">Entendido</button>
        </div>
    </div>
</div>

<script>
// Mostrar modal de alerta
function showAlertModal(message, title = 'Atención') {
    document.getElementById('alertModalTitle').textContent = title;
    document.getElementById('alertModalMessage').textContent = message;
    document.getElementById('alertModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Cerrar modal de alerta
function closeAlertModal() {
    document.getElementById('alertModal').classList.remove('active');
    document.body.style.overflow = '';
}

// Cerrar modal al hacer click fuera
document.getElementById('alertModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAlertModal();
    }
});

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAlertModal();
    }
});

// Generar contraseña segura
function generateSecurePassword() {
    const length = 16;
    const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const lowercase = 'abcdefghijklmnopqrstuvwxyz';
    const numbers = '0123456789';
    const special = '!@#$%^&*()_+-=[]{}|;:,.<>?';

    const allChars = uppercase + lowercase + numbers + special;
    let password = '';

    // Asegurar al menos un carácter de cada tipo
    password += uppercase[Math.floor(Math.random() * uppercase.length)];
    password += lowercase[Math.floor(Math.random() * lowercase.length)];
    password += numbers[Math.floor(Math.random() * numbers.length)];
    password += special[Math.floor(Math.random() * special.length)];

    // Completar el resto de la contraseña
    for (let i = password.length; i < length; i++) {
        password += allChars[Math.floor(Math.random() * allChars.length)];
    }

    // Mezclar los caracteres
    password = password.split('').sort(() => Math.random() - 0.5).join('');

    // Establecer la contraseña en ambos campos
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirm');

    passwordInput.type = 'text';
    passwordInput.value = password;
    passwordConfirmInput.value = password;

    // Actualizar indicador de fortaleza
    checkPasswordStrength(password);

    // Volver a tipo password después de 3 segundos
    setTimeout(() => {
        passwordInput.type = 'password';
    }, 3000);
}

// Copiar contraseña al portapapeles
function copyPassword(event) {
    event.preventDefault();
    const passwordInput = document.getElementById('password');
    const copyBtn = event.currentTarget;

    if (!passwordInput.value) {
        showAlertModal('Debe generar o ingresar una contraseña antes de copiarla.', 'Campo vacío');
        return;
    }

    // Método de copia - funciona en HTTP y HTTPS
    const textToCopy = passwordInput.value;

    // Crear un elemento temporal
    const tempInput = document.createElement('input');
    tempInput.style.position = 'absolute';
    tempInput.style.left = '-9999px';
    tempInput.value = textToCopy;
    document.body.appendChild(tempInput);

    // Seleccionar y copiar
    tempInput.select();
    tempInput.setSelectionRange(0, 99999); // Para móviles

    try {
        const successful = document.execCommand('copy');
        document.body.removeChild(tempInput);

        if (successful) {
            // Cambiar icono a check
            copyBtn.classList.add('copied');
            const svg = copyBtn.querySelector('svg');
            const originalSvg = svg.innerHTML;
            svg.innerHTML = '<polyline points="20 6 9 17 4 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';

            // Restaurar después de 2 segundos
            setTimeout(() => {
                copyBtn.classList.remove('copied');
                svg.innerHTML = originalSvg;
            }, 2000);
        } else {
            showAlertModal('No se pudo copiar la contraseña. Por favor, inténtelo de nuevo.', 'Error al copiar');
        }
    } catch (err) {
        document.body.removeChild(tempInput);
        showAlertModal('Hubo un error al copiar la contraseña. Por favor, inténtelo de nuevo.', 'Error');
    }
}

// Verificar fortaleza de contraseña
function checkPasswordStrength(password) {
    const strengthContainer = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('strengthText');
    const strengthBarFill = document.getElementById('strengthBarFill');

    if (!password) {
        strengthContainer.classList.remove('active');
        return;
    }

    strengthContainer.classList.add('active');

    // Verificar requisitos
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*()_+\-=\[\]{}|;:,.<>?]/.test(password)
    };

    // Actualizar visualización de requisitos
    Object.keys(requirements).forEach(req => {
        const element = document.getElementById(`req-${req}`);
        if (requirements[req]) {
            element.classList.add('met');
            element.querySelector('svg').innerHTML = '<polyline points="20 6 9 17 4 12"/>';
        } else {
            element.classList.remove('met');
            element.querySelector('svg').innerHTML = '<circle cx="12" cy="12" r="10"/>';
        }
    });

    // Calcular nivel de fortaleza
    const metRequirements = Object.values(requirements).filter(Boolean).length;

    // Eliminar clases anteriores
    strengthContainer.classList.remove('strength-weak', 'strength-medium', 'strength-strong');

    if (metRequirements <= 2) {
        strengthContainer.classList.add('strength-weak');
        strengthText.textContent = 'Débil';
    } else if (metRequirements <= 4) {
        strengthContainer.classList.add('strength-medium');
        strengthText.textContent = 'Media';
    } else {
        strengthContainer.classList.add('strength-strong');
        strengthText.textContent = 'Fuerte';
    }
}

// Event listener para el campo de contraseña
document.getElementById('password').addEventListener('input', function(e) {
    checkPasswordStrength(e.target.value);
});

// Event listener para el campo de confirmación
document.getElementById('password_confirm').addEventListener('input', function(e) {
    const password = document.getElementById('password').value;
    const confirm = e.target.value;

    if (confirm && password !== confirm) {
        e.target.setCustomValidity('Las contraseñas no coinciden');
    } else {
        e.target.setCustomValidity('');
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

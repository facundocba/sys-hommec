<?php
$title = 'Configuración del Sistema';
include __DIR__ . '/../layouts/header.php';
?>

<style>
    .settings-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .settings-card {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(255, 255, 255, 0.92) 100%);
        backdrop-filter: blur(30px);
        border-radius: 24px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow:
            0 10px 40px rgba(56, 73, 89, 0.08),
            0 2px 8px rgba(56, 73, 89, 0.04);
        border: 1.5px solid rgba(136, 219, 242, 0.2);
        position: relative;
        overflow: hidden;
    }

    .settings-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);
    }

    .settings-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(136, 219, 242, 0.2);
    }

    .settings-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 8px 24px rgba(106, 137, 167, 0.35);
    }

    .settings-icon svg {
        width: 24px;
        height: 24px;
        stroke: white;
    }

    .settings-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--stormy-dark);
        margin: 0;
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .form-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--stormy-dark);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-section-title::before {
        content: '';
        width: 4px;
        height: 20px;
        background: linear-gradient(135deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);
        border-radius: 2px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--stormy-dark);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid rgba(136, 219, 242, 0.3);
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus {
        border-color: var(--stormy-cyan);
        box-shadow: 0 0 0 3px rgba(136, 219, 242, 0.1);
        outline: none;
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--stormy-blue);
        margin-top: 0.25rem;
        opacity: 0.8;
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: linear-gradient(135deg, rgba(189, 221, 252, 0.1) 0%, rgba(136, 219, 242, 0.05) 100%);
        border-radius: 12px;
        border: 1px solid rgba(136, 219, 242, 0.15);
        margin-bottom: 1rem;
    }

    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid var(--stormy-cyan);
        border-radius: 6px;
        cursor: pointer;
        margin: 0;
    }

    .form-check-input:checked {
        background-color: var(--stormy-cyan);
        border-color: var(--stormy-cyan);
    }

    .form-check-label {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--stormy-dark);
        cursor: pointer;
        margin: 0;
    }

    .row {
        display: flex;
        gap: 1.5rem;
        margin: 0 -0.75rem;
    }

    .col-md-6 {
        flex: 1;
        padding: 0 0.75rem;
    }

    .btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid rgba(136, 219, 242, 0.2);
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border: 1px solid;
    }

    .alert-warning {
        background: rgba(251, 191, 36, 0.1);
        border-color: rgba(251, 191, 36, 0.3);
        color: #92400e;
    }

    .alert-icon {
        width: 24px;
        height: 24px;
    }

    @media (max-width: 768px) {
        .row {
            flex-direction: column;
        }

        .settings-card {
            padding: 1.5rem;
        }
    }
</style>

<div class="settings-container">
    <div class="settings-card">
        <div class="settings-header">
            <div class="settings-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12 1v6m0 6v6m5.5-13.5l-4.24 4.24m-2.52 2.52L6.5 16.5m11-11l-4.24 4.24m-2.52 2.52L6.5 6.5m11 11l-4.24-4.24m-2.52-2.52L6.5 16.5"/>
                </svg>
            </div>
            <h1 class="settings-title">Configuración del Sistema</h1>
        </div>

        <div class="alert alert-warning">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <div>
                <strong>Importante:</strong> Los cambios en la configuración tomarán efecto en el próximo inicio de sesión. Algunos cambios pueden requerir reiniciar el servidor web.
            </div>
        </div>

        <form method="POST" action="<?= baseUrl('settings/update') ?>">
            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

            <!-- Configuración General -->
            <div class="form-section">
                <h3 class="form-section-title">Configuración General</h3>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="app_name">Nombre de la Aplicación</label>
                            <input type="text" class="form-control" id="app_name" name="app_name"
                                   value="<?= htmlspecialchars($envVars['APP_NAME'] ?? 'MedFlow') ?>" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="app_url">URL de la Aplicación</label>
                            <input type="url" class="form-control" id="app_url" name="app_url"
                                   value="<?= htmlspecialchars($envVars['APP_URL'] ?? 'http://localhost/MedFlow') ?>" required>
                            <small class="form-text">URL base del sistema (sin barra final)</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="app_timezone">Zona Horaria</label>
                            <select class="form-control" id="app_timezone" name="app_timezone" required>
                                <option value="America/Argentina/Buenos_Aires" <?= ($envVars['APP_TIMEZONE'] ?? '') === 'America/Argentina/Buenos_Aires' ? 'selected' : '' ?>>Argentina - Buenos Aires</option>
                                <option value="America/Santiago" <?= ($envVars['APP_TIMEZONE'] ?? '') === 'America/Santiago' ? 'selected' : '' ?>>Chile - Santiago</option>
                                <option value="America/Bogota" <?= ($envVars['APP_TIMEZONE'] ?? '') === 'America/Bogota' ? 'selected' : '' ?>>Colombia - Bogotá</option>
                                <option value="America/Lima" <?= ($envVars['APP_TIMEZONE'] ?? '') === 'America/Lima' ? 'selected' : '' ?>>Perú - Lima</option>
                                <option value="America/Mexico_City" <?= ($envVars['APP_TIMEZONE'] ?? '') === 'America/Mexico_City' ? 'selected' : '' ?>>México - Ciudad de México</option>
                                <option value="America/Montevideo" <?= ($envVars['APP_TIMEZONE'] ?? '') === 'America/Montevideo' ? 'selected' : '' ?>>Uruguay - Montevideo</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="session_lifetime">Duración de Sesión (segundos)</label>
                            <input type="number" class="form-control" id="session_lifetime" name="session_lifetime"
                                   value="<?= htmlspecialchars($envVars['SESSION_LIFETIME'] ?? '7200') ?>" min="300" max="86400" required>
                            <small class="form-text">Tiempo antes de cerrar sesión automáticamente (7200 = 2 horas)</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seguridad -->
            <div class="form-section">
                <h3 class="form-section-title">Seguridad</h3>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="password_min_length">Longitud Mínima de Contraseña</label>
                            <input type="number" class="form-control" id="password_min_length" name="password_min_length"
                                   value="<?= htmlspecialchars($envVars['PASSWORD_MIN_LENGTH'] ?? '8') ?>" min="6" max="20" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notificaciones -->
            <div class="form-section">
                <h3 class="form-section-title">Notificaciones</h3>

                <div class="alert alert-info" style="background: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.3); color: #1e40af; margin-bottom: 1.5rem;">
                    <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    <div>
                        Si experimentas problemas con el envío de emails (timeouts o errores), desactiva las notificaciones por email hasta resolver la configuración SMTP.
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="patient_expiration_alert_days">Días de Alerta de Vencimiento</label>
                            <input type="number" class="form-control" id="patient_expiration_alert_days" name="patient_expiration_alert_days"
                                   value="<?= htmlspecialchars($envVars['PATIENT_EXPIRATION_ALERT_DAYS'] ?? '7') ?>" min="1" max="30" required>
                            <small class="form-text">Días antes del vencimiento para mostrar alerta</small>
                        </div>
                    </div>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="enable_email_notifications" name="enable_email_notifications"
                           <?= ($envVars['ENABLE_EMAIL_NOTIFICATIONS'] ?? 'true') === 'true' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="enable_email_notifications">
                        <strong>Habilitar Notificaciones por Email</strong>
                        <small style="display: block; color: var(--stormy-blue); opacity: 0.8; margin-top: 0.25rem;">
                            Desactiva esto si tienes problemas con el servidor SMTP
                        </small>
                    </label>
                </div>
            </div>

            <!-- Configuración de Email -->
            <div class="form-section">
                <h3 class="form-section-title">Configuración de Email (SMTP)</h3>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="mail_host">Servidor SMTP</label>
                            <input type="text" class="form-control" id="mail_host" name="mail_host"
                                   value="<?= htmlspecialchars($envVars['MAIL_HOST'] ?? 'smtp.gmail.com') ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="mail_port">Puerto SMTP</label>
                            <input type="number" class="form-control" id="mail_port" name="mail_port"
                                   value="<?= htmlspecialchars($envVars['MAIL_PORT'] ?? '587') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="mail_username">Usuario SMTP</label>
                            <input type="text" class="form-control" id="mail_username" name="mail_username"
                                   value="<?= htmlspecialchars($envVars['MAIL_USERNAME'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="mail_password">Contraseña SMTP</label>
                            <input type="password" class="form-control" id="mail_password" name="mail_password"
                                   value="<?= htmlspecialchars($envVars['MAIL_PASSWORD'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="mail_encryption">Encriptación</label>
                            <select class="form-control" id="mail_encryption" name="mail_encryption">
                                <option value="tls" <?= ($envVars['MAIL_ENCRYPTION'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
                                <option value="ssl" <?= ($envVars['MAIL_ENCRYPTION'] ?? 'tls') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="mail_from_address">Email Remitente</label>
                            <input type="email" class="form-control" id="mail_from_address" name="mail_from_address"
                                   value="<?= htmlspecialchars($envVars['MAIL_FROM_ADDRESS'] ?? 'noreply@medflow.com') ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="mail_from_name">Nombre Remitente</label>
                            <input type="text" class="form-control" id="mail_from_name" name="mail_from_name"
                                   value="<?= htmlspecialchars($envVars['MAIL_FROM_NAME'] ?? 'MedFlow Sistema') ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Archivos -->
            <div class="form-section">
                <h3 class="form-section-title">Configuración de Archivos</h3>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="upload_max_size">Tamaño Máximo de Archivo (bytes)</label>
                            <input type="number" class="form-control" id="upload_max_size" name="upload_max_size"
                                   value="<?= htmlspecialchars($envVars['UPLOAD_MAX_SIZE'] ?? '20971520') ?>" min="1048576" required>
                            <small class="form-text">20971520 bytes = 20 MB</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logs -->
            <div class="form-section">
                <h3 class="form-section-title">Registros del Sistema</h3>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="log_enabled" name="log_enabled"
                           <?= ($envVars['LOG_ENABLED'] ?? 'true') === 'true' ? 'checked' : '' ?>>
                    <label class="form-check-label" for="log_enabled">
                        Habilitar Registro de Actividades
                    </label>
                </div>
            </div>

            <!-- Botones -->
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i>
                    Guardar Cambios
                </button>
                <a href="<?= baseUrl('dashboard') ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

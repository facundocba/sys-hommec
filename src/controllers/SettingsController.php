<?php
/**
 * Settings Controller
 * MedFlow - Sistema Administrativo Médico
 */

require_once __DIR__ . '/../middleware/Auth.php';

class SettingsController {

    public function __construct() {
        Auth::requireAuth();
        Auth::checkTimeout();

        // Solo admins pueden acceder
        if (!isAdmin()) {
            setFlash('error', 'No tienes permisos para acceder a esta sección.');
            redirect(baseUrl('dashboard'));
        }
    }

    /**
     * Mostrar página de configuración
     */
    public function index() {
        $config = require __DIR__ . '/../../config/config.php';

        // Leer archivo .env
        $envFile = __DIR__ . '/../../.env';
        $envVars = [];

        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || strpos($line, '#') === 0) {
                    continue;
                }
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $envVars[trim($key)] = trim($value);
                }
            }
        }

        $title = 'Configuración del Sistema';
        include __DIR__ . '/../../views/settings/index.php';
    }

    /**
     * Actualizar configuración
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(baseUrl('settings'));
            return;
        }

        // Validar CSRF
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            setFlash('error', 'Token de seguridad inválido.');
            redirect(baseUrl('settings'));
            return;
        }

        $envFile = __DIR__ . '/../../.env';

        // Leer archivo .env actual
        $envVars = [];
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || strpos($line, '#') === 0) {
                    continue;
                }
                if (strpos($line, '=') !== false) {
                    list($key, $value) = explode('=', $line, 2);
                    $envVars[trim($key)] = trim($value);
                }
            }
        }

        // Actualizar valores
        $envVars['APP_NAME'] = $_POST['app_name'] ?? 'MedFlow';
        $envVars['APP_URL'] = $_POST['app_url'] ?? 'http://localhost/MedFlow';
        $envVars['APP_TIMEZONE'] = $_POST['app_timezone'] ?? 'America/Argentina/Buenos_Aires';
        $envVars['SESSION_LIFETIME'] = $_POST['session_lifetime'] ?? '7200';
        $envVars['PASSWORD_MIN_LENGTH'] = $_POST['password_min_length'] ?? '8';
        $envVars['PATIENT_EXPIRATION_ALERT_DAYS'] = $_POST['patient_expiration_alert_days'] ?? '7';
        $envVars['UPLOAD_MAX_SIZE'] = $_POST['upload_max_size'] ?? '20971520';
        $envVars['ENABLE_EMAIL_NOTIFICATIONS'] = isset($_POST['enable_email_notifications']) ? 'true' : 'false';
        $envVars['LOG_ENABLED'] = isset($_POST['log_enabled']) ? 'true' : 'false';

        // Email settings
        $envVars['MAIL_HOST'] = $_POST['mail_host'] ?? 'smtp.gmail.com';
        $envVars['MAIL_PORT'] = $_POST['mail_port'] ?? '587';
        $envVars['MAIL_USERNAME'] = $_POST['mail_username'] ?? '';
        $envVars['MAIL_PASSWORD'] = $_POST['mail_password'] ?? '';
        $envVars['MAIL_ENCRYPTION'] = $_POST['mail_encryption'] ?? 'tls';
        $envVars['MAIL_FROM_ADDRESS'] = $_POST['mail_from_address'] ?? 'noreply@medflow.com';
        $envVars['MAIL_FROM_NAME'] = $_POST['mail_from_name'] ?? 'MedFlow Sistema';

        // Escribir archivo .env
        $content = "# MedFlow Configuration\n";
        $content .= "# Última actualización: " . date('Y-m-d H:i:s') . "\n\n";

        $content .= "# Application\n";
        $content .= "APP_NAME={$envVars['APP_NAME']}\n";
        $content .= "APP_URL={$envVars['APP_URL']}\n";
        $content .= "APP_ENV=" . ($envVars['APP_ENV'] ?? 'production') . "\n";
        $content .= "APP_DEBUG=" . ($envVars['APP_DEBUG'] ?? 'false') . "\n";
        $content .= "APP_TIMEZONE={$envVars['APP_TIMEZONE']}\n\n";

        $content .= "# Database\n";
        $content .= "DB_HOST=" . ($envVars['DB_HOST'] ?? 'localhost') . "\n";
        $content .= "DB_NAME=" . ($envVars['DB_NAME'] ?? 'medflow') . "\n";
        $content .= "DB_USER=" . ($envVars['DB_USER'] ?? 'root') . "\n";
        $content .= "DB_PASS=" . ($envVars['DB_PASS'] ?? '') . "\n";
        $content .= "DB_CHARSET=" . ($envVars['DB_CHARSET'] ?? 'utf8mb4') . "\n\n";

        $content .= "# Session\n";
        $content .= "SESSION_LIFETIME={$envVars['SESSION_LIFETIME']}\n";
        $content .= "SESSION_NAME=" . ($envVars['SESSION_NAME'] ?? 'MEDFLOW_SESSION') . "\n\n";

        $content .= "# Security\n";
        $content .= "CSRF_TOKEN_NAME=" . ($envVars['CSRF_TOKEN_NAME'] ?? 'csrf_token') . "\n";
        $content .= "PASSWORD_MIN_LENGTH={$envVars['PASSWORD_MIN_LENGTH']}\n\n";

        $content .= "# Email\n";
        $content .= "MAIL_HOST={$envVars['MAIL_HOST']}\n";
        $content .= "MAIL_PORT={$envVars['MAIL_PORT']}\n";
        $content .= "MAIL_USERNAME={$envVars['MAIL_USERNAME']}\n";
        // Escapar contraseña con comillas si tiene caracteres especiales
        $password = $envVars['MAIL_PASSWORD'];
        if (preg_match('/[&!@#$%^*()+=\[\]{};:\'",<>?\/\\\\|`~]/', $password)) {
            $password = '"' . str_replace('"', '\\"', $password) . '"';
        }
        $content .= "MAIL_PASSWORD={$password}\n";
        $content .= "MAIL_ENCRYPTION={$envVars['MAIL_ENCRYPTION']}\n";
        $content .= "MAIL_FROM_ADDRESS={$envVars['MAIL_FROM_ADDRESS']}\n";
        $content .= "MAIL_FROM_NAME=\"{$envVars['MAIL_FROM_NAME']}\"\n\n";

        $content .= "# Uploads\n";
        $content .= "UPLOAD_MAX_SIZE={$envVars['UPLOAD_MAX_SIZE']}\n";
        $content .= "ALLOWED_FILE_TYPES=" . ($envVars['ALLOWED_FILE_TYPES'] ?? 'pdf,doc,docx,jpg,jpeg,png,gif') . "\n\n";

        $content .= "# Notifications\n";
        $content .= "PATIENT_EXPIRATION_ALERT_DAYS={$envVars['PATIENT_EXPIRATION_ALERT_DAYS']}\n";
        $content .= "ENABLE_EMAIL_NOTIFICATIONS={$envVars['ENABLE_EMAIL_NOTIFICATIONS']}\n\n";

        $content .= "# Logs\n";
        $content .= "LOG_ENABLED={$envVars['LOG_ENABLED']}\n";
        $content .= "LOG_LEVEL=" . ($envVars['LOG_LEVEL'] ?? 'debug') . "\n";

        // Intentar escribir el archivo
        if (file_put_contents($envFile, $content)) {
            logActivity($_SESSION['user_id'], 'update', 'settings', 'Configuración del sistema actualizada');
            setFlash('success', 'Configuración actualizada exitosamente. Los cambios tomarán efecto en el próximo inicio de sesión.');
        } else {
            setFlash('error', 'Error al guardar la configuración. Verifica los permisos del archivo .env');
        }

        redirect(baseUrl('settings'));
    }
}

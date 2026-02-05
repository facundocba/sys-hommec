<?php
/**
 * Global Configuration File
 * MedFlow - Sistema Administrativo Médico
 */

// Load environment variables
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);

        // Skip comments and empty lines
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }

        // Parse line
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Remove quotes from value if present
            if (strlen($value) > 1 && $value[0] === '"' && $value[strlen($value) - 1] === '"') {
                $value = substr($value, 1, -1);
                $value = str_replace('\\"', '"', $value); // Unescape quotes
            }

            if (!empty($key)) {
                $_ENV[$key] = $value;
            }
        }
    }
}

// Helper function to get environment variables
if (!function_exists('env')) {
    function env($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

// Application configuration
return [
    'app' => [
        'name' => env('APP_NAME', 'MedFlow'),
        'url' => env('APP_URL', 'http://localhost/MedFlow'),
        'env' => env('APP_ENV', 'production'),
        'debug' => env('APP_DEBUG', 'false') === 'true',
        'timezone' => env('APP_TIMEZONE', 'America/Argentina/Buenos_Aires')
    ],

    'database' => [
        'host' => env('DB_HOST', 'localhost'),
        'name' => env('DB_NAME', 'medflow'),
        'user' => env('DB_USER', 'root'),
        'password' => env('DB_PASS', ''),
        'charset' => env('DB_CHARSET', 'utf8mb4')
    ],

    'session' => [
        'lifetime' => (int) env('SESSION_LIFETIME', 7200),
        'name' => env('SESSION_NAME', 'MEDFLOW_SESSION'),
        'path' => '/',
        'domain' => '',
        'secure' => env('APP_ENV', 'production') === 'production',
        'httponly' => true,
        'samesite' => 'Strict'
    ],

    'security' => [
        'csrf_token_name' => env('CSRF_TOKEN_NAME', 'csrf_token'),
        'password_min_length' => (int) env('PASSWORD_MIN_LENGTH', 8)
    ],

    'mail' => [
        'host' => env('MAIL_HOST', 'smtp.gmail.com'),
        'port' => (int) env('MAIL_PORT', 587),
        'username' => env('MAIL_USERNAME', ''),
        'password' => env('MAIL_PASSWORD', ''),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
        'from' => [
            'address' => env('MAIL_FROM_ADDRESS', 'noreply@medflow.com'),
            'name' => env('MAIL_FROM_NAME', 'MedFlow Sistema')
        ]
    ],

    'upload' => [
        'max_size' => (int) env('UPLOAD_MAX_SIZE', 20971520), // 20MB
        'allowed_types' => explode(',', env('ALLOWED_FILE_TYPES', 'pdf,doc,docx,jpg,jpeg,png,gif')),
        'path' => __DIR__ . '/../public/uploads/patients/'
    ],

    'notifications' => [
        'patient_expiration_alert_days' => (int) env('PATIENT_EXPIRATION_ALERT_DAYS', 7),
        'enable_email' => env('ENABLE_EMAIL_NOTIFICATIONS', 'true') === 'true'
    ],

    'logs' => [
        'enabled' => env('LOG_ENABLED', 'true') === 'true',
        'path' => __DIR__ . '/../logs/app.log',
        'level' => env('LOG_LEVEL', 'debug')
    ],

    'paths' => [
        'root' => dirname(__DIR__),
        'public' => dirname(__DIR__) . '/public',
        'views' => dirname(__DIR__) . '/views',
        'uploads' => dirname(__DIR__) . '/public/uploads',
        'logs' => dirname(__DIR__) . '/logs'
    ]
];

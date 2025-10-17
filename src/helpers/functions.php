<?php
/**
 * Helper Functions
 * MedFlow - Sistema Administrativo Médico
 */

/**
 * Sanitize input data
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to a specific URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user data
 */
function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

/**
 * Check if user has specific role
 */
function hasRole($role) {
    return isLoggedIn() && ($_SESSION['user']['rol'] ?? '') === $role;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return hasRole('administrador');
}

/**
 * Check if user is coordinator
 */
function isCoordinator() {
    return hasRole('coordinador');
}

/**
 * Flash message system
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Format date for display
 */
function formatDate($date, $format = 'd/m/Y') {
    if (empty($date)) return '-';
    $dt = new DateTime($date);
    return $dt->format($format);
}

/**
 * Format currency
 */
function formatCurrency($amount) {
    return '$' . number_format($amount, 2, ',', '.');
}

/**
 * Calculate days until date
 */
function daysUntil($date) {
    if (empty($date)) return null;

    $target = new DateTime($date);
    $now = new DateTime();
    $diff = $now->diff($target);

    return $diff->invert ? -$diff->days : $diff->days;
}

/**
 * Check if patient is expiring soon
 */
function isExpiringSoon($date, $alertDays = 7) {
    $days = daysUntil($date);
    return $days !== null && $days >= 0 && $days <= $alertDays;
}

/**
 * Check if patient is expired
 */
function isExpired($date) {
    $days = daysUntil($date);
    return $days !== null && $days < 0;
}

/**
 * Generate random filename
 */
function generateFileName($originalName) {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $extension;
}

/**
 * Format file size
 */
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

/**
 * Log activity
 */
function logActivity($userId, $action, $module, $description = null, $ipAddress = null) {
    try {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO logs_actividad (id_usuario, accion, modulo, descripcion, ip_address)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $userId,
            $action,
            $module,
            $description,
            $ipAddress ?? $_SERVER['REMOTE_ADDR']
        ]);

    } catch (Exception $e) {
        error_log("Log Activity Error: " . $e->getMessage());
    }
}

/**
 * Write to log file
 */
function writeLog($message, $level = 'info') {
    $config = require __DIR__ . '/../../config/config.php';

    if (!$config['logs']['enabled']) return;

    $logFile = $config['logs']['path'];
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;

    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

/**
 * JSON response helper
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Validate email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Get base URL
 */
function baseUrl($path = '') {
    // Auto-detect base URL
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $baseUrl = $protocol . '://' . $host;

    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}

/**
 * Get asset URL
 */
function asset($path) {
    // Return relative path from public folder
    return '/assets/' . ltrim($path, '/');
}

/**
 * Include view
 */
function view($viewPath, $data = []) {
    extract($data);
    $config = require __DIR__ . '/../../config/config.php';
    $viewFile = $config['paths']['views'] . '/' . $viewPath . '.php';

    if (file_exists($viewFile)) {
        require $viewFile;
    } else {
        die("View not found: {$viewPath}");
    }
}

/**
 * Get unread notifications count for current user
 */
function getUnreadNotificationsCount() {
    if (!isLoggedIn()) {
        return 0;
    }

    try {
        require_once __DIR__ . '/../models/Notification.php';
        $notificationModel = new Notification();
        return $notificationModel->countUnread($_SESSION['user_id']);
    } catch (Exception $e) {
        error_log("Error getting unread notifications: " . $e->getMessage());
        return 0;
    }
}

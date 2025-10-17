<?php
/**
 * Authentication Middleware
 * MedFlow - Sistema Administrativo Médico
 */

class Auth {

    /**
     * Check if user is authenticated
     */
    public static function check() {
        return isLoggedIn();
    }

    /**
     * Require authentication
     */
    public static function requireAuth() {
        if (!self::check()) {
            setFlash('danger', 'Debe iniciar sesión para acceder a esta página.');
            redirect(baseUrl('login'));
        }
    }

    /**
     * Require specific role
     */
    public static function requireRole($role) {
        self::requireAuth();

        if (!hasRole($role)) {
            setFlash('danger', 'No tiene permisos para acceder a esta página.');
            redirect(baseUrl('dashboard'));
        }
    }

    /**
     * Require admin role
     */
    public static function requireAdmin() {
        self::requireRole('administrador');
    }

    /**
     * Check session timeout
     */
    public static function checkTimeout() {
        $config = require __DIR__ . '/../../config/config.php';
        $lifetime = $config['session']['lifetime'];

        if (isset($_SESSION['last_activity'])) {
            $elapsed = time() - $_SESSION['last_activity'];

            if ($elapsed > $lifetime) {
                self::logout();
                setFlash('warning', 'Su sesión ha expirado por inactividad.');
                redirect(baseUrl('login'));
            }
        }

        $_SESSION['last_activity'] = time();
    }

    /**
     * Login user
     */
    public static function login($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nombre' => $user['nombre'],
            'email' => $user['email'],
            'rol' => $user['rol']
        ];
        $_SESSION['last_activity'] = time();
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];

        // Update last access
        $userModel = new User();
        $userModel->updateLastAccess($user['id']);

        // Create session record
        self::createSessionRecord($user['id']);

        // Log activity
        logActivity($user['id'], 'login', 'auth', 'Usuario inició sesión');
    }

    /**
     * Logout user
     */
    public static function logout() {
        if (isset($_SESSION['user_id'])) {
            logActivity($_SESSION['user_id'], 'logout', 'auth', 'Usuario cerró sesión');
            self::destroySessionRecord();
        }

        session_unset();
        session_destroy();
    }

    /**
     * Create session record in database
     */
    private static function createSessionRecord($userId) {
        try {
            $db = Database::getInstance()->getConnection();
            $config = require __DIR__ . '/../../config/config.php';

            $sessionId = session_id();
            $token = bin2hex(random_bytes(32));
            $expirationTime = time() + $config['session']['lifetime'];

            $stmt = $db->prepare("
                INSERT INTO sesiones (id, id_usuario, ip_address, user_agent, token, fecha_expiracion)
                VALUES (?, ?, ?, ?, ?, FROM_UNIXTIME(?))
                ON DUPLICATE KEY UPDATE
                    ip_address = VALUES(ip_address),
                    user_agent = VALUES(user_agent),
                    token = VALUES(token),
                    fecha_expiracion = VALUES(fecha_expiracion),
                    ultima_actividad = CURRENT_TIMESTAMP
            ");

            $stmt->execute([
                $sessionId,
                $userId,
                $_SERVER['REMOTE_ADDR'],
                $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                $token,
                $expirationTime
            ]);

            $_SESSION['session_token'] = $token;

        } catch (PDOException $e) {
            error_log("Session Record Error: " . $e->getMessage());
        }
    }

    /**
     * Destroy session record
     */
    private static function destroySessionRecord() {
        try {
            $db = Database::getInstance()->getConnection();
            $sessionId = session_id();

            $stmt = $db->prepare("DELETE FROM sesiones WHERE id = ?");
            $stmt->execute([$sessionId]);

        } catch (PDOException $e) {
            error_log("Session Destroy Error: " . $e->getMessage());
        }
    }

    /**
     * Clean expired sessions
     */
    public static function cleanExpiredSessions() {
        try {
            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("DELETE FROM sesiones WHERE fecha_expiracion < NOW()");
            $stmt->execute();

        } catch (PDOException $e) {
            error_log("Clean Sessions Error: " . $e->getMessage());
        }
    }

    /**
     * Redirect if already authenticated
     */
    public static function redirectIfAuthenticated() {
        if (self::check()) {
            redirect(baseUrl('dashboard'));
        }
    }
}

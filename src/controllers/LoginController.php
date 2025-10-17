<?php
/**
 * Login Controller
 * MedFlow - Sistema Administrativo Médico
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../middleware/Auth.php';

class LoginController {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Show login form
     */
    public function index() {
        Auth::redirectIfAuthenticated();
        Auth::cleanExpiredSessions();

        view('auth/login');
    }

    /**
     * Process login
     */
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(baseUrl('login'));
        }

        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlash('danger', 'Token de seguridad inválido.');
            redirect(baseUrl('login'));
        }

        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate inputs
        if (empty($email) || empty($password)) {
            setFlash('danger', 'Por favor, complete todos los campos.');
            redirect(baseUrl('login'));
        }

        // Verify credentials
        $user = $this->userModel->verifyCredentials($email, $password);

        if (!$user) {
            setFlash('danger', 'Email o contraseña incorrectos.');
            logActivity(null, 'login_failed', 'auth', "Intento fallido de login: {$email}");
            redirect(baseUrl('login'));
        }

        // Login successful
        Auth::login($user);
        setFlash('success', "¡Bienvenido/a, {$user['nombre']}!");
        redirect(baseUrl('dashboard'));
    }

    /**
     * Logout
     */
    public function logout() {
        Auth::logout();
        setFlash('success', 'Sesión cerrada correctamente.');
        redirect(baseUrl('login'));
    }

    /**
     * Show forgot password form
     */
    public function forgotPassword() {
        Auth::redirectIfAuthenticated();
        view('auth/forgot-password');
    }

    /**
     * Process password recovery (placeholder)
     */
    public function recoverPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(baseUrl('login/forgotPassword'));
        }

        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlash('danger', 'Token de seguridad inválido.');
            redirect(baseUrl('login/forgotPassword'));
        }

        $email = sanitize($_POST['email'] ?? '');

        if (empty($email) || !validateEmail($email)) {
            setFlash('danger', 'Por favor, ingrese un email válido.');
            redirect(baseUrl('login/forgotPassword'));
        }

        $user = $this->userModel->findByEmail($email);

        // Always show success message for security (don't reveal if email exists)
        setFlash('success', 'Si el email existe en nuestro sistema, recibirá instrucciones para recuperar su contraseña.');

        // TODO: Implement email sending with password reset link
        if ($user) {
            writeLog("Password recovery requested for: {$email}", 'info');
        }

        redirect(baseUrl('login'));
    }
}

<?php
/**
 * Users Controller (Admin only)
 * MedFlow - Sistema Administrativo Médico
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../middleware/Auth.php';

class UsersController {

    private $userModel;

    public function __construct() {
        Auth::requireAdmin();
        Auth::checkTimeout();
        $this->userModel = new User();
    }

    /**
     * List all users
     */
    public function index() {
        $search = $_GET['search'] ?? '';
        $rol = $_GET['rol'] ?? '';
        $estado = $_GET['estado'] ?? '';

        $filters = [
            'search' => $search,
            'rol' => $rol,
            'estado' => $estado
        ];

        $users = $this->userModel->getAll($filters);

        $data = [
            'title' => 'Gestión de Usuarios',
            'users' => $users,
            'filters' => $filters,
            'notificaciones_pendientes' => 0 // TODO: Get real count
        ];

        view('users/index', $data);
    }

    /**
     * Show create user form
     */
    public function create() {
        $data = [
            'title' => 'Crear Usuario',
            'notificaciones_pendientes' => 0
        ];

        view('users/create', $data);
    }

    /**
     * Store new user
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(baseUrl('users/create'));
        }

        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlash('danger', 'Token de seguridad inválido.');
            redirect(baseUrl('users/create'));
        }

        $nombre = sanitize($_POST['nombre'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $rol = sanitize($_POST['rol'] ?? 'coordinador');
        $estado = sanitize($_POST['estado'] ?? 'activo');

        // Validations
        $errors = [];

        if (empty($nombre)) {
            $errors[] = 'El nombre es requerido.';
        }

        if (empty($email) || !validateEmail($email)) {
            $errors[] = 'El email es inválido.';
        }

        if ($this->userModel->emailExists($email)) {
            $errors[] = 'El email ya está registrado.';
        }

        if (empty($password)) {
            $errors[] = 'La contraseña es requerida.';
        } elseif (strlen($password) < 8) {
            $errors[] = 'La contraseña debe tener al menos 8 caracteres.';
        }

        if ($password !== $password_confirm) {
            $errors[] = 'Las contraseñas no coinciden.';
        }

        if (!empty($errors)) {
            setFlash('danger', implode('<br>', $errors));
            redirect(baseUrl('users/create'));
        }

        // Create user
        $userId = $this->userModel->create([
            'nombre' => $nombre,
            'email' => $email,
            'password' => $password,
            'rol' => $rol,
            'estado' => $estado
        ]);

        if ($userId) {
            logActivity($_SESSION['user_id'], 'create_user', 'users', "Usuario creado: {$email}");
            setFlash('success', 'Usuario creado exitosamente.');
            redirect(baseUrl('users'));
        } else {
            setFlash('danger', 'Error al crear el usuario.');
            redirect(baseUrl('users/create'));
        }
    }

    /**
     * Show edit user form
     */
    public function edit($id) {
        $user = $this->userModel->findById($id, true); // Allow any status

        if (!$user) {
            setFlash('danger', 'Usuario no encontrado.');
            redirect(baseUrl('users'));
        }

        $data = [
            'title' => 'Editar Usuario',
            'user' => $user,
            'notificaciones_pendientes' => 0
        ];

        view('users/edit', $data);
    }

    /**
     * Update user
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(baseUrl('users'));
        }

        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlash('danger', 'Token de seguridad inválido.');
            redirect(baseUrl('users/edit/' . $id));
        }

        $user = $this->userModel->findById($id, true); // Allow any status

        if (!$user) {
            setFlash('danger', 'Usuario no encontrado.');
            redirect(baseUrl('users'));
        }

        $nombre = sanitize($_POST['nombre'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $rol = sanitize($_POST['rol'] ?? 'coordinador');
        $estado = sanitize($_POST['estado'] ?? 'activo');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        // Validations
        $errors = [];

        if (empty($nombre)) {
            $errors[] = 'El nombre es requerido.';
        }

        if (empty($email) || !validateEmail($email)) {
            $errors[] = 'El email es inválido.';
        }

        if ($this->userModel->emailExists($email, $id)) {
            $errors[] = 'El email ya está registrado.';
        }

        if (!empty($password)) {
            if (strlen($password) < 8) {
                $errors[] = 'La contraseña debe tener al menos 8 caracteres.';
            }

            if ($password !== $password_confirm) {
                $errors[] = 'Las contraseñas no coinciden.';
            }
        }

        if (!empty($errors)) {
            setFlash('danger', implode('<br>', $errors));
            redirect(baseUrl('users/edit/' . $id));
        }

        // Update user
        $updateData = [
            'nombre' => $nombre,
            'email' => $email,
            'rol' => $rol,
            'estado' => $estado
        ];

        $result = $this->userModel->update($id, $updateData);

        // Update password if provided
        if (!empty($password)) {
            $this->userModel->updatePassword($id, $password);
        }

        if ($result) {
            logActivity($_SESSION['user_id'], 'update_user', 'users', "Usuario actualizado: {$email}");
            setFlash('success', 'Usuario actualizado exitosamente.');
            redirect(baseUrl('users'));
        } else {
            setFlash('danger', 'Error al actualizar el usuario.');
            redirect(baseUrl('users/edit/' . $id));
        }
    }

    /**
     * Delete user (soft delete)
     */
    public function delete($id) {
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlash('danger', 'Token de seguridad inválido.');
            redirect(baseUrl('users'));
        }

        // Prevent self-deletion
        if ($id == $_SESSION['user_id']) {
            setFlash('danger', 'No puede eliminar su propio usuario.');
            redirect(baseUrl('users'));
        }

        $user = $this->userModel->findById($id, true); // Allow any status

        if (!$user) {
            setFlash('danger', 'Usuario no encontrado.');
            redirect(baseUrl('users'));
        }

        $result = $this->userModel->delete($id);

        if ($result) {
            logActivity($_SESSION['user_id'], 'delete_user', 'users', "Usuario desactivado: {$user['email']}");
            setFlash('success', 'Usuario desactivado exitosamente.');
        } else {
            setFlash('danger', 'Error al desactivar el usuario.');
        }

        redirect(baseUrl('users'));
    }
}

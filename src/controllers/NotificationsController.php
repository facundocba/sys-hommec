<?php

require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../middleware/Auth.php';

class NotificationsController
{
    private $notificationModel;

    public function __construct()
    {
        Auth::requireAuth();
        Auth::checkTimeout();
        $this->notificationModel = new Notification();
    }

    /**
     * Mostrar todas las notificaciones del usuario
     */
    public function index()
    {
        $filters = [
            'leida' => $_GET['leida'] ?? '',
            'tipo' => $_GET['tipo'] ?? ''
        ];

        $notificaciones = $this->notificationModel->getByUser($_SESSION['user_id'], $filters);

        $title = 'Notificaciones';
        include __DIR__ . '/../../views/notifications/index.php';
    }

    /**
     * Obtener conteo de notificaciones no leídas (para AJAX)
     */
    public function getUnreadCount()
    {
        header('Content-Type: application/json');

        $count = $this->notificationModel->countUnread($_SESSION['user_id']);

        echo json_encode(['count' => $count]);
        exit;
    }

    /**
     * Obtener notificaciones recientes (para AJAX)
     */
    public function getRecent()
    {
        header('Content-Type: application/json');

        $notificaciones = $this->notificationModel->getRecent($_SESSION['user_id'], 5);

        echo json_encode(['notifications' => $notificaciones]);
        exit;
    }

    /**
     * Marcar notificación como leída
     */
    public function markAsRead($id)
    {
        $notification = $this->notificationModel->getById($id);

        if (!$notification) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Notificación no encontrada']);
                exit;
            }

            setFlash('error', 'Notificación no encontrada.');
            redirect(baseUrl('notifications'));
            return;
        }

        // Verificar que la notificación es para este usuario
        if ($notification['id_usuario_destino'] !== null && $notification['id_usuario_destino'] != $_SESSION['user_id']) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
                exit;
            }

            setFlash('error', 'Acceso denegado.');
            redirect(baseUrl('notifications'));
            return;
        }

        if ($this->notificationModel->markAsRead($id)) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }

            // Si hay URL de redirección, ir ahí
            if (!empty($notification['id_paciente'])) {
                redirect(baseUrl('patients/view/' . $notification['id_paciente']));
            } else {
                redirect(baseUrl('notifications'));
            }
        } else {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Error al marcar como leída']);
                exit;
            }

            setFlash('error', 'Error al marcar como leída.');
            redirect(baseUrl('notifications'));
        }
    }

    /**
     * Marcar todas como leídas
     */
    public function markAllAsRead()
    {
        if ($this->notificationModel->markAllAsRead($_SESSION['user_id'])) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }

            setFlash('success', 'Todas las notificaciones marcadas como leídas.');
        } else {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false]);
                exit;
            }

            setFlash('error', 'Error al marcar las notificaciones.');
        }

        redirect(baseUrl('notifications'));
    }

    /**
     * Eliminar notificación
     */
    public function delete($id)
    {
        // Solo admin puede eliminar
        if (!hasRole('administrador')) {
            setFlash('error', 'No tiene permisos para realizar esta acción.');
            redirect(baseUrl('notifications'));
            return;
        }

        $this->validateCSRFToken();

        if ($this->notificationModel->delete($id)) {
            setFlash('success', 'Notificación eliminada.');
        } else {
            setFlash('error', 'Error al eliminar la notificación.');
        }

        redirect(baseUrl('notifications'));
    }

    /**
     * Validar CSRF token
     */
    private function validateCSRFToken()
    {
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            setFlash('error', 'Token de seguridad inválido.');
            redirect(baseUrl('notifications'));
            exit;
        }
    }
}

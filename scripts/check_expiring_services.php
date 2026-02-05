<?php
/**
 * Script para verificar prestaciones próximas a vencer
 * Se debe ejecutar diariamente mediante CRON
 *
 * Ejemplo de configuración CRON (ejecutar diariamente a las 8:00 AM):
 * 0 8 * * * /usr/bin/php /path/to/MedFlow/scripts/check_expiring_services.php
 *
 * En Windows con WAMP:
 * C:\wamp64\bin\php\php8.1.31\php.exe C:\wamp64\www\MedFlow\scripts\check_expiring_services.php
 */

// Configurar timezone
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Cargar dependencias
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/helpers/functions.php';
require_once __DIR__ . '/../src/models/PrestacionPaciente.php';
require_once __DIR__ . '/../src/models/Notification.php';
require_once __DIR__ . '/../src/models/User.php';
require_once __DIR__ . '/../src/services/EmailService.php';

echo "=== Script de Verificación de Vencimientos ===\n";
echo "Fecha y hora: " . date('Y-m-d H:i:s') . "\n\n";

try {
    // Cargar configuración
    $config = require __DIR__ . '/../config/config.php';
    $alertDays = $config['notifications']['patient_expiration_alert_days'];

    echo "Buscando prestaciones que vencen en {$alertDays} días o menos...\n";

    // Inicializar modelos
    $prestacionModel = new PrestacionPaciente();
    $notificationModel = new Notification();
    $userModel = new User();
    $emailService = new EmailService();

    // Obtener todas las prestaciones
    $prestaciones = $prestacionModel->getAll();

    $alertCount = 0;
    $notificationCount = 0;
    $emailCount = 0;

    foreach ($prestaciones as $prestacion) {
        // Verificar si está próxima a vencer
        if (!empty($prestacion['fecha_fin_prestacion'])) {
            $diasRestantes = daysUntil($prestacion['fecha_fin_prestacion']);

            // Si está en el rango de alerta (0 a alertDays días)
            if ($diasRestantes !== null && $diasRestantes >= 0 && $diasRestantes <= $alertDays) {
                $alertCount++;

                echo "\n[ALERTA] Prestación vence en {$diasRestantes} días:\n";
                echo "  - Paciente: {$prestacion['paciente_nombre']}\n";
                echo "  - Fecha vencimiento: " . formatDate($prestacion['fecha_fin_prestacion']) . "\n";

                // Verificar si ya se envió notificación para esta fecha de vencimiento
                // Para evitar spam, solo enviar una vez por día
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("
                    SELECT COUNT(*)
                    FROM notificaciones
                    WHERE tipo = 'paciente_vencimiento'
                    AND id_paciente = ?
                    AND DATE(fecha_creacion) = CURDATE()
                ");
                $stmt->execute([$prestacion['id_paciente']]);
                $yaNotificado = $stmt->fetchColumn() > 0;

                if (!$yaNotificado) {
                    // Crear notificación interna
                    $notificationId = $notificationModel->createExpiringServiceNotification(
                        $prestacion['id_paciente'],
                        $prestacion['id'],
                        $prestacion['paciente_nombre'],
                        $diasRestantes
                    );

                    if ($notificationId) {
                        $notificationCount++;
                        echo "  ✓ Notificación interna creada\n";
                    }

                    // Enviar emails a todos los usuarios activos
                    $usuarios = $userModel->getAll(['estado' => 'activo']);

                    if (!empty($usuarios)) {
                        $emailService->sendExpiringServiceNotification(
                            $usuarios,
                            $prestacion['paciente_nombre'],
                            $diasRestantes,
                            formatDate($prestacion['fecha_fin_prestacion'])
                        );

                        $emailCount += count($usuarios);
                        echo "  ✓ Emails enviados a " . count($usuarios) . " usuarios\n";
                    }
                } else {
                    echo "  ⓘ Ya se envió notificación hoy para este paciente\n";
                }
            }
        }
    }

    echo "\n=== Resumen ===\n";
    echo "Total prestaciones verificadas: " . count($prestaciones) . "\n";
    echo "Alertas detectadas: {$alertCount}\n";
    echo "Notificaciones internas creadas: {$notificationCount}\n";
    echo "Emails enviados: {$emailCount}\n";
    echo "\nScript finalizado exitosamente.\n";

} catch (Exception $e) {
    echo "\n[ERROR] " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";

    writeLog("Error en script de vencimientos: " . $e->getMessage(), 'error');
    exit(1);
}

exit(0);

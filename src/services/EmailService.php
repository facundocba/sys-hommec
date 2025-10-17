<?php

require_once __DIR__ . '/../../vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../../vendor/phpmailer/src/SMTP.php';
require_once __DIR__ . '/../../vendor/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mailer;
    private $config;
    private $enabled;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/config.php';
        $this->enabled = $this->config['notifications']['enable_email'];
    }

    /**
     * Configurar PHPMailer
     */
    private function configure()
    {
        try {
            // Configuración del servidor SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['mail']['host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['mail']['username'];
            $this->mailer->Password = $this->config['mail']['password'];

            // Configurar encriptación según el valor
            $encryption = $this->config['mail']['encryption'];
            if ($encryption === 'ssl') {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($encryption === 'tls') {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $this->mailer->Port = $this->config['mail']['port'];
            $this->mailer->Timeout = 30; // 30 segundos para timeout
            $this->mailer->CharSet = 'UTF-8';

            // IMPORTANTE: Desactivar verificación SSL ANTES de cualquier conexión
            $this->mailer->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            // Remitente por defecto
            $this->mailer->setFrom(
                $this->config['mail']['from']['address'],
                $this->config['mail']['from']['name']
            );

        } catch (Exception $e) {
            error_log("Error configurando EmailService: " . $e->getMessage());
            throw $e; // Re-lanzar para que el controlador pueda capturarlo
        }
    }

    /**
     * Enviar email genérico
     */
    public function send($to, $subject, $body, $isHTML = true, $bcc = [])
    {
        if (!$this->enabled) {
            writeLog("Emails deshabilitados - No se envió: {$subject} a {$to}", 'info');
            return false;
        }

        try {
            // Crear una nueva instancia de PHPMailer para cada email
            $this->mailer = new PHPMailer(true);
            $this->configure();

            // Destinatario
            if (is_array($to)) {
                foreach ($to as $address) {
                    $this->mailer->addAddress($address);
                }
            } else {
                $this->mailer->addAddress($to);
            }

            // BCC (copia oculta)
            if (!empty($bcc)) {
                if (is_array($bcc)) {
                    foreach ($bcc as $address) {
                        $this->mailer->addBCC($address);
                    }
                } else {
                    $this->mailer->addBCC($bcc);
                }
            }

            // Contenido
            $this->mailer->isHTML($isHTML);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            // Si es HTML, generar versión texto plano
            if ($isHTML) {
                $this->mailer->AltBody = strip_tags($body);
            }

            $this->mailer->send();
            writeLog("Email enviado exitosamente a: {$to} - Asunto: {$subject}", 'info');
            return true;

        } catch (Exception $e) {
            $errorMsg = "Error enviando email: " . $e->getMessage();
            writeLog($errorMsg, 'error');
            error_log($errorMsg);
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Enviar email de archivo subido a empresas con BCC a admins
     */
    public function sendFileUploadedNotification($empresasEmails, $pacienteNombre, $archivoNombre, $usuarioSubio, $adminsEmails = [])
    {
        $subject = "Nuevo archivo subido - {$pacienteNombre}";

        $body = $this->renderTemplate('file_uploaded', [
            'paciente_nombre' => $pacienteNombre,
            'archivo_nombre' => $archivoNombre,
            'usuario_subio' => $usuarioSubio,
            'fecha' => date('d/m/Y H:i')
        ]);

        // Enviar a cada empresa con BCC a administradores
        foreach ($empresasEmails as $empresaEmail) {
            if (!empty($empresaEmail)) {
                $this->send($empresaEmail, $subject, $body, true, $adminsEmails);
            }
        }
    }

    /**
     * Enviar documento a empresa
     */
    public function sendDocumentToCompany($empresaEmail, $empresaNombre, $pacienteNombre, $tipoDocumento, $asunto = '', $comentario = '', $archivoPath = '', $adminsEmails = [])
    {
        // Configurar datos según el tipo de documento
        $configuracion = $this->getDocumentTypeConfig($tipoDocumento);

        $subject = $configuracion['subject_prefix'] . " - {$pacienteNombre}";
        if (!empty($asunto)) {
            $subject = $asunto . " - " . $pacienteNombre;
        }

        $body = $this->renderTemplate('document_to_company', [
            'app_name' => $this->config['app']['name'] ?? 'Homme Cuidados Integrales',
            'titulo_principal' => $configuracion['titulo'],
            'subtitulo' => $configuracion['subtitulo'],
            'tipo_documento_label' => $configuracion['label'],
            'accion_solicitada' => $configuracion['accion'],
            'paciente_nombre' => $pacienteNombre,
            'empresa_nombre' => $empresaNombre,
            'fecha' => date('d/m/Y H:i'),
            'asunto' => $asunto,
            'comentario' => $comentario
        ]);

        return $this->sendWithAttachment($empresaEmail, $subject, $body, $archivoPath, $adminsEmails);
    }

    /**
     * Obtener configuración según tipo de documento
     */
    private function getDocumentTypeConfig($tipo)
    {
        $configs = [
            'receta' => [
                'label' => 'Receta médica',
                'titulo' => 'Solicitud de Facturación',
                'subtitulo' => 'Nueva receta médica cargada en el sistema',
                'subject_prefix' => 'Solicitud de Facturación',
                'accion' => 'Se solicita proceder con la facturación correspondiente'
            ],
            'documentacion' => [
                'label' => 'Documentación del paciente',
                'titulo' => 'Envío de Documentación',
                'subtitulo' => 'Nueva documentación del paciente',
                'subject_prefix' => 'Documentación de Paciente',
                'accion' => 'Se adjunta documentación del paciente para su archivo y revisión'
            ],
            'historial' => [
                'label' => 'Historial clínico',
                'titulo' => 'Envío de Historial Clínico',
                'subtitulo' => 'Historial clínico del paciente',
                'subject_prefix' => 'Historial Clínico',
                'accion' => 'Se adjunta el historial clínico para su revisión y archivo'
            ],
            'autorizacion' => [
                'label' => 'Autorización médica',
                'titulo' => 'Solicitud de Autorización',
                'subtitulo' => 'Nueva autorización médica',
                'subject_prefix' => 'Solicitud de Autorización Médica',
                'accion' => 'Se solicita proceder con la autorización correspondiente'
            ],
            'estudios' => [
                'label' => 'Estudios / Análisis',
                'titulo' => 'Envío de Estudios Médicos',
                'subtitulo' => 'Estudios y análisis del paciente',
                'subject_prefix' => 'Estudios Médicos',
                'accion' => 'Se adjuntan estudios médicos para su revisión y procesamiento'
            ],
            'informe' => [
                'label' => 'Informe médico',
                'titulo' => 'Envío de Informe Médico',
                'subtitulo' => 'Informe médico del paciente',
                'subject_prefix' => 'Informe Médico',
                'accion' => 'Se adjunta informe médico para su conocimiento y archivo'
            ],
            'otro' => [
                'label' => 'Otro documento',
                'titulo' => 'Envío de Documentación',
                'subtitulo' => 'Nuevo documento del paciente',
                'subject_prefix' => 'Documentación de Paciente',
                'accion' => 'Se adjunta documentación para su revisión'
            ]
        ];

        return $configs[$tipo] ?? $configs['otro'];
    }

    /**
     * Enviar email con archivo adjunto
     */
    private function sendWithAttachment($to, $subject, $body, $attachmentPath = '', $bcc = [])
    {
        if (!$this->enabled) {
            writeLog("Emails deshabilitados - No se envió: {$subject} a {$to}", 'info');
            return false;
        }

        try {
            // Crear una nueva instancia de PHPMailer para cada email
            $this->mailer = new PHPMailer(true);
            $this->configure();

            // Destinatario
            if (is_array($to)) {
                foreach ($to as $address) {
                    $this->mailer->addAddress($address);
                }
            } else {
                $this->mailer->addAddress($to);
            }

            // BCC (copia oculta)
            if (!empty($bcc)) {
                if (is_array($bcc)) {
                    foreach ($bcc as $address) {
                        $this->mailer->addBCC($address);
                    }
                } else {
                    $this->mailer->addBCC($bcc);
                }
            }

            // Adjuntar archivo si existe
            if (!empty($attachmentPath) && file_exists($attachmentPath)) {
                $this->mailer->addAttachment($attachmentPath);
            }

            // Contenido
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            $this->mailer->AltBody = strip_tags($body);

            $this->mailer->send();
            writeLog("Email enviado exitosamente a: {$to} - Asunto: {$subject}", 'info');
            return true;

        } catch (Exception $e) {
            $errorMsg = "Error enviando email: " . $e->getMessage();
            writeLog($errorMsg, 'error');
            error_log($errorMsg);
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Enviar email de prestación próxima a vencer
     */
    public function sendExpiringServiceNotification($usuarios, $pacienteNombre, $diasRestantes, $fechaVencimiento)
    {
        $subject = "Alerta: Prestación próxima a vencer - {$pacienteNombre}";

        $body = $this->renderTemplate('expiring_service', [
            'paciente_nombre' => $pacienteNombre,
            'dias_restantes' => $diasRestantes,
            'fecha_vencimiento' => $fechaVencimiento
        ]);

        foreach ($usuarios as $usuario) {
            if (!empty($usuario['email'])) {
                $this->send($usuario['email'], $subject, $body);
            }
        }
    }

    /**
     * Enviar email de notificación genérica del sistema
     */
    public function sendSystemNotification($usuarios, $titulo, $mensaje)
    {
        $subject = "MedFlow - {$titulo}";

        $body = $this->renderTemplate('system_notification', [
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'fecha' => date('d/m/Y H:i')
        ]);

        foreach ($usuarios as $usuario) {
            if (!empty($usuario['email'])) {
                $this->send($usuario['email'], $subject, $body);
            }
        }
    }

    /**
     * Renderizar plantilla de email
     */
    private function renderTemplate($template, $data = [])
    {
        $templatePath = __DIR__ . '/../../views/emails/' . $template . '.php';

        if (!file_exists($templatePath)) {
            return $this->getDefaultTemplate($data);
        }

        ob_start();
        extract($data);
        include $templatePath;
        return ob_get_clean();
    }

    /**
     * Plantilla por defecto si no existe archivo específico
     */
    private function getDefaultTemplate($data)
    {
        $content = '';
        foreach ($data as $key => $value) {
            $content .= ucfirst(str_replace('_', ' ', $key)) . ": {$value}<br>";
        }

        return $this->getBaseTemplate('Notificación de MedFlow', $content);
    }

    /**
     * Plantilla base HTML
     */
    private function getBaseTemplate($title, $content)
    {
        return '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($title) . '</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8fafc;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #88DBF2 0%, #6A89A7 100%);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: white;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
            color: #384959;
            line-height: 1.6;
        }
        .footer {
            background: #f1f5f9;
            padding: 20px 30px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #88DBF2 0%, #6A89A7 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>MedFlow</h1>
        </div>
        <div class="content">
            ' . $content . '
        </div>
        <div class="footer">
            <p>Este es un mensaje automático de MedFlow. Por favor no responda a este correo.</p>
            <p>&copy; ' . date('Y') . ' MedFlow - Sistema Administrativo Médico</p>
        </div>
    </div>
</body>
</html>';
    }
}

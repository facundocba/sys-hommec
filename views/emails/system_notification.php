<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación del Sistema</title>
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
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
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
            line-height: 1.8;
        }
        .content h2 {
            color: #8b5cf6;
            font-size: 22px;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f5f3ff;
            border-left: 4px solid #8b5cf6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .footer {
            background: #f1f5f9;
            padding: 20px 30px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 MedFlow</h1>
        </div>
        <div class="content">
            <div style="text-align: center;" class="icon">ℹ️</div>
            <h2><?php echo htmlspecialchars($titulo); ?></h2>

            <div class="info-box">
                <?php echo nl2br(htmlspecialchars($mensaje)); ?>
            </div>

            <div style="margin-top: 20px; color: #64748b; font-size: 14px;">
                <strong>Fecha:</strong> <?php echo htmlspecialchars($fecha); ?>
            </div>

            <p style="margin-top: 30px; color: #64748b;">
                Inicia sesión en MedFlow para más detalles.
            </p>
        </div>
        <div class="footer">
            <p>Este es un mensaje automático de MedFlow. Por favor no responda a este correo.</p>
            <p>&copy; <?php echo date('Y'); ?> MedFlow - Sistema Administrativo Médico</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta de Vencimiento</title>
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
            background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
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
            color: #f59e0b;
            font-size: 22px;
            margin-bottom: 20px;
        }
        .alert-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .alert-box strong {
            color: #92400e;
            display: block;
            margin-bottom: 5px;
        }
        .info-box {
            background: #f1f5f9;
            border-left: 4px solid #88DBF2;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .info-box strong {
            color: #384959;
            display: block;
            margin-bottom: 5px;
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
            <h1>⚠️ MedFlow</h1>
        </div>
        <div class="content">
            <div style="text-align: center;" class="icon">⏰</div>
            <h2>Alerta: Prestación Próxima a Vencer</h2>
            <p><strong>Se requiere atención inmediata.</strong> Una prestación está próxima a vencer.</p>

            <div class="alert-box">
                <strong>⚠️ Vence en <?php echo $dias_restantes; ?> día<?php echo $dias_restantes > 1 ? 's' : ''; ?></strong>
            </div>

            <div class="info-box">
                <strong>Paciente:</strong>
                <span><?php echo htmlspecialchars($paciente_nombre); ?></span>
            </div>

            <div class="info-box">
                <strong>Fecha de Vencimiento:</strong>
                <span><?php echo htmlspecialchars($fecha_vencimiento); ?></span>
            </div>

            <p style="margin-top: 30px; color: #64748b;">
                Por favor, revise el expediente del paciente y tome las acciones necesarias antes de la fecha de vencimiento.
            </p>

            <p style="margin-top: 20px; color: #92400e; font-weight: 600;">
                Inicia sesión en MedFlow para gestionar la prestación.
            </p>
        </div>
        <div class="footer">
            <p>Este es un mensaje automático de MedFlow. Por favor no responda a este correo.</p>
            <p>&copy; <?php echo date('Y'); ?> MedFlow - Sistema Administrativo Médico</p>
        </div>
    </div>
</body>
</html>

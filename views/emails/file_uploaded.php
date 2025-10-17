<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Archivo Subido</title>
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
            line-height: 1.8;
        }
        .content h2 {
            color: #88DBF2;
            font-size: 22px;
            margin-bottom: 20px;
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
            <h1>📁 MedFlow</h1>
        </div>
        <div class="content">
            <div style="text-align: center;" class="icon">📄</div>
            <h2>Nuevo Archivo Subido</h2>
            <p>Se ha subido un nuevo archivo al sistema MedFlow.</p>

            <div class="info-box">
                <strong>Paciente:</strong>
                <span><?php echo htmlspecialchars($paciente_nombre); ?></span>
            </div>

            <div class="info-box">
                <strong>Archivo:</strong>
                <span><?php echo htmlspecialchars($archivo_nombre); ?></span>
            </div>

            <div class="info-box">
                <strong>Subido por:</strong>
                <span><?php echo htmlspecialchars($usuario_subio); ?></span>
            </div>

            <div class="info-box">
                <strong>Fecha y Hora:</strong>
                <span><?php echo htmlspecialchars($fecha); ?></span>
            </div>

            <p style="margin-top: 30px; color: #64748b;">
                Inicia sesión en MedFlow para ver el archivo y todos los detalles del paciente.
            </p>
        </div>
        <div class="footer">
            <p>Este es un mensaje automático de MedFlow. Por favor no responda a este correo.</p>
            <p>&copy; <?php echo date('Y'); ?> MedFlow - Sistema Administrativo Médico</p>
        </div>
    </div>
</body>
</html>

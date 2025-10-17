<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Receta para Facturación</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8fafc;
        }
        .container {
            max-width: 650px;
            margin: 40px auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }
        .header {
            background: linear-gradient(135deg, #88DBF2 0%, #6A89A7 100%);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 4px solid #88DBF2;
        }
        .header h1 {
            margin: 0;
            color: white;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header p {
            margin: 8px 0 0 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            font-weight: 400;
        }
        .content {
            padding: 40px 35px;
            color: #384959;
            line-height: 1.7;
        }
        .title-section {
            text-align: center;
            padding: 25px 0;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 30px;
        }
        .title-section h2 {
            margin: 0;
            color: #384959;
            font-size: 24px;
            font-weight: 700;
        }
        .title-section p {
            margin: 10px 0 0 0;
            color: #64748b;
            font-size: 15px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        .data-table tr {
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table tr:last-child {
            border-bottom: none;
        }
        .data-table td {
            padding: 16px 12px;
            vertical-align: top;
        }
        .data-table td:first-child {
            font-weight: 600;
            color: #384959;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 35%;
            background: #f8fafc;
        }
        .data-table td:last-child {
            color: #475569;
            font-size: 15px;
        }
        .info-section {
            background: #f8fafc;
            border-left: 4px solid #88DBF2;
            padding: 20px 24px;
            margin: 30px 0;
            border-radius: 8px;
        }
        .info-section h3 {
            margin: 0 0 12px 0;
            color: #384959;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-section p {
            margin: 0;
            color: #475569;
            font-size: 15px;
            line-height: 1.7;
        }
        .message-box {
            background: #ffffff;
            border: 2px solid #e2e8f0;
            padding: 20px 24px;
            margin: 25px 0;
            border-radius: 10px;
        }
        .message-box h3 {
            margin: 0 0 12px 0;
            color: #384959;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .message-box .message-content {
            color: #475569;
            font-size: 15px;
            line-height: 1.7;
            white-space: pre-wrap;
        }
        <?php if (!empty($asunto)): ?>
        .subject-box {
            background: linear-gradient(135deg, rgba(136, 219, 242, 0.08) 0%, rgba(189, 221, 252, 0.08) 100%);
            border: 2px solid #88DBF2;
            padding: 20px 24px;
            margin: 25px 0 30px 0;
            border-radius: 10px;
        }
        .subject-box h3 {
            margin: 0 0 12px 0;
            color: #384959;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .subject-box p {
            margin: 0;
            color: #384959;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.5;
        }
        <?php endif; ?>
        .action-box {
            background: linear-gradient(135deg, rgba(136, 219, 242, 0.08) 0%, rgba(106, 137, 167, 0.08) 100%);
            border: 2px solid #88DBF2;
            padding: 20px 24px;
            margin: 30px 0;
            border-radius: 10px;
            text-align: center;
        }
        .action-box p {
            margin: 0;
            color: #384959;
            font-size: 15px;
            font-weight: 600;
        }
        .footer {
            background: #384959;
            padding: 30px;
            text-align: center;
            color: #cbd5e1;
            font-size: 13px;
        }
        .footer p {
            margin: 8px 0;
            line-height: 1.6;
        }
        .footer .company-name {
            color: #88DBF2;
            font-weight: 700;
            font-size: 14px;
        }
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Homme Cuidados Integrales</h1>
            <p>Sistema de Gestión Administrativa</p>
        </div>
        <div class="content">
            <div class="title-section">
                <h2><?php echo htmlspecialchars($titulo_principal); ?></h2>
                <p><?php echo htmlspecialchars($subtitulo); ?></p>
            </div>

            <?php if (!empty($asunto)): ?>
            <div class="subject-box">
                <h3>Asunto</h3>
                <p><?php echo nl2br(htmlspecialchars($asunto)); ?></p>
            </div>
            <?php endif; ?>

            <table class="data-table">
                <tr>
                    <td>Tipo de Documento</td>
                    <td><?php echo htmlspecialchars($tipo_documento_label); ?></td>
                </tr>
                <tr>
                    <td>Paciente</td>
                    <td><?php echo htmlspecialchars($paciente_nombre); ?></td>
                </tr>
                <tr>
                    <td>Fecha y Hora</td>
                    <td><?php echo htmlspecialchars($fecha); ?></td>
                </tr>
            </table>

            <?php if (!empty($comentario)): ?>
            <div class="divider"></div>
            <div class="message-box">
                <h3>Observaciones</h3>
                <div class="message-content"><?php echo nl2br(htmlspecialchars($comentario)); ?></div>
            </div>
            <?php endif; ?>

            <div class="action-box">
                <p><?php echo htmlspecialchars($accion_solicitada); ?></p>
            </div>

            <div class="info-section">
                <h3>Información Importante</h3>
                <p>El archivo se encuentra adjunto a este correo electrónico.</p>
            </div>
        </div>
        <div class="footer">
            <p><strong>Este es un mensaje automático del sistema</strong></p>
            <p>Por favor no responda a este correo electrónico</p>
            <div class="divider" style="background: linear-gradient(90deg, transparent, rgba(136, 219, 242, 0.3), transparent); margin: 20px 0;"></div>
            <p class="company-name">Homme Cuidados Integrales</p>
            <p>&copy; <?php echo date('Y'); ?> Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>

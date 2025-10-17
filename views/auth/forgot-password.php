<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - MedFlow</title>
    <link rel="icon" type="image/png" href="<?= asset('img/Homme_Cuidados_Integrales_transparent-e1749324227114.png') ?>">
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-box {
            width: 100%;
            max-width: 450px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo {
            font-size: 3rem;
            color: var(--white);
            margin-bottom: 0.5rem;
        }

        .login-title {
            color: var(--white);
            font-size: 1.5rem;
            font-weight: 300;
            letter-spacing: 1px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .login-card h2 {
            text-align: center;
            margin-bottom: 1rem;
            color: var(--primary-navy);
        }

        .login-card p {
            text-align: center;
            color: var(--dark-gray);
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .back-login {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-login a {
            color: var(--secondary-blue);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .back-login a:hover {
            text-decoration: underline;
        }

        .btn-recover {
            width: 100%;
            margin-top: 1rem;
        }

        .footer-text {
            text-align: center;
            margin-top: 2rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="login-logo">🏥</div>
                <h1 class="login-title">MedFlow</h1>
            </div>

            <div class="login-card">
                <h2>Recuperar Contraseña</h2>
                <p>Ingrese su email y le enviaremos instrucciones para recuperar su contraseña.</p>

                <?php
                $flash = getFlash();
                if ($flash):
                ?>
                    <div class="alert alert-<?= $flash['type'] ?>">
                        <?= $flash['message'] ?>
                    </div>
                <?php endif; ?>

                <form action="<?= baseUrl('login/recoverPassword') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            placeholder="usuario@ejemplo.com"
                            required
                            autofocus
                        >
                    </div>

                    <button type="submit" class="btn btn-primary btn-recover">
                        Enviar Instrucciones
                    </button>

                    <div class="back-login">
                        <a href="<?= baseUrl('login') ?>">← Volver al inicio de sesión</a>
                    </div>
                </form>
            </div>

            <div class="footer-text">
                &copy; <?= date('Y') ?> MedFlow - Sistema Administrativo Médico
            </div>
        </div>
    </div>
</body>
</html>

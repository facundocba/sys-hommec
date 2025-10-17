<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Homme Cuidados Integrales</title>
    <link rel="icon" type="image/png" href="<?= asset('img/Homme_Cuidados_Integrales_transparent-e1749324227114.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #001f3f;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* Fondo dinámico con formas glassmorphism */
        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(0, 116, 217, 0.4) 0%, transparent 70%);
            border-radius: 50%;
            top: -200px;
            right: -200px;
            filter: blur(80px);
            animation: float 8s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(127, 219, 255, 0.3) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -150px;
            left: -150px;
            filter: blur(80px);
            animation: float 6s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }

        /* Grid sutil de fondo */
        .grid-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(rgba(127, 219, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(127, 219, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            opacity: 0.5;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }

        .login-wrapper {
            display: flex;
            max-width: 1100px;
            width: 100%;
            gap: 3rem;
            align-items: center;
        }

        /* Panel izquierdo - Branding */
        .login-branding {
            flex: 1;
            opacity: 0;
            animation: slideInLeft 0.8s ease-out 0.2s forwards;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .brand-logo {
            width: 200px;
            height: 200px;
            position: relative;
            margin-bottom: 1.5rem;
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 4px 20px rgba(127, 219, 255, 0.4)) drop-shadow(0 0 40px rgba(127, 219, 255, 0.3));
            animation: logoGlow 3s ease-in-out infinite alternate;
        }

        @keyframes logoGlow {
            0% {
                filter: drop-shadow(0 4px 20px rgba(127, 219, 255, 0.4)) drop-shadow(0 0 40px rgba(127, 219, 255, 0.3));
            }
            100% {
                filter: drop-shadow(0 4px 30px rgba(127, 219, 255, 0.6)) drop-shadow(0 0 50px rgba(127, 219, 255, 0.5));
            }
        }

        .brand-title {
            font-size: 3.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #FFFFFF 0%, #7FDBFF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            line-height: 1.2;
            letter-spacing: -0.02em;
            text-shadow: 0 0 40px rgba(127, 219, 255, 0.3);
            filter: drop-shadow(0 4px 20px rgba(127, 219, 255, 0.4));
            animation: titleGlow 3s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            0% {
                filter: drop-shadow(0 4px 20px rgba(127, 219, 255, 0.4));
            }
            100% {
                filter: drop-shadow(0 4px 30px rgba(127, 219, 255, 0.6));
            }
        }

        .brand-subtitle {
            font-size: 1.25rem;
            color: #7FDBFF;
            font-weight: 400;
            margin-bottom: 2rem;
            letter-spacing: 0.01em;
        }

        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            background: rgba(127, 219, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(127, 219, 255, 0.2);
        }

        .feature-icon svg {
            width: 18px;
            height: 18px;
            stroke: #7FDBFF;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Panel derecho - Formulario */
        .login-form-panel {
            flex: 0 0 480px;
            opacity: 0;
            animation: slideInRight 0.8s ease-out 0.4s forwards;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(40px) saturate(180%);
            -webkit-backdrop-filter: blur(40px) saturate(180%);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 3rem;
            box-shadow:
                0 8px 32px rgba(0, 31, 63, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.1),
                inset 0 -1px 0 rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(127, 219, 255, 0.5), transparent);
        }

        .login-card h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #FFFFFF;
            margin-bottom: 0.5rem;
            letter-spacing: -0.01em;
        }

        .login-card p {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 2.5rem;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            letter-spacing: 0.02em;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1.125rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .input-icon svg {
            width: 20px;
            height: 20px;
            stroke: rgba(0, 116, 217, 0.6);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .form-control {
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            color: #001f3f;
            padding: 1rem 1.25rem 1rem 3.25rem;
            font-size: 0.95rem;
            border-radius: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
        }

        .form-control::placeholder {
            color: rgba(0, 31, 63, 0.4);
            font-weight: 400;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 1);
            border-color: #7FDBFF;
            box-shadow:
                0 0 0 4px rgba(127, 219, 255, 0.15),
                0 8px 24px rgba(0, 116, 217, 0.2);
            outline: none;
            transform: translateY(-1px);
        }

        .form-control:focus + .input-icon svg {
            stroke: #0074D9;
        }

        .btn-login {
            width: 100%;
            margin-top: 2rem;
            padding: 1.125rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            background: linear-gradient(135deg, #0074D9 0%, #7FDBFF 100%);
            border: none;
            border-radius: 14px;
            color: #FFFFFF;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 24px rgba(0, 116, 217, 0.4);
            position: relative;
            overflow: hidden;
            letter-spacing: 0.02em;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0099FF 0%, #99E6FF 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(0, 116, 217, 0.5);
        }

        .btn-login:hover::before {
            opacity: 1;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login span {
            position: relative;
            z-index: 1;
        }

        .divider {
            margin: 2rem 0;
            text-align: center;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        }

        .divider span {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-text {
            text-align: center;
            margin-top: 2rem;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.8rem;
        }

        .alert {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            border-left: 3px solid;
            font-size: 0.9rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 968px) {
            .login-branding {
                display: none;
            }

            .login-form-panel {
                flex: 1;
                max-width: 480px;
            }

            .login-wrapper {
                justify-content: center;
            }
        }

        @media (max-width: 640px) {
            .login-card {
                padding: 2rem 1.5rem;
            }

            .brand-title {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="grid-background"></div>

    <div class="login-container">
        <div class="login-wrapper">
            <!-- Panel Izquierdo - Branding -->
            <div class="login-branding">
                <div class="brand-logo">
                    <img src="<?= asset('img/Homme_Cuidados_Integrales_transparent-e1749324227114.png') ?>" alt="Homme Cuidados Integrales Logo">
                </div>
                <h1 class="brand-title">Homme <br>Cuidados Integrales</h1>
                <p class="brand-subtitle"></p>

                <div class="brand-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <span>Gestión integral de pacientes</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"/>
                                <line x1="12" y1="20" x2="12" y2="4"/>
                                <line x1="6" y1="20" x2="6" y2="14"/>
                            </svg>
                        </div>
                        <span>Reportes y estadísticas en tiempo real</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <span>Seguridad y privacidad garantizada</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                            </svg>
                        </div>
                        <span>Acceso rápido desde cualquier dispositivo</span>
                    </div>
                </div>
            </div>

            <!-- Panel Derecho - Formulario -->
            <div class="login-form-panel">
                <div class="login-card">
                    <h2>Bienvenido de nuevo</h2>
                    <p>Ingrese sus credenciales para continuar</p>

                    <?php
                    $flash = getFlash();
                    if ($flash):
                    ?>
                        <div class="alert alert-<?= $flash['type'] ?>">
                            <?= $flash['message'] ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= baseUrl('login/authenticate') ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                        <div class="form-group">
                            <label for="email" class="form-label">CORREO ELECTRÓNICO</label>
                            <div class="input-wrapper">
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="correo@ejemplo.com"
                                    required
                                    autofocus
                                >
                                <span class="input-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">CONTRASEÑA</label>
                            <div class="input-wrapper">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Ingrese su contraseña"
                                    required
                                >
                                <span class="input-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn-login">
                            <span>Iniciar Sesión</span>
                        </button>
                    </form>

                    <div class="footer-text">
                        &copy; <?= date('Y') ?> Homme Cuidados Integrales. Todos los derechos reservados.
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

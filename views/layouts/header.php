<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - Homme Cuidados Integrales</title>
    <link rel="icon" type="image/png" href="<?= asset('img/Homme_Cuidados_Integrales_transparent-e1749324227114.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <style>
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            background: transparent;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .top-bar {
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(136, 219, 242, 0.25);
            padding: 1.25rem 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow:
                0 4px 24px rgba(56, 73, 89, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
            z-index: 100;
        }

        .top-bar::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg,
                var(--stormy-cyan) 0%,
                var(--stormy-light) 25%,
                var(--stormy-blue) 50%,
                var(--stormy-light) 75%,
                var(--stormy-cyan) 100%
            );
            background-size: 200% 100%;
            animation: gradientShift 8s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 0%; }
            50% { background-position: 100% 0%; }
        }

        .page-title-wrapper {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-title-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);
            box-shadow: 0 4px 16px rgba(136, 219, 242, 0.3);
            position: relative;
        }

        .page-title-icon::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 13px;
            padding: 2px;
            background: linear-gradient(135deg, rgba(136, 219, 242, 0.6), rgba(189, 221, 252, 0.4));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            animation: rotateBorder 3s linear infinite;
        }

        @keyframes rotateBorder {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .page-title-icon svg {
            width: 24px;
            height: 24px;
            stroke: var(--white);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        .page-title-content {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .page-title-main {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
            line-height: 1;
        }

        .page-title-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: var(--stormy-blue);
            font-weight: 500;
        }

        .breadcrumb-separator {
            color: var(--stormy-cyan);
            font-weight: 300;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .breadcrumb-item svg {
            width: 12px;
            height: 12px;
            stroke: var(--stormy-cyan);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(189, 221, 252, 0.15) 0%, rgba(136, 219, 242, 0.1) 100%);
            border: 1px solid rgba(136, 219, 242, 0.2);
            transition: all 0.3s ease;
        }

        .user-info:hover {
            background: linear-gradient(135deg, rgba(136, 219, 242, 0.2) 0%, rgba(189, 221, 252, 0.15) 100%);
            border-color: rgba(136, 219, 242, 0.35);
            transform: translateY(-1px);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.125rem;
            box-shadow: 0 4px 12px rgba(106, 137, 167, 0.3);
            position: relative;
        }

        .user-avatar::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 12px;
            padding: 2px;
            background: linear-gradient(135deg, rgba(136, 219, 242, 0.5), rgba(189, 221, 252, 0.3));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .user-details {
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }

        .user-name {
            font-weight: 600;
            color: var(--stormy-dark);
            font-size: 0.9375rem;
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--stormy-blue);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .notifications-btn {
            position: relative;
            background: linear-gradient(135deg, rgba(136, 219, 242, 0.15) 0%, rgba(189, 221, 252, 0.1) 100%);
            border: 1px solid rgba(136, 219, 242, 0.25);
            border-radius: 12px;
            cursor: pointer;
            padding: 0.625rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notifications-btn svg {
            width: 22px;
            height: 22px;
            stroke: var(--stormy-blue);
            transition: all 0.3s ease;
        }

        .notifications-btn:hover {
            background: linear-gradient(135deg, rgba(136, 219, 242, 0.25) 0%, rgba(189, 221, 252, 0.2) 100%);
            border-color: rgba(136, 219, 242, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(136, 219, 242, 0.2);
        }

        .notifications-btn:hover svg {
            stroke: var(--stormy-cyan);
            transform: rotate(-15deg);
        }

        .notifications-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: linear-gradient(135deg, var(--danger) 0%, #f87171 100%);
            color: var(--white);
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.6875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 2px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        }

        .btn-logout {
            background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 100%);
            color: var(--white);
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(56, 73, 89, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-logout::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(56, 73, 89, 0.3);
        }

        .btn-logout:hover::before {
            left: 100%;
        }

        @media (max-width: 768px) {
            .dashboard-wrapper {
                flex-direction: column;
            }

            .main-content {
                padding: 1.5rem;
            }

            .top-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding: 1rem 1.25rem;
                border-radius: 14px;
                margin-bottom: 1.5rem;
            }

            .page-title-wrapper {
                width: 100%;
                gap: 0.75rem;
            }

            .page-title-icon {
                width: 40px;
                height: 40px;
                border-radius: 10px;
            }

            .page-title-icon svg {
                width: 20px;
                height: 20px;
            }

            .page-title-main {
                font-size: 1.25rem;
            }

            .page-title-breadcrumb {
                font-size: 0.6875rem;
                gap: 0.375rem;
            }

            .user-menu {
                width: 100%;
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .notifications-btn {
                order: 1;
                flex-shrink: 0;
                position: relative;
                z-index: 10;
                -webkit-tap-highlight-color: transparent;
                touch-action: manipulation;
                min-width: 44px;
                min-height: 44px;
            }

            .user-info {
                order: 2;
                flex: 1;
                min-width: 0;
                padding: 0.5rem 0.875rem;
                position: relative;
                z-index: 10;
            }

            .user-avatar {
                width: 38px;
                height: 38px;
                font-size: 1rem;
            }

            .user-name {
                font-size: 0.875rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .user-role {
                font-size: 0.6875rem;
            }

            .btn-logout {
                order: 3;
                width: 100%;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                position: relative;
                z-index: 10;
                -webkit-tap-highlight-color: transparent;
                touch-action: manipulation;
                min-height: 44px;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 1rem;
            }

            .top-bar {
                padding: 1rem;
                border-radius: 12px;
            }

            .page-title-icon {
                width: 36px;
                height: 36px;
            }

            .page-title-icon svg {
                width: 18px;
                height: 18px;
            }

            .page-title-main {
                font-size: 1.125rem;
            }

            .page-title-breadcrumb {
                font-size: 0.625rem;
            }

            .user-info {
                padding: 0.5rem 0.75rem;
            }

            .user-avatar {
                width: 36px;
                height: 36px;
                font-size: 0.9375rem;
            }

            .user-name {
                font-size: 0.8125rem;
            }

            .user-role {
                font-size: 0.625rem;
            }

            .btn-logout {
                padding: 0.625rem 1rem;
                font-size: 0.8125rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <?php include __DIR__ . '/sidebar.php'; ?>

        <div class="main-content">
            <div class="top-bar">
                <div class="page-title-wrapper">
                    <div class="page-title-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <div class="page-title-content">
                        <h1 class="page-title-main"><?= $title ?? 'Dashboard' ?></h1>
                        <div class="page-title-breadcrumb">
                            <div class="breadcrumb-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                </svg>
                                <span>Homme</span>
                            </div>
                            <span class="breadcrumb-separator">/</span>
                            <span><?= $title ?? 'Dashboard' ?></span>
                        </div>
                    </div>
                </div>

                <div class="user-menu">
                    <button class="notifications-btn" onclick="window.location.href='<?= baseUrl('notifications') ?>'">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <?php
                        $unreadCount = getUnreadNotificationsCount();
                        if ($unreadCount > 0):
                        ?>
                            <span class="notifications-badge"><?= $unreadCount > 9 ? '9+' : $unreadCount ?></span>
                        <?php endif; ?>
                    </button>

                    <div class="user-info">
                        <div class="user-avatar">
                            <?= strtoupper(substr(getCurrentUser()['nombre'], 0, 1)) ?>
                        </div>
                        <div class="user-details">
                            <span class="user-name"><?= getCurrentUser()['nombre'] ?></span>
                            <span class="user-role"><?= getCurrentUser()['rol'] ?></span>
                        </div>
                    </div>

                    <button onclick="window.location.href='<?= baseUrl('login/logout') ?>'" class="btn-logout">
                        Cerrar Sesión
                    </button>
                </div>
            </div>

            <!-- Toast Container -->
            <div id="toast-container"></div>

            <?php
            $flash = getFlash();
            if ($flash):
            ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showToast('<?= addslashes($flash['message']) ?>', '<?= $flash['type'] ?>');
                    });
                </script>
            <?php endif; ?>

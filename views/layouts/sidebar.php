<style>
    .sidebar {
        width: 280px;
        background:
            linear-gradient(135deg, rgba(136, 219, 242, 0.15) 0%, transparent 50%),
            linear-gradient(225deg, rgba(189, 221, 252, 0.12) 0%, transparent 60%),
            linear-gradient(315deg, rgba(106, 137, 167, 0.18) 0%, transparent 70%),
            linear-gradient(180deg, #2d4456 0%, #384959 50%, #2a3847 100%);
        border-right: 3px solid var(--stormy-cyan);
        color: var(--white);
        padding: 0;
        box-shadow:
            4px 0 32px rgba(56, 73, 89, 0.2),
            inset -1px 0 0 rgba(136, 219, 242, 0.1);
        position: relative;
        z-index: 100;
        overflow: hidden;
    }

    .sidebar::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 300px;
        height: 300px;
        background:
            radial-gradient(circle at 30% 30%, rgba(136, 219, 242, 0.25) 0%, transparent 50%),
            radial-gradient(circle at 70% 70%, rgba(189, 221, 252, 0.15) 0%, transparent 60%);
        border-radius: 50%;
        pointer-events: none;
        animation: pulse 8s ease-in-out infinite;
    }

    .sidebar::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -80px;
        width: 280px;
        height: 280px;
        background:
            radial-gradient(circle at 50% 50%, rgba(106, 137, 167, 0.2) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(136, 219, 242, 0.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        animation: pulse 10s ease-in-out infinite reverse;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }

    .sidebar-logo {
        text-align: center;
        padding: 2rem;
        border-bottom: 1px solid rgba(136, 219, 242, 0.2);
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.08) 0%, transparent 100%);
        position: relative;
    }

    .sidebar-logo-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(136, 219, 242, 0.3);
    }

    .sidebar-logo-icon svg {
        width: 32px;
        height: 32px;
        stroke: var(--white);
    }

    .sidebar-logo-text {
        font-size: 1.125rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        color: var(--white);
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        line-height: 1.4;
    }

    .sidebar-logo-text-small {
        font-size: 0.875rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.85);
        display: block;
        margin-top: 0.25rem;
    }

    .sidebar-nav {
        list-style: none;
        padding: 0 1rem;
        margin: 0;
    }

    .sidebar-nav-item {
        margin-bottom: 0.375rem;
    }

    .sidebar-nav-link {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        padding: 0.875rem 1rem;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 12px;
        position: relative;
        font-weight: 500;
        font-size: 0.9375rem;
        overflow: hidden;
    }

    .sidebar-nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 4px;
        height: 100%;
        background: var(--stormy-cyan);
        transform: translateX(-4px);
        transition: transform 0.3s ease;
    }

    .sidebar-nav-link:hover {
        background: linear-gradient(90deg, rgba(136, 219, 242, 0.15) 0%, rgba(136, 219, 242, 0.05) 100%);
        color: rgba(255, 255, 255, 0.95);
        transform: translateX(4px);
    }

    .sidebar-nav-link:hover::before {
        transform: translateX(0);
    }

    .sidebar-nav-link.active {
        background: linear-gradient(135deg, var(--stormy-cyan) 0%, #6ec9e0 100%);
        color: var(--stormy-dark);
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(136, 219, 242, 0.4);
    }

    .sidebar-nav-link.active::before {
        transform: translateX(0);
        background: var(--stormy-dark);
    }

    .sidebar-nav-icon {
        width: 22px;
        height: 22px;
        flex-shrink: 0;
    }

    .sidebar-nav-icon svg {
        width: 100%;
        height: 100%;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .sidebar-footer {
        position: absolute;
        bottom: 0;
        width: 280px;
        padding: 1.5rem 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.6);
        text-align: center;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            position: relative;
        }

        .sidebar-footer {
            position: relative;
            width: 100%;
        }
    }
</style>

<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
            </svg>
        </div>
        <div class="sidebar-logo-text">
            Homme
            <span class="sidebar-logo-text-small">Cuidados Integrales</span>
        </div>
    </div>

    <nav>
        <ul class="sidebar-nav">
            <li class="sidebar-nav-item">
                <a href="<?= baseUrl('dashboard') ?>" class="sidebar-nav-link <?= ($_GET['url'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"/>
                            <rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/>
                        </svg>
                    </span>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-nav-item">
                <a href="<?= baseUrl('patients') ?>" class="sidebar-nav-link <?= ($_GET['url'] ?? '') === 'patients' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </span>
                    <span>Pacientes</span>
                </a>
            </li>

            <li class="sidebar-nav-item">
                <a href="<?= baseUrl('professionals') ?>" class="sidebar-nav-link <?= ($_GET['url'] ?? '') === 'professionals' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="8.5" cy="7" r="4"/>
                            <polyline points="17 11 19 13 23 9"/>
                        </svg>
                    </span>
                    <span>Profesionales</span>
                </a>
            </li>

            <?php if (!isCoordinator()): ?>
            <li class="sidebar-nav-item">
                <a href="<?= baseUrl('professionals/reports') ?>" class="sidebar-nav-link <?= (($_GET['url'] ?? '') === 'professionals/reports' || strpos(($_GET['url'] ?? ''), 'professionals/reports') === 0) ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"/>
                            <line x1="12" y1="20" x2="12" y2="4"/>
                            <line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </span>
                    <span>Reportes Financieros</span>
                </a>
            </li>
            <?php endif; ?>

            <li class="sidebar-nav-item">
                <a href="<?= baseUrl('companies') ?>" class="sidebar-nav-link <?= strpos(($_GET['url'] ?? ''), 'companies') === 0 ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                        </svg>
                    </span>
                    <span>Empresas</span>
                </a>
            </li>

            <li class="sidebar-nav-item">
                <a href="<?= baseUrl('prestaciones') ?>" class="sidebar-nav-link <?= ($_GET['url'] ?? '') === 'prestaciones' || ($_GET['url'] ?? '') === 'tipos-prestacion' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                    </span>
                    <span>Prestaciones</span>
                </a>
            </li>

            <li class="sidebar-nav-item">
                <a href="<?= baseUrl('notifications') ?>" class="sidebar-nav-link <?= ($_GET['url'] ?? '') === 'notifications' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                    </span>
                    <span>Notificaciones</span>
                </a>
            </li>

            <?php if (isAdmin()): ?>
            <li class="sidebar-nav-item" style="margin-top: 2rem;">
                <a href="<?= baseUrl('users') ?>" class="sidebar-nav-link <?= ($_GET['url'] ?? '') === 'users' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    <span>Usuarios</span>
                </a>
            </li>

            <li class="sidebar-nav-item">
                <a href="<?= baseUrl('settings') ?>" class="sidebar-nav-link <?= ($_GET['url'] ?? '') === 'settings' ? 'active' : '' ?>">
                    <span class="sidebar-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M12 1v6m0 6v6m5.5-13.5l-4.24 4.24m-2.52 2.52L6.5 16.5m11-11l-4.24 4.24m-2.52 2.52L6.5 6.5m11 11l-4.24-4.24m-2.52-2.52L6.5 16.5"/>
                        </svg>
                    </span>
                    <span>Configuración</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
</aside>

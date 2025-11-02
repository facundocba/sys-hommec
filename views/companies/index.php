<?php
include __DIR__ . '/../layouts/header.php';
?>

<style>
    .page-header {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%
        );
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 24px;
        padding: 0;
        margin-bottom: 2rem;
        box-shadow:
            0 20px 60px rgba(56, 73, 89, 0.08),
            0 4px 20px rgba(136, 219, 242, 0.06),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg,
            transparent 0%,
            var(--stormy-cyan) 20%,
            var(--stormy-blue) 50%,
            var(--stormy-cyan) 80%,
            transparent 100%
        );
        opacity: 0.8;
    }

    .page-header::after {
        content: '';
        position: absolute;
        top: 0;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle,
            rgba(136, 219, 242, 0.08) 0%,
            transparent 70%
        );
        pointer-events: none;
        animation: pulse-gentle 8s ease-in-out infinite;
    }

    @keyframes pulse-gentle {
        0%, 100% {
            transform: scale(1) translateY(0);
            opacity: 0.6;
        }
        50% {
            transform: scale(1.1) translateY(-10px);
            opacity: 0.8;
        }
    }

    .page-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 2;
        padding: 2.5rem 3rem;
    }

    .page-header-left {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .page-header-icon {
        width: 72px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg,
            var(--stormy-blue) 0%,
            var(--stormy-cyan) 100%
        );
        border-radius: 20px;
        box-shadow:
            0 8px 24px rgba(106, 137, 167, 0.25),
            0 2px 8px rgba(136, 219, 242, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .page-header-icon::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.2) 0%,
            transparent 100%
        );
    }

    .page-header-icon svg {
        width: 36px;
        height: 36px;
        stroke: var(--white);
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    .page-header-text h1 {
        margin: 0;
        font-size: 2.25rem;
        font-weight: 800;
        background: linear-gradient(135deg,
            var(--stormy-dark) 0%,
            var(--stormy-blue) 60%,
            var(--stormy-cyan) 100%
        );
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.03em;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .page-header-text p {
        color: var(--stormy-blue);
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        opacity: 0.9;
    }

    .page-header-text p::before {
        content: '';
        width: 4px;
        height: 4px;
        background: var(--stormy-cyan);
        border-radius: 50%;
        display: inline-block;
    }

    .btn-new {
        background: linear-gradient(135deg,
            var(--stormy-blue) 0%,
            var(--stormy-cyan) 100%
        );
        color: var(--white);
        border: none;
        padding: 1rem 2rem;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow:
            0 8px 24px rgba(106, 137, 167, 0.3),
            0 2px 8px rgba(136, 219, 242, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .btn-new::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }

    .btn-new:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-new:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow:
            0 12px 32px rgba(106, 137, 167, 0.4),
            0 4px 12px rgba(136, 219, 242, 0.3);
    }

    .btn-new:active {
        transform: translateY(-1px) scale(0.98);
    }

    .btn-new svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        position: relative;
        z-index: 1;
    }

    .btn-new span {
        position: relative;
        z-index: 1;
    }

    .filters-card {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%
        );
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 24px;
        padding: 0;
        margin-bottom: 2rem;
        box-shadow:
            0 20px 60px rgba(56, 73, 89, 0.06),
            0 4px 20px rgba(136, 219, 242, 0.04),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        position: relative;
        overflow: hidden;
    }

    .filters-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg,
            transparent 0%,
            rgba(136, 219, 242, 0.3) 25%,
            rgba(106, 137, 167, 0.3) 50%,
            rgba(136, 219, 242, 0.3) 75%,
            transparent 100%
        );
    }

    .filters-card::after {
        content: '';
        position: absolute;
        bottom: -50%;
        left: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle,
            rgba(189, 221, 252, 0.06) 0%,
            transparent 70%
        );
        pointer-events: none;
    }

    .filters-header {
        padding: 2rem 2.5rem 1.5rem;
        border-bottom: 1px solid rgba(136, 219, 242, 0.15);
        position: relative;
        z-index: 2;
    }

    .filters-header-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .filters-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg,
            rgba(106, 137, 167, 0.12) 0%,
            rgba(136, 219, 242, 0.08) 100%
        );
        border-radius: 14px;
        border: 1px solid rgba(136, 219, 242, 0.2);
    }

    .filters-icon svg {
        width: 22px;
        height: 22px;
        stroke: var(--stormy-blue);
    }

    .filters-title {
        font-size: 1.125rem;
        font-weight: 700;
        background: linear-gradient(135deg,
            var(--stormy-dark) 0%,
            var(--stormy-blue) 100%
        );
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .filters-body {
        padding: 2rem 2.5rem;
        position: relative;
        z-index: 2;
    }

    .filters-row {
        display: grid;
        grid-template-columns: 3fr 1.5fr auto;
        gap: 1.5rem;
        align-items: end;
    }

    .form-group {
        margin-bottom: 0;
        position: relative;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        color: var(--stormy-dark);
        font-weight: 700;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-label svg {
        width: 16px;
        height: 16px;
        stroke: var(--stormy-cyan);
        opacity: 0.7;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1.25rem;
        font-size: 0.9375rem;
        border: 2px solid rgba(136, 219, 242, 0.2);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.9);
        color: var(--stormy-dark);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(56, 73, 89, 0.03);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--stormy-cyan);
        background: rgba(255, 255, 255, 1);
        box-shadow:
            0 0 0 4px rgba(136, 219, 242, 0.12),
            0 4px 16px rgba(136, 219, 242, 0.15);
        transform: translateY(-2px);
    }

    .form-control::placeholder {
        color: rgba(106, 137, 167, 0.5);
        font-weight: 400;
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%236A89A7' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 3rem;
        cursor: pointer;
    }

    .btn-filter {
        background: linear-gradient(135deg,
            var(--stormy-dark) 0%,
            var(--stormy-blue) 100%
        );
        color: var(--white);
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        white-space: nowrap;
        box-shadow:
            0 6px 20px rgba(56, 73, 89, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
    }

    .btn-filter::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.5s ease, height 0.5s ease;
    }

    .btn-filter:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-filter:hover {
        transform: translateY(-3px);
        box-shadow:
            0 10px 28px rgba(56, 73, 89, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.15);
    }

    .btn-filter:active {
        transform: translateY(-1px);
    }

    .btn-filter svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        position: relative;
        z-index: 1;
    }

    .btn-filter span {
        position: relative;
        z-index: 1;
    }

    .table-wrapper {
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(136, 219, 242, 0.3);
        border-radius: 20px;
        overflow: hidden;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.12),
            inset 0 1px 0 rgba(255, 255, 255, 1);
    }

    .table {
        margin: 0;
    }

    .table thead {
        background: linear-gradient(135deg, rgba(106, 137, 167, 0.1) 0%, rgba(136, 219, 242, 0.08) 100%);
        border-bottom: 2px solid rgba(136, 219, 242, 0.3);
    }

    .table th {
        padding: 1.25rem 1.5rem;
        font-weight: 700;
        color: var(--stormy-dark);
        text-transform: uppercase;
        font-size: 0.8125rem;
        letter-spacing: 0.8px;
    }

    .table td {
        padding: 1.25rem 1.5rem;
        color: var(--stormy-dark);
        font-size: 0.9375rem;
        border-bottom: 1px solid rgba(136, 219, 242, 0.15);
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.08) 0%, rgba(189, 221, 252, 0.05) 100%);
    }

    .company-name {
        font-weight: 600;
        color: var(--stormy-dark);
    }

    .actions-cell {
        display: flex;
        gap: 0.5rem;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: 2px solid;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .btn-icon svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
    }

    .btn-edit {
        background: rgba(106, 137, 167, 0.1);
        border-color: rgba(106, 137, 167, 0.3);
        color: var(--stormy-blue);
    }

    .btn-edit:hover {
        background: var(--stormy-blue);
        border-color: var(--stormy-blue);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(106, 137, 167, 0.3);
    }

    .btn-delete {
        background: rgba(239, 68, 68, 0.1);
        border-color: rgba(239, 68, 68, 0.3);
        color: var(--danger);
    }

    .btn-delete:hover {
        background: var(--danger);
        border-color: var(--danger);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 2rem;
        stroke: var(--stormy-cyan);
        opacity: 0.4;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.75rem;
    }

    .empty-state p {
        color: var(--stormy-blue);
        font-size: 1rem;
        font-weight: 500;
    }

    @media (max-width: 1024px) {
        .filters-row {
            grid-template-columns: 1fr;
        }

        .btn-filter {
            justify-content: center;
        }

        .page-header-content {
            flex-direction: column;
            gap: 1.5rem;
            text-align: center;
        }

        .page-header-left {
            flex-direction: column;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-header-left">
            <div class="page-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
            </div>
            <div class="page-header-text">
                <h1>Directorio Empresarial</h1>
                <p>Gestione las empresas y organizaciones del sistema</p>
            </div>
        </div>
        <a href="<?= baseUrl('companies/create') ?>" class="btn-new">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            <span>Nueva Empresa</span>
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="filters-card">
    <div class="filters-header">
        <div class="filters-header-content">
            <div class="filters-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
            </div>
            <h3 class="filters-title">Filtrar Empresas</h3>
        </div>
    </div>
    <div class="filters-body">
        <form method="GET" action="<?= baseUrl('companies') ?>">
            <div class="filters-row">
                <div class="form-group">
                    <label class="form-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                        Buscar
                    </label>
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Nombre o email..."
                        value="<?= htmlspecialchars($filters['search']) ?>"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                        Estado
                    </label>
                    <select name="estado" class="form-control form-select">
                        <option value="">Todos</option>
                        <option value="activo" <?= $filters['estado'] === 'activo' ? 'selected' : '' ?>>
                            Activo
                        </option>
                        <option value="inactivo" <?= $filters['estado'] === 'inactivo' ? 'selected' : '' ?>>
                            Inactivo
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn-filter">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                        <span>Filtrar</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de empresas -->
<div class="table-wrapper">
    <?php if (empty($companies)): ?>
        <div class="empty-state">
            <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
            <h3>No se encontraron empresas</h3>
            <p>Intente ajustar los filtros de búsqueda</p>
        </div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $company): ?>
                    <tr>
                        <td>
                            <strong class="company-name"><?= htmlspecialchars($company['nombre']) ?></strong>
                        </td>
                        <td><?= htmlspecialchars($company['email']) ?></td>
                        <td><?= htmlspecialchars($company['telefono'] ?? '-') ?></td>
                        <td>
                            <?php if ($company['estado'] === 'activo'): ?>
                                <span class="badge badge-success">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="<?= baseUrl('prestaciones-empresas/config/' . $company['id']) ?>"
                                   class="btn btn-info btn-sm btn-icon"
                                   title="Configurar Prestaciones"
                                   style="background: rgba(99, 102, 241, 0.1); border-color: rgba(99, 102, 241, 0.3); color: #6366f1;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        <path d="M9 12h6m-6 4h6"/>
                                    </svg>
                                </a>
                                <a href="<?= baseUrl('companies/edit/' . $company['id']) ?>"
                                   class="btn btn-secondary btn-sm btn-icon btn-edit"
                                   title="Editar Empresa">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>

                                <?php if (getCurrentUser()['rol'] === 'administrador'): ?>
                                    <form method="POST"
                                          action="<?= baseUrl('companies/delete/' . $company['id']) ?>"
                                          style="display: inline;"
                                          onsubmit="return confirmDelete('¿Está seguro de desactivar esta empresa?')">
                                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                        <button type="submit"
                                                class="btn btn-danger btn-sm btn-icon btn-delete"
                                                title="Desactivar">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                <line x1="10" y1="11" x2="10" y2="17"/>
                                                <line x1="14" y1="11" x2="14" y2="17"/>
                                            </svg>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

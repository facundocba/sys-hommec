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
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 24px;
        padding: 2.5rem 3rem;
        margin-bottom: 2rem;
        box-shadow:
            0 20px 60px rgba(56, 73, 89, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 1);
    }

    .page-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header h1 {
        font-size: 2.25rem;
        font-weight: 800;
        background: linear-gradient(135deg,
            var(--stormy-dark) 0%,
            var(--stormy-blue) 60%,
            var(--stormy-cyan) 100%
        );
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }

    .filters-card {
        background: rgba(255, 255, 255, 0.98);
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 24px rgba(56, 73, 89, 0.06);
    }

    .filters-row {
        display: grid;
        grid-template-columns: 2fr 1fr auto;
        gap: 1.5rem;
        align-items: end;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--stormy-dark);
        font-size: 0.875rem;
        text-transform: uppercase;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1.25rem;
        border: 2px solid rgba(136, 219, 242, 0.2);
        border-radius: 12px;
        font-size: 0.9375rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--stormy-cyan);
        box-shadow: 0 0 0 4px rgba(136, 219, 242, 0.12);
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%236A89A7' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 3rem;
    }

    .btn-filter {
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(106, 137, 167, 0.3);
    }

    .btn-new {
        background: linear-gradient(135deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);
        color: white;
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-new:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(136, 219, 242, 0.4);
    }

    .obras-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .obra-card {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%
        );
        backdrop-filter: blur(20px);
        border: 2px solid rgba(136, 219, 242, 0.25);
        border-radius: 24px;
        overflow: hidden;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.1),
            0 4px 16px rgba(136, 219, 242, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .obra-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg,
            var(--stormy-cyan) 0%,
            var(--stormy-blue) 50%,
            var(--stormy-cyan) 100%
        );
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .obra-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow:
            0 24px 64px rgba(136, 219, 242, 0.2),
            0 12px 32px rgba(56, 73, 89, 0.15);
        border-color: var(--stormy-cyan);
    }

    .obra-card-header {
        padding: 2rem 2rem 1.5rem 2rem;
        background: linear-gradient(135deg,
            rgba(136, 219, 242, 0.08) 0%,
            rgba(189, 221, 252, 0.04) 100%
        );
        position: relative;
    }

    .obra-header-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .obra-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(136, 219, 242, 0.4);
        flex-shrink: 0;
    }

    .obra-icon svg {
        width: 30px;
        height: 30px;
        stroke: white;
        stroke-width: 2.5;
    }

    .obra-title-section {
        flex: 1;
        min-width: 0;
    }

    .obra-name {
        font-size: 1.375rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-dark), var(--stormy-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0 0 0.5rem 0;
        line-height: 1.3;
        word-wrap: break-word;
    }

    .obra-sigla {
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        color: white;
        padding: 0.375rem 1rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 700;
        letter-spacing: 1px;
        display: inline-block;
        box-shadow: 0 4px 12px rgba(136, 219, 242, 0.3);
        text-transform: uppercase;
    }

    .obra-card-body {
        padding: 1.75rem 2rem;
        min-height: 160px;
    }

    .info-item {
        display: grid;
        grid-template-columns: 32px 1fr;
        gap: 1rem;
        padding: 0.875rem;
        margin-bottom: 0.5rem;
        color: var(--stormy-dark);
        font-size: 0.9375rem;
        background: rgba(136, 219, 242, 0.04);
        border-radius: 12px;
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: rgba(136, 219, 242, 0.08);
        border-left-color: var(--stormy-cyan);
        transform: translateX(4px);
    }

    .info-item-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.15), rgba(189, 221, 252, 0.1));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-item-icon svg {
        width: 18px;
        height: 18px;
        stroke: var(--stormy-cyan);
        flex-shrink: 0;
    }

    .info-item-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        min-width: 0;
    }

    .info-label {
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--stormy-blue);
    }

    .info-value {
        font-size: 0.9375rem;
        color: var(--stormy-dark);
        word-wrap: break-word;
    }

    .obra-card-footer {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg,
            rgba(136, 219, 242, 0.05) 0%,
            rgba(189, 221, 252, 0.03) 100%
        );
        border-top: 2px solid rgba(136, 219, 242, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .badge-estado {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.8125rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-estado.activo {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .badge-estado.inactivo {
        background: #e5e7eb;
        color: #6b7280;
    }

    .obra-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: 2px solid rgba(136, 219, 242, 0.3);
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-action svg {
        width: 18px;
        height: 18px;
    }

    .btn-action.edit {
        color: var(--stormy-blue);
    }

    .btn-action.edit:hover {
        background: var(--stormy-blue);
        color: white;
        border-color: var(--stormy-blue);
        transform: translateY(-2px);
    }

    .btn-action.delete {
        color: #ef4444;
    }

    .btn-action.delete:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--stormy-blue);
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(56, 73, 89, 0.7);
        backdrop-filter: blur(8px);
        z-index: 9999;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .modal-content {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%
        );
        backdrop-filter: blur(20px);
        border: 2px solid rgba(136, 219, 242, 0.3);
        border-radius: 24px;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 24px 64px rgba(56, 73, 89, 0.3);
        animation: slideUp 0.3s ease;
        overflow: hidden;
    }

    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 2rem 2rem 1.5rem 2rem;
        border-bottom: 2px solid rgba(136, 219, 242, 0.15);
        background: linear-gradient(135deg,
            rgba(239, 68, 68, 0.08) 0%,
            transparent 100%
        );
    }

    .modal-header-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .modal-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
        flex-shrink: 0;
    }

    .modal-icon svg {
        width: 30px;
        height: 30px;
        stroke: white;
        stroke-width: 2.5;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-dark), #ef4444);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-message {
        color: var(--stormy-dark);
        font-size: 1rem;
        line-height: 1.6;
        margin: 0 0 1rem 0;
    }

    .modal-warning {
        background: linear-gradient(135deg,
            rgba(239, 68, 68, 0.08) 0%,
            rgba(239, 68, 68, 0.04) 100%
        );
        border-left: 4px solid #ef4444;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        margin-top: 1rem;
    }

    .modal-warning p {
        margin: 0;
        color: #991b1b;
        font-size: 0.9375rem;
        font-weight: 600;
    }

    .modal-footer {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg,
            rgba(136, 219, 242, 0.05) 0%,
            rgba(189, 221, 252, 0.03) 100%
        );
        border-top: 2px solid rgba(136, 219, 242, 0.15);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .modal-btn {
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9375rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-btn-cancel {
        background: white;
        color: var(--stormy-dark);
        border: 2px solid rgba(136, 219, 242, 0.3);
    }

    .modal-btn-cancel:hover {
        background: rgba(136, 219, 242, 0.1);
        border-color: var(--stormy-cyan);
        transform: translateY(-2px);
    }

    .modal-btn-confirm {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .modal-btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
    }

    @media (max-width: 768px) {
        .page-header-content {
            flex-direction: column;
            gap: 1rem;
        }

        .filters-row {
            grid-template-columns: 1fr;
        }

        .obras-grid {
            grid-template-columns: 1fr;
        }

        .modal-footer {
            flex-direction: column-reverse;
        }

        .modal-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue)); border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(106, 137, 167, 0.35);">
                <svg style="width: 28px; height: 28px; stroke: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                </svg>
            </div>
            <div>
                <h1>Obras Sociales</h1>
                <p style="margin: 0.5rem 0 0 0; color: var(--stormy-blue);">Gestión de obras sociales y seguros médicos</p>
            </div>
        </div>
        <a href="<?= baseUrl('obras-sociales/create') ?>" class="btn-new">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Nueva Obra Social
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="filters-card">
    <form method="GET" action="<?= baseUrl('obras-sociales') ?>">
        <div class="filters-row">
            <div class="form-group">
                <label>Buscar</label>
                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o sigla..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado" class="form-control form-select">
                    <option value="">Todos</option>
                    <option value="activo" <?= ($_GET['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="inactivo" <?= ($_GET['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-filter">Filtrar</button>
            </div>
        </div>
    </form>
</div>

<!-- Grid de Obras Sociales -->
<?php if (empty($obrasSociales)): ?>
    <div class="empty-state">
        <h3>No se encontraron obras sociales</h3>
        <p>Intente ajustar los filtros de búsqueda</p>
    </div>
<?php else: ?>
    <div class="obras-grid">
        <?php foreach ($obrasSociales as $obra): ?>
            <div class="obra-card">
                <div class="obra-card-header">
                    <div class="obra-header-top">
                        <div class="obra-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                            </svg>
                        </div>
                        <div class="obra-title-section">
                            <h3 class="obra-name"><?= htmlspecialchars($obra['nombre']) ?></h3>
                            <?php if (!empty($obra['sigla'])): ?>
                                <span class="obra-sigla"><?= htmlspecialchars($obra['sigla']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="obra-card-body">
                    <?php if (!empty($obra['telefono'])): ?>
                        <div class="info-item">
                            <div class="info-item-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </div>
                            <div class="info-item-content">
                                <span class="info-label">Teléfono</span>
                                <span class="info-value"><?= htmlspecialchars($obra['telefono']) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($obra['email'])): ?>
                        <div class="info-item">
                            <div class="info-item-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </div>
                            <div class="info-item-content">
                                <span class="info-label">Email</span>
                                <span class="info-value"><?= htmlspecialchars($obra['email']) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($obra['direccion'])): ?>
                        <div class="info-item">
                            <div class="info-item-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div class="info-item-content">
                                <span class="info-label">Dirección</span>
                                <span class="info-value"><?= htmlspecialchars($obra['direccion']) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($obra['telefono']) && empty($obra['email']) && empty($obra['direccion'])): ?>
                        <div style="text-align: center; padding: 2rem; color: var(--stormy-blue); opacity: 0.6;">
                            <svg style="width: 48px; height: 48px; margin: 0 auto 1rem; stroke: currentColor;" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                            <p style="margin: 0; font-size: 0.875rem;">Sin información de contacto</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="obra-card-footer">
                    <span class="badge-estado <?= $obra['estado'] ?>">
                        <?= ucfirst($obra['estado']) ?>
                    </span>
                    <div class="obra-actions">
                        <a href="<?= baseUrl('obras-sociales/edit/' . $obra['id']) ?>" class="btn-action edit" title="Editar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </a>
                        <?php if (hasRole('administrador')): ?>
                            <button type="button" class="btn-action delete" onclick="openDeleteModal(<?= $obra['id'] ?>, '<?= htmlspecialchars($obra['nombre'], ENT_QUOTES) ?>')" title="Desactivar">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Form para eliminar -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
</form>

<!-- Modal de confirmación -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-header-content">
                <div class="modal-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <h3 class="modal-title">Desactivar Obra Social</h3>
            </div>
        </div>

        <div class="modal-body">
            <p class="modal-message">
                ¿Está seguro que desea desactivar la obra social <strong id="modalObraNombre"></strong>?
            </p>
            <div class="modal-warning">
                <p>⚠️ La obra social será marcada como inactiva y no aparecerá en los listados activos.</p>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="closeDeleteModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
                Cancelar
            </button>
            <button type="button" class="modal-btn modal-btn-confirm" onclick="submitDelete()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                </svg>
                Desactivar
            </button>
        </div>
    </div>
</div>

<div id="toast-container"></div>

<script>
let deleteObraId = null;
let deleteObraNombre = null;

function openDeleteModal(id, nombre) {
    deleteObraId = id;
    deleteObraNombre = nombre;
    document.getElementById('modalObraNombre').textContent = nombre;
    document.getElementById('deleteModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    document.body.style.overflow = '';
    deleteObraId = null;
    deleteObraNombre = null;
}

function submitDelete() {
    if (deleteObraId) {
        const form = document.getElementById('deleteForm');
        form.action = '<?= baseUrl('obras-sociales/delete/') ?>' + deleteObraId;
        form.submit();
    }
}

// Cerrar modal al hacer clic fuera
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('deleteModal').classList.contains('active')) {
        closeDeleteModal();
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

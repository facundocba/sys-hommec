<?php
include __DIR__ . '/../layouts/header.php';
?>

<style>
    .page-header {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%
        );
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
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.75rem;
        margin-bottom: 2rem;
    }

    .obra-card {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%
        );
        border: 2px solid rgba(136, 219, 242, 0.25);
        border-radius: 20px;
        overflow: hidden;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.08),
            0 4px 16px rgba(136, 219, 242, 0.06);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        display: flex;
        flex-direction: column;
        min-height: 220px;
    }

    .obra-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg,
            var(--stormy-cyan) 0%,
            var(--stormy-blue) 50%,
            var(--stormy-cyan) 100%
        );
    }

    .obra-card:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow:
            0 20px 48px rgba(136, 219, 242, 0.18),
            0 12px 32px rgba(56, 73, 89, 0.12);
        border-color: var(--stormy-cyan);
    }

    .obra-card-header {
        padding: 2rem 2rem 1.75rem;
        background: linear-gradient(135deg,
            rgba(136, 219, 242, 0.06) 0%,
            rgba(189, 221, 252, 0.03) 100%
        );
        position: relative;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .obra-icon {
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 12px 32px rgba(136, 219, 242, 0.35),
            inset 0 2px 0 rgba(255, 255, 255, 0.2);
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .obra-icon::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.15) 0%,
            transparent 100%
        );
    }

    .obra-icon svg {
        width: 36px;
        height: 36px;
        stroke: white;
        stroke-width: 2.5;
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    .obra-title-section {
        width: 100%;
    }

    .obra-name {
        font-size: 1.375rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 0.875rem 0;
        line-height: 1.3;
        word-wrap: break-word;
        letter-spacing: -0.02em;
    }

    .obra-sigla {
        background: linear-gradient(135deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        display: inline-block;
        box-shadow:
            0 6px 16px rgba(136, 219, 242, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        text-transform: uppercase;
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
        gap: 1rem;
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

    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%
        );
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 20px;
        margin-top: 1.5rem;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 1);
    }

    .pagination-info {
        color: var(--stormy-blue);
        font-size: 0.9375rem;
        font-weight: 600;
    }

    .pagination {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pagination-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 1rem;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(136, 219, 242, 0.2);
        border-radius: 10px;
        color: var(--stormy-dark);
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(56, 73, 89, 0.03);
    }

    .pagination-btn:hover:not(.disabled):not(.active) {
        background: rgba(136, 219, 242, 0.1);
        border-color: var(--stormy-cyan);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(136, 219, 242, 0.2);
    }

    .pagination-btn.active {
        background: linear-gradient(135deg,
            var(--stormy-blue) 0%,
            var(--stormy-cyan) 100%
        );
        border-color: var(--stormy-cyan);
        color: var(--white);
        box-shadow:
            0 4px 12px rgba(106, 137, 167, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    .pagination-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }

    .pagination-btn svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
    }

    .pagination-ellipsis {
        display: inline-flex;
        align-items: center;
        padding: 0 0.5rem;
        color: var(--stormy-blue);
        font-weight: 600;
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

        .pagination-wrapper {
            flex-direction: column;
            gap: 1rem;
        }

        .pagination-info {
            text-align: center;
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

<?php if (!empty($obrasSociales) && $pagination['total_pages'] > 1): ?>
<!-- Paginación -->
<div class="pagination-wrapper">
    <div class="pagination-info">
        Mostrando <?= $pagination['offset'] + 1 ?> - <?= min($pagination['offset'] + $pagination['items_per_page'], $pagination['total_items']) ?> de <?= $pagination['total_items'] ?> obras sociales
    </div>

    <ul class="pagination">
        <!-- Botón Anterior -->
        <li>
            <?php if ($pagination['current_page'] > 1): ?>
                <a href="<?= baseUrl('obras-sociales?' . http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] - 1]))) ?>"
                   class="pagination-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </a>
            <?php else: ?>
                <span class="pagination-btn disabled">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </span>
            <?php endif; ?>
        </li>

        <?php
        // Lógica de paginación con elipsis
        $range = 2;
        $start = max(1, $pagination['current_page'] - $range);
        $end = min($pagination['total_pages'], $pagination['current_page'] + $range);

        // Mostrar primera página
        if ($start > 1): ?>
            <li>
                <a href="<?= baseUrl('obras-sociales?' . http_build_query(array_merge($_GET, ['page' => 1]))) ?>"
                   class="pagination-btn">1</a>
            </li>
            <?php if ($start > 2): ?>
                <li class="pagination-ellipsis">...</li>
            <?php endif;
        endif;

        // Mostrar páginas en el rango
        for ($i = $start; $i <= $end; $i++): ?>
            <li>
                <?php if ($i == $pagination['current_page']): ?>
                    <span class="pagination-btn active"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= baseUrl('obras-sociales?' . http_build_query(array_merge($_GET, ['page' => $i]))) ?>"
                       class="pagination-btn"><?= $i ?></a>
                <?php endif; ?>
            </li>
        <?php endfor;

        // Mostrar última página
        if ($end < $pagination['total_pages']): ?>
            <?php if ($end < $pagination['total_pages'] - 1): ?>
                <li class="pagination-ellipsis">...</li>
            <?php endif; ?>
            <li>
                <a href="<?= baseUrl('obras-sociales?' . http_build_query(array_merge($_GET, ['page' => $pagination['total_pages']]))) ?>"
                   class="pagination-btn"><?= $pagination['total_pages'] ?></a>
            </li>
        <?php endif; ?>

        <!-- Botón Siguiente -->
        <li>
            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                <a href="<?= baseUrl('obras-sociales?' . http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] + 1]))) ?>"
                   class="pagination-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </a>
            <?php else: ?>
                <span class="pagination-btn disabled">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </span>
            <?php endif; ?>
        </li>
    </ul>
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

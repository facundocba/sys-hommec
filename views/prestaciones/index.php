<?php
include __DIR__ . '/../layouts/header.php';
?>

<style>
    .prestaciones-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        padding: 2rem 0;
    }

    .prestacion-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.95) 100%);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(136, 219, 242, 0.25);
        border-radius: 18px;
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(136, 219, 242, 0.1);
    }

    .prestacion-card:hover {
        transform: translateY(-4px);
        border-color: var(--stormy-cyan);
        box-shadow: 0 8px 30px rgba(136, 219, 242, 0.2);
    }

    .prestacion-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(136, 219, 242, 0.15);
    }

    .prestacion-title h5 {
        color: var(--stormy-dark);
        font-weight: 600;
        margin-bottom: 0;
        font-size: 1.15rem;
    }

    .prestacion-icon {
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .prestacion-body {
        margin-bottom: 1rem;
        color: #666;
        font-size: 0.9rem;
        line-height: 1.6;
        min-height: 60px;
    }

    .prestacion-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 2px solid rgba(136, 219, 242, 0.15);
    }

    .badge-estado {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .filter-section {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        border: 1px solid rgba(136, 219, 242, 0.2);
        box-shadow: 0 2px 8px rgba(136, 219, 242, 0.08);
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -0.5rem;
    }

    .row.g-3 > * {
        padding: 0 0.5rem;
        margin-bottom: 1rem;
    }

    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }

    .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }

    .col-md-2 {
        flex: 0 0 16.666667%;
        max-width: 16.666667%;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        font-size: 0.875rem;
        color: #475569;
    }

    .form-control, .form-select {
        display: block;
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 0.95rem;
        font-weight: 400;
        line-height: 1.5;
        color: #475569;
        background-color: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--stormy-cyan);
        outline: 0;
        box-shadow: 0 0 0 3px rgba(136, 219, 242, 0.1);
    }

    .d-flex {
        display: flex !important;
    }

    .align-items-end {
        align-items: flex-end !important;
    }

    .w-100 {
        width: 100% !important;
    }

    .alert {
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 12px;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    .btn-group {
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
        font-size: 1rem;
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
        border-color: rgba(239, 68, 68, 0.3);
    }

    .btn-action.delete:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
        transform: translateY(-2px);
    }

    .me-1 {
        margin-right: 0.25rem !important;
    }

    .me-2 {
        margin-right: 0.5rem !important;
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
        from { opacity: 0; }
        to { opacity: 1; }
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
        .col-md-6, .col-md-4, .col-md-2 {
            flex: 0 0 100%;
            max-width: 100%;
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

<!-- Page Header -->
<div class="top-bar">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--stormy-dark);">
            <i class="bi bi-heart-pulse me-2"></i>
            <?php echo $title; ?>
        </h1>
    </div>
    <a href="<?php echo baseUrl('prestaciones/create'); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Nueva Prestación
    </a>
</div>

<!-- Filtros -->
<div class="filter-section">
    <form method="GET" action="<?php echo baseUrl('prestaciones'); ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control"
                       placeholder="Buscar por nombre..."
                       value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="activo" <?php echo ($_GET['estado'] ?? '') === 'activo' ? 'selected' : ''; ?>>Activo</option>
                    <option value="inactivo" <?php echo ($_GET['estado'] ?? '') === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i>
                    Filtrar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Grid de Prestaciones -->
<?php if (empty($prestaciones)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        No se encontraron prestaciones.
    </div>
<?php else: ?>
    <div class="prestaciones-grid">
        <?php foreach ($prestaciones as $prestacion): ?>
            <div class="prestacion-card">
                <div class="prestacion-header">
                    <div class="prestacion-title">
                        <h5><?php echo htmlspecialchars($prestacion['nombre']); ?></h5>
                    </div>
                    <div class="prestacion-icon">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                </div>

                <div class="prestacion-body">
                    <?php if (!empty($prestacion['descripcion'])): ?>
                        <?php echo htmlspecialchars($prestacion['descripcion']); ?>
                    <?php else: ?>
                        <em class="text-muted">Sin descripción</em>
                    <?php endif; ?>
                </div>

                <div class="prestacion-footer">
                    <span class="badge-estado <?php echo $prestacion['estado'] === 'activo' ? 'bg-success' : 'bg-secondary'; ?>">
                        <?php echo ucfirst($prestacion['estado']); ?>
                    </span>
                    <div class="btn-group">
                        <a href="<?php echo baseUrl('prestaciones/edit/' . $prestacion['id']); ?>"
                           class="btn-action edit" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php if (hasRole('administrador')): ?>
                            <button type="button" class="btn-action delete"
                                    onclick="openDeleteModal(<?php echo $prestacion['id']; ?>, '<?php echo htmlspecialchars($prestacion['nombre'], ENT_QUOTES); ?>')"
                                    title="Desactivar">
                                <i class="bi bi-trash"></i>
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
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
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
                <h3 class="modal-title">Desactivar Prestación</h3>
            </div>
        </div>

        <div class="modal-body">
            <p class="modal-message">
                ¿Está seguro que desea desactivar la prestación <strong id="modalPrestacionNombre"></strong>?
            </p>
            <div class="modal-warning">
                <p>⚠️ La prestación será marcada como inactiva y no aparecerá en los listados activos.</p>
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

<script>
let deletePrestacionId = null;
let deletePrestacionNombre = null;

function openDeleteModal(id, nombre) {
    deletePrestacionId = id;
    deletePrestacionNombre = nombre;
    document.getElementById('modalPrestacionNombre').textContent = nombre;
    document.getElementById('deleteModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
    document.body.style.overflow = '';
    deletePrestacionId = null;
    deletePrestacionNombre = null;
}

function submitDelete() {
    if (deletePrestacionId) {
        const form = document.getElementById('deleteForm');
        form.action = '<?php echo baseUrl('prestaciones/delete/'); ?>' + deletePrestacionId;
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

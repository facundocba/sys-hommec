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
        display: inline-flex;
        border-radius: 8px;
        overflow: hidden;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .btn-outline-primary {
        color: var(--stormy-cyan);
        border: 1px solid var(--stormy-cyan);
        background-color: transparent;
    }

    .btn-outline-primary:hover {
        color: white;
        background-color: var(--stormy-cyan);
    }

    .btn-outline-danger {
        color: #dc3545;
        border: 1px solid #dc3545;
        background-color: transparent;
    }

    .btn-outline-danger:hover {
        color: white;
        background-color: #dc3545;
    }

    .me-1 {
        margin-right: 0.25rem !important;
    }

    .me-2 {
        margin-right: 0.5rem !important;
    }

    @media (max-width: 768px) {
        .col-md-6, .col-md-4, .col-md-2 {
            flex: 0 0 100%;
            max-width: 100%;
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
                    <div class="btn-group" role="group">
                        <a href="<?php echo baseUrl('prestaciones/edit/' . $prestacion['id']); ?>"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php if (hasRole('administrador')): ?>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete(<?php echo $prestacion['id']; ?>)">
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

<script>
    function confirmDelete(id) {
        if (confirm('¿Está seguro de desactivar esta prestación?')) {
            const form = document.getElementById('deleteForm');
            form.action = '<?php echo baseUrl('prestaciones/delete/'); ?>' + id;
            form.submit();
        }
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

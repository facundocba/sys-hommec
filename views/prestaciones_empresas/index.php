<?php include __DIR__ . '/../layouts/header.php'; ?>

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
        box-shadow: 0 20px 60px rgba(56, 73, 89, 0.08);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.08) 0%, rgba(189, 221, 252, 0.05) 100%);
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
    }

    .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: var(--stormy-blue);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-dark), var(--stormy-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .filters-card {
        background: white;
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .table-card {
        background: white;
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(136, 219, 242, 0.1);
    }

    .table {
        margin: 0;
    }

    .table thead {
        background: linear-gradient(135deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);
        color: white;
    }

    .table thead th {
        border: none;
        font-weight: 600;
        padding: 1rem;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: rgba(136, 219, 242, 0.05);
    }

    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .badge-success {
        background: #10b981;
        color: white;
    }

    .badge-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-action {
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--stormy-blue);
    }
</style>

<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="margin: 0; font-size: 2rem; font-weight: 800; background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Configuración de Prestaciones por Empresa
            </h1>
            <p style="margin: 0.5rem 0 0 0; color: var(--stormy-blue);">
                Gestiona los valores de las prestaciones para cada empresa
            </p>
        </div>
        <?php if (hasRole('administrador')): ?>
        <a href="<?= baseUrl('prestaciones-empresas/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Nueva Configuración
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Estadísticas -->
<?php if (!empty($stats)): ?>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Configuraciones</div>
        <div class="stat-value"><?= $stats['total'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Activas</div>
        <div class="stat-value" style="color: #10b981;"><?= $stats['activas'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Empresas</div>
        <div class="stat-value"><?= $stats['total_empresas'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Prestaciones</div>
        <div class="stat-value"><?= $stats['total_prestaciones'] ?></div>
    </div>
</div>
<?php endif; ?>

<!-- Filtros -->
<div class="filters-card">
    <form method="GET" action="<?= baseUrl('prestaciones-empresas') ?>">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Empresa</label>
                <select name="id_empresa" class="form-select">
                    <option value="">Todas las empresas</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?= $company['id'] ?>" <?= ($filters['id_empresa'] ?? '') == $company['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($company['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Prestación</label>
                <select name="id_tipo_prestacion" class="form-select">
                    <option value="">Todas las prestaciones</option>
                    <?php foreach ($tiposPrestacion as $tipo): ?>
                        <option value="<?= $tipo['id'] ?>" <?= ($filters['id_tipo_prestacion'] ?? '') == $tipo['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tipo['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="activo" <?= ($filters['estado'] ?? '') === 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="inactivo" <?= ($filters['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Búsqueda</label>
                <input type="text" name="search" class="form-control" placeholder="Buscar..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
            </div>
            <div class="col-md-1" style="display: flex; align-items: end;">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Tabla -->
<div class="table-card">
    <?php if (empty($prestacionesEmpresas)): ?>
        <div class="empty-state">
            <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
            <h3>No hay configuraciones</h3>
            <p>No se encontraron configuraciones con los filtros aplicados.</p>
            <?php if (hasRole('administrador')): ?>
                <a href="<?= baseUrl('prestaciones-empresas/create') ?>" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-lg me-1"></i>
                    Crear primera configuración
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Empresa</th>
                    <th>Prestación</th>
                    <?php if (!isCoordinator()): ?><th>Valor Empresa</th><?php endif; ?>
                    <th>Estado</th>
                    <th>Fecha Creación</th>
                    <?php if (hasRole('administrador')): ?>
                    <th>Acciones</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prestacionesEmpresas as $pe): ?>
                <tr>
                    <td><?= $pe['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($pe['empresa_nombre']) ?></strong>
                    </td>
                    <td><?= htmlspecialchars($pe['prestacion_nombre']) ?></td>
                    <?php if (!isCoordinator()): ?>
                    <td>
                        <strong style="color: #6366f1; font-size: 1.1rem;">
                            $<?= number_format($pe['valor_empresa'], 2) ?>
                        </strong>
                    </td>
                    <?php endif; ?>
                    <td>
                        <span class="badge badge-<?= $pe['estado'] === 'activo' ? 'success' : 'secondary' ?>">
                            <?= ucfirst($pe['estado']) ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($pe['fecha_creacion'])) ?></td>
                    <?php if (hasRole('administrador')): ?>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?= baseUrl('prestaciones-empresas/edit/' . $pe['id']) ?>" class="btn btn-sm btn-outline-primary btn-action" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-action" onclick="confirmDelete(<?= $pe['id'] ?>)" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Form para eliminar -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
</form>

<script>
function confirmDelete(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta configuración?')) {
        const form = document.getElementById('deleteForm');
        form.action = '<?= baseUrl('prestaciones-empresas/delete/') ?>' + id;
        form.submit();
    }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

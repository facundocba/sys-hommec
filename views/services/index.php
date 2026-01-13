<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
    .page-header {
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
        border: 1px solid rgba(136, 219, 242, 0.25);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg,
            var(--stormy-cyan) 0%,
            var(--stormy-light) 50%,
            var(--stormy-cyan) 100%
        );
    }

    .page-header-content {
        display: flex;
        align-items: center;
        gap: 2rem;
        position: relative;
        z-index: 1;
    }

    .page-header-icon {
        width: 72px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        box-shadow: 0 8px 24px rgba(106, 137, 167, 0.35);
        flex-shrink: 0;
    }

    .page-header-icon svg {
        width: 36px;
        height: 36px;
        stroke: var(--white);
    }

    .page-header-text {
        flex: 1;
    }

    .page-header-text h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--stormy-dark) 0%, var(--stormy-blue) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.02em;
    }

    .page-header-text p {
        color: var(--stormy-blue);
        margin: 0;
        font-size: 0.9375rem;
        font-weight: 500;
    }

    .filters-card {
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
        border: 1px solid rgba(136, 219, 242, 0.3);
        border-radius: 20px;
        padding: 0;
        margin-bottom: 2rem;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.12),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        overflow: hidden;
    }

    .filters-header {
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.12) 0%, rgba(189, 221, 252, 0.08) 100%);
        padding: 1.5rem 2rem;
        border-bottom: 2px solid rgba(136, 219, 242, 0.2);
    }

    .filters-header h2 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--stormy-dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .filters-header h2::before {
        content: '';
        width: 4px;
        height: 20px;
        background: linear-gradient(180deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);
        border-radius: 2px;
    }

    .filters-content {
        padding: 2rem;
    }

    .table-card {
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
        border: 1px solid rgba(136, 219, 242, 0.3);
        border-radius: 20px;
        padding: 0;
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.12),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        margin: 0;
    }

    .table thead {
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.15) 0%, rgba(189, 221, 252, 0.1) 100%);
    }

    .table thead th {
        padding: 1.25rem 1.5rem;
        font-weight: 700;
        color: var(--stormy-dark);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: none;
    }

    .table tbody td {
        padding: 1.25rem 1.5rem;
        color: var(--stormy-blue);
        font-weight: 500;
        border-top: 1px solid rgba(136, 219, 242, 0.15);
        vertical-align: middle;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: rgba(136, 219, 242, 0.05);
    }

    .badge {
        padding: 0.375rem 0.875rem;
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }

    .badge-success {
        background: linear-gradient(135deg, rgba(46, 204, 113, 0.15) 0%, rgba(46, 204, 113, 0.1) 100%);
        color: #27ae60;
        border: 1px solid rgba(46, 204, 113, 0.3);
    }

    .badge-danger {
        background: linear-gradient(135deg, rgba(231, 76, 60, 0.15) 0%, rgba(231, 76, 60, 0.1) 100%);
        color: #c0392b;
        border: 1px solid rgba(231, 76, 60, 0.3);
    }

    .badge-info {
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.15) 0%, rgba(136, 219, 242, 0.1) 100%);
        color: var(--stormy-blue);
        border: 1px solid rgba(136, 219, 242, 0.3);
    }

    .badge-warning {
        background: linear-gradient(135deg, rgba(243, 156, 18, 0.15) 0%, rgba(243, 156, 18, 0.1) 100%);
        color: #d68910;
        border: 1px solid rgba(243, 156, 18, 0.3);
    }

    .stats-cell {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8125rem;
    }

    .stat-icon {
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        background: rgba(136, 219, 242, 0.1);
    }

    .stat-icon svg {
        width: 10px;
        height: 10px;
        stroke: var(--stormy-cyan);
    }

    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        color: var(--white);
        box-shadow: 0 4px 12px rgba(106, 137, 167, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(106, 137, 167, 0.4);
    }

    .btn-sm {
        padding: 0.5rem 0.875rem;
        font-size: 0.8125rem;
    }

    .btn-edit {
        background: rgba(52, 152, 219, 0.1);
        color: #2980b9;
        border: 1px solid rgba(52, 152, 219, 0.3);
    }

    .btn-edit:hover {
        background: rgba(52, 152, 219, 0.2);
        border-color: rgba(52, 152, 219, 0.5);
    }

    .btn-delete {
        background: rgba(231, 76, 60, 0.1);
        color: #c0392b;
        border: 1px solid rgba(231, 76, 60, 0.3);
    }

    .btn-delete:hover {
        background: rgba(231, 76, 60, 0.2);
        border-color: rgba(231, 76, 60, 0.5);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1.5rem;
        opacity: 0.3;
    }

    .empty-state-icon svg {
        width: 100%;
        height: 100%;
        stroke: var(--stormy-blue);
    }

    .empty-state h3 {
        color: var(--stormy-dark);
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--stormy-blue);
        font-size: 0.9375rem;
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div class="page-header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
                <polyline points="10 9 9 9 8 9"/>
            </svg>
        </div>
        <div class="page-header-text">
            <h1>Catálogo de Prestaciones</h1>
            <p>Gestione prestaciones médicas y servicios del sistema</p>
        </div>
    </div>
</div>

<div class="filters-card">
    <div class="filters-header">
        <h2>Filtrar Prestaciones</h2>
    </div>
    <div class="filters-content">
        <form method="GET" action="<?= baseUrl('services') ?>">
            <div class="row" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                <div class="form-group" style="margin: 0;">
                    <label class="form-label" style="margin-bottom: 0.5rem; font-weight: 600; color: var(--stormy-dark); font-size: 0.875rem;">Buscar</label>
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Nombre, código o descripción..."
                        value="<?= htmlspecialchars($filters['search']) ?>"
                        style="padding: 0.75rem 1rem; border: 2px solid rgba(136, 219, 242, 0.25); border-radius: 10px; font-size: 0.9375rem;"
                    >
                </div>

                <div class="form-group" style="margin: 0;">
                    <label class="form-label" style="margin-bottom: 0.5rem; font-weight: 600; color: var(--stormy-dark); font-size: 0.875rem;">Estado</label>
                    <select name="estado" class="form-control form-select" style="padding: 0.75rem 1rem; border: 2px solid rgba(136, 219, 242, 0.25); border-radius: 10px; font-size: 0.9375rem;">
                        <option value="">Todos</option>
                        <option value="activo" <?= $filters['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="inactivo" <?= $filters['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <div></div>

                <div style="display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                        Filtrar
                    </button>
                    <a href="<?= baseUrl('services/create') ?>" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Nueva Prestación
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="table-card">
    <?php if (empty($services)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
            <h3>No hay prestaciones registradas</h3>
            <p>Comience agregando su primera prestación médica al sistema</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Prestación</th>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th style="min-width: 200px;">Uso del Servicio</th>
                        <th>Estado</th>
                        <th style="text-align: center; width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: var(--stormy-dark); margin-bottom: 0.25rem;">
                                    <?= htmlspecialchars($service['nombre']) ?>
                                </div>
                                <?php if (!empty($service['descripcion'])): ?>
                                    <div style="font-size: 0.8125rem; color: var(--stormy-blue); max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?= htmlspecialchars($service['descripcion']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($service['codigo'])): ?>
                                    <span class="badge badge-info" style="font-family: monospace;">
                                        <?= htmlspecialchars($service['codigo']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: rgba(106, 137, 167, 0.4);">Sin código</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($service['tipo_prestacion_nombre'])): ?>
                                    <span class="badge badge-secondary">
                                        <?= htmlspecialchars($service['tipo_prestacion_nombre']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: rgba(106, 137, 167, 0.4);">Sin tipo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="stats-cell">
                                    <div class="stat-item">
                                        <div class="stat-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                                <circle cx="9" cy="7" r="4"/>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                            </svg>
                                        </div>
                                        <span style="font-weight: 600; color: var(--stormy-dark);">
                                            <?= $service['total_pacientes'] ?? 0 ?>
                                        </span>
                                        <span style="color: var(--stormy-blue);">
                                            <?= ($service['total_pacientes'] ?? 0) == 1 ? 'paciente' : 'pacientes' ?>
                                        </span>
                                        <?php if (($service['pacientes_activos'] ?? 0) > 0): ?>
                                            <span class="badge badge-success" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                                <?= $service['pacientes_activos'] ?> activo<?= $service['pacientes_activos'] > 1 ? 's' : '' ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                                <circle cx="8.5" cy="7" r="4"/>
                                                <polyline points="17 11 19 13 23 9"/>
                                            </svg>
                                        </div>
                                        <span style="font-weight: 600; color: var(--stormy-dark);">
                                            <?= $service['profesionales_asignados'] ?? 0 ?>
                                        </span>
                                        <span style="color: var(--stormy-blue);">
                                            <?= ($service['profesionales_asignados'] ?? 0) == 1 ? 'profesional' : 'profesionales' ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($service['estado'] === 'activo'): ?>
                                    <span class="badge badge-success">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                        Activo
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-danger">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                            <line x1="18" y1="6" x2="6" y2="18"/>
                                            <line x1="6" y1="6" x2="18" y2="18"/>
                                        </svg>
                                        Inactivo
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    <a href="<?= baseUrl('services/edit/' . $service['id']) ?>" class="btn btn-sm btn-edit">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Editar
                                    </a>
                                    <?php if (hasRole('administrador')): ?>
                                        <form method="POST" action="<?= baseUrl('services/delete/' . $service['id']) ?>" style="display: inline;" onsubmit="return confirm('¿Está seguro de desactivar esta prestación?')">
                                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                            <button type="submit" class="btn btn-sm btn-delete">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"/>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

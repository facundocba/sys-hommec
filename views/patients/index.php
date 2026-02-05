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

    .badge-warning {
        background: linear-gradient(135deg, rgba(243, 156, 18, 0.15) 0%, rgba(243, 156, 18, 0.1) 100%);
        color: #d68910;
        border: 1px solid rgba(243, 156, 18, 0.3);
    }

    .badge-info {
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.15) 0%, rgba(136, 219, 242, 0.1) 100%);
        color: var(--stormy-blue);
        border: 1px solid rgba(136, 219, 242, 0.3);
    }

    .badge-recurrent {
        background: linear-gradient(135deg, rgba(155, 89, 182, 0.15) 0%, rgba(155, 89, 182, 0.1) 100%);
        color: #8e44ad;
        border: 1px solid rgba(155, 89, 182, 0.3);
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

    .btn-finalize {
        background: rgba(243, 156, 18, 0.1);
        color: #d68910;
        border: 1px solid rgba(243, 156, 18, 0.3);
    }

    .btn-finalize:hover {
        background: rgba(243, 156, 18, 0.2);
        border-color: rgba(243, 156, 18, 0.5);
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

    .patients-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 1.5rem;
        padding: 2rem;
    }

    .patient-card {
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.95) 100%);
        border: 2px solid rgba(136, 219, 242, 0.25);
        border-radius: 18px;
        padding: 0;
        box-shadow:
            0 4px 20px rgba(56, 73, 89, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
    }

    .patient-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .patient-card:hover {
        transform: translateY(-4px);
        box-shadow:
            0 8px 32px rgba(56, 73, 89, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        border-color: var(--stormy-cyan);
    }

    .patient-card:hover::before {
        opacity: 1;
    }

    .patient-card-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.08) 0%, rgba(189, 221, 252, 0.05) 100%);
        border-bottom: 1px solid rgba(136, 219, 242, 0.15);
    }

    .patient-card-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--stormy-dark);
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .patient-card-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .patient-card-body {
        padding: 1.5rem;
    }

    .patient-info-grid {
        display: grid;
        gap: 1rem;
    }

    .patient-info-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .patient-info-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.12) 0%, rgba(189, 221, 252, 0.08) 100%);
        flex-shrink: 0;
    }

    .patient-info-icon svg {
        width: 18px;
        height: 18px;
        stroke: var(--stormy-cyan);
    }

    .patient-info-content {
        flex: 1;
    }

    .patient-info-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--stormy-blue);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .patient-info-value {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--stormy-dark);
    }

    .patient-info-subvalue {
        font-size: 0.8125rem;
        color: var(--stormy-blue);
        font-weight: 500;
        margin-top: 0.125rem;
    }

    .patient-card-footer {
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, rgba(189, 221, 252, 0.05) 0%, rgba(136, 219, 242, 0.03) 100%);
        border-top: 1px solid rgba(136, 219, 242, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
    }

    .patient-dates {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        font-size: 0.75rem;
        color: var(--stormy-blue);
    }

    .patient-dates strong {
        font-weight: 600;
        color: var(--stormy-dark);
    }

    .patient-actions {
        display: flex;
        gap: 0.5rem;
    }

    .form-select {
        position: relative;
        padding: 0.875rem 2.5rem 0.875rem 1rem;
        border: 2px solid rgba(136, 219, 242, 0.3);
        border-radius: 12px;
        font-size: 0.9375rem;
        font-weight: 500;
        color: var(--stormy-dark);
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(56, 73, 89, 0.04);
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2388dbf2' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px;
    }

    .form-select:hover {
        border-color: var(--stormy-cyan);
        box-shadow:
            0 4px 16px rgba(136, 219, 242, 0.15),
            0 2px 8px rgba(56, 73, 89, 0.06);
        transform: translateY(-1px);
    }

    .form-select:focus {
        outline: none;
        border-color: var(--stormy-cyan);
        box-shadow:
            0 0 0 4px rgba(136, 219, 242, 0.12),
            0 4px 16px rgba(136, 219, 242, 0.2);
        transform: translateY(-1px);
    }

    .form-select option {
        padding: 0.75rem;
        background: white;
        color: var(--stormy-dark);
        font-weight: 500;
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

    @media (max-width: 768px) {
        .patients-grid {
            grid-template-columns: 1fr;
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
        <div class="page-header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div class="page-header-text">
            <h1>Gestión de Pacientes</h1>
            <p>Administre pacientes, asignaciones y prestaciones médicas</p>
        </div>
    </div>
</div>

<div class="filters-card">
    <div class="filters-header">
        <h2>Filtrar Pacientes</h2>
    </div>
    <div class="filters-content">
        <form method="GET" action="<?= baseUrl('patients') ?>">
            <div class="row" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group" style="margin: 0;">
                    <label class="form-label" style="margin-bottom: 0.5rem; font-weight: 600; color: var(--stormy-dark); font-size: 0.875rem;">Buscar Paciente</label>
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Nombre o DNI..."
                        value="<?= htmlspecialchars($filters['search']) ?>"
                        style="padding: 0.75rem 1rem; border: 2px solid rgba(136, 219, 242, 0.25); border-radius: 10px; font-size: 0.9375rem;"
                    >
                </div>

                <div class="form-group" style="margin: 0;">
                    <label class="form-label" style="margin-bottom: 0.5rem; font-weight: 600; color: var(--stormy-dark); font-size: 0.875rem;">Profesional</label>
                    <select name="profesional" class="form-control form-select">
                        <option value="">Todos</option>
                        <?php foreach ($professionals as $prof): ?>
                            <option value="<?= $prof['id'] ?>" <?= $filters['profesional'] == $prof['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($prof['nombre']) ?> - <?= htmlspecialchars($prof['especialidad']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin: 0;">
                    <label class="form-label" style="margin-bottom: 0.5rem; font-weight: 600; color: var(--stormy-dark); font-size: 0.875rem;">Empresa</label>
                    <select name="empresa" class="form-control form-select">
                        <option value="">Todas</option>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?= $company['id'] ?>" <?= $filters['empresa'] == $company['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($company['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin: 0;">
                    <label class="form-label" style="margin-bottom: 0.5rem; font-weight: 600; color: var(--stormy-dark); font-size: 0.875rem;">Prestación</label>
                    <select name="prestacion" class="form-control form-select">
                        <option value="">Todas</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= $service['id'] ?>" <?= $filters['prestacion'] == $service['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($service['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin: 0;">
                    <label class="form-label" style="margin-bottom: 0.5rem; font-weight: 600; color: var(--stormy-dark); font-size: 0.875rem;">Provincia</label>
                    <select name="provincia" class="form-control form-select">
                        <option value="">Todas</option>
                        <?php foreach ($provinces as $prov): ?>
                            <option value="<?= $prov['id'] ?>" <?= $filters['provincia'] == $prov['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($prov['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin: 0;">
                    <label class="form-label" style="margin-bottom: 0.5rem; font-weight: 600; color: var(--stormy-dark); font-size: 0.875rem;">Estado</label>
                    <select name="estado" class="form-control form-select">
                        <option value="">Todos</option>
                        <option value="activo" <?= $filters['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="finalizado" <?= $filters['estado'] === 'finalizado' ? 'selected' : '' ?>>Finalizado</option>
                        <option value="suspendido" <?= $filters['estado'] === 'suspendido' ? 'selected' : '' ?>>Suspendido</option>
                    </select>
                </div>

                <div class="form-group" style="margin: 0;">
                    <label class="form-label" style="margin-bottom: 0.5rem; font-weight: 600; color: var(--stormy-dark); font-size: 0.875rem;">Tipo</label>
                    <select name="recurrente" class="form-control form-select">
                        <option value="">Todos</option>
                        <option value="1" <?= $filters['recurrente'] === '1' ? 'selected' : '' ?>>Recurrente</option>
                        <option value="0" <?= $filters['recurrente'] === '0' ? 'selected' : '' ?>>No Recurrente</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    Filtrar
                </button>
                <a href="<?= baseUrl('patients/create') ?>" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Nuevo Paciente
                </a>
            </div>
        </form>
    </div>
</div>

<div class="table-card">
    <?php if (empty($patients)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <h3>No hay pacientes registrados</h3>
            <p>Comience agregando su primer paciente al sistema</p>
        </div>
    <?php else: ?>
        <div class="patients-grid">
            <?php foreach ($patients as $patient): ?>
                <div class="patient-card">
                    <!-- Header: Nombre y Estado -->
                    <div class="patient-card-header">
                        <h3 class="patient-card-name">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="8" r="5"/>
                                <path d="M20 21a8 8 0 1 0-16 0"/>
                            </svg>
                            <?= htmlspecialchars($patient['nombre_completo']) ?>
                        </h3>
                        <div class="patient-card-meta">
                            <?php if ($patient['estado'] === 'activo'): ?>
                                <span class="badge badge-success">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    Activo
                                </span>
                            <?php elseif ($patient['estado'] === 'finalizado'): ?>
                                <span class="badge badge-danger">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                    Finalizado
                                </span>
                            <?php else: ?>
                                <span class="badge badge-warning">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    Suspendido
                                </span>
                            <?php endif; ?>

                            <?php if ($patient['paciente_recurrente']): ?>
                                <span class="badge badge-recurrent">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                        <path d="M3 3v5h5"/>
                                        <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                                        <path d="M16 21h5v-5"/>
                                    </svg>
                                    Recurrente
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($patient['obra_social_nombre'])): ?>
                                <span class="badge badge-info">
                                    <?= !empty($patient['obra_social_sigla']) ? htmlspecialchars($patient['obra_social_sigla']) : htmlspecialchars($patient['obra_social_nombre']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Body: Información principal -->
                    <div class="patient-card-body">
                        <div class="patient-info-grid">
                            <!-- Prestaciones Activas -->
                            <?php if (!empty($patient['prestaciones_activas']) && $patient['prestaciones_activas'] > 0): ?>
                                <div class="patient-info-item">
                                    <div class="patient-info-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                            <line x1="16" y1="13" x2="8" y2="13"/>
                                            <line x1="16" y1="17" x2="8" y2="17"/>
                                        </svg>
                                    </div>
                                    <div class="patient-info-content">
                                        <div class="patient-info-label">Prestaciones Activas</div>
                                        <div class="patient-info-value">
                                            <?= $patient['prestaciones_activas'] ?>
                                            <?= $patient['prestaciones_activas'] == 1 ? 'prestación' : 'prestaciones' ?>
                                        </div>
                                        <?php if (!empty($patient['prestaciones_list'])): ?>
                                            <div class="patient-info-subvalue">
                                                <?= htmlspecialchars(substr($patient['prestaciones_list'], 0, 50)) ?>
                                                <?= strlen($patient['prestaciones_list']) > 50 ? '...' : '' ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Profesionales -->
                                <div class="patient-info-item">
                                    <div class="patient-info-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                            <circle cx="8.5" cy="7" r="4"/>
                                            <polyline points="17 11 19 13 23 9"/>
                                        </svg>
                                    </div>
                                    <div class="patient-info-content">
                                        <div class="patient-info-label">Profesionales</div>
                                        <?php if (!empty($patient['profesionales_list'])): ?>
                                            <div class="patient-info-value">
                                                <?= $patient['profesionales_count'] ?>
                                                <?= $patient['profesionales_count'] == 1 ? 'profesional' : 'profesionales' ?>
                                            </div>
                                            <div class="patient-info-subvalue">
                                                <?= htmlspecialchars(substr($patient['profesionales_list'], 0, 50)) ?>
                                                <?= strlen($patient['profesionales_list']) > 50 ? '...' : '' ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="patient-info-value" style="color: rgba(106, 137, 167, 0.5);">Sin asignar</div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Empresas -->
                                <div class="patient-info-item">
                                    <div class="patient-info-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                        </svg>
                                    </div>
                                    <div class="patient-info-content">
                                        <div class="patient-info-label">Empresas</div>
                                        <?php if (!empty($patient['empresas_list'])): ?>
                                            <div class="patient-info-value">
                                                <?= $patient['empresas_count'] ?>
                                                <?= $patient['empresas_count'] == 1 ? 'empresa' : 'empresas' ?>
                                            </div>
                                            <div class="patient-info-subvalue">
                                                <?= htmlspecialchars(substr($patient['empresas_list'], 0, 50)) ?>
                                                <?= strlen($patient['empresas_list']) > 50 ? '...' : '' ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="patient-info-value" style="color: rgba(106, 137, 167, 0.5);">Sin asignar</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="patient-info-item" style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                                    <div style="color: rgba(106, 137, 167, 0.6);">
                                        <svg style="width: 48px; height: 48px; margin: 0 auto 1rem; stroke: currentColor;" viewBox="0 0 24 24" fill="none" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <line x1="12" y1="16" x2="12" y2="12"/>
                                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                                        </svg>
                                        <div style="font-weight: 600;">Sin prestaciones activas</div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Footer: Fechas y Acciones -->
                    <div class="patient-card-footer">
                        <div class="patient-dates">
                            <div><strong>Ingreso:</strong> <?= formatDate($patient['fecha_ingreso'], 'd/m/Y') ?></div>
                            <?php if (!$patient['paciente_recurrente'] && $patient['fecha_finalizacion']): ?>
                                <div><strong>Finalización:</strong> <?= formatDate($patient['fecha_finalizacion'], 'd/m/Y') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="patient-actions">
                            <a href="<?= baseUrl('patients/view/' . $patient['id']) ?>" class="btn btn-sm btn-edit">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Ver Detalle
                            </a>
                            <?php if (hasRole('administrador') && $patient['estado'] === 'activo'): ?>
                                <button type="button" class="btn btn-sm btn-finalize" onclick="showFinalizeModal(<?= $patient['id'] ?>, '<?= htmlspecialchars($patient['nombre_completo'], ENT_QUOTES) ?>')" style="background: rgba(243, 156, 18, 0.1); color: #d68910; border: 1px solid rgba(243, 156, 18, 0.3);" title="Finalizar">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 11l3 3L22 4"/>
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                                    </svg>
                                </button>
                            <?php endif; ?>
                            <?php if (hasRole('administrador')): ?>
                                <button type="button" class="btn btn-sm btn-delete" onclick="showDeleteModal(<?= $patient['id'] ?>, '<?= htmlspecialchars($patient['nombre_completo'], ENT_QUOTES) ?>')" title="Eliminar permanentemente">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
</div>

<!-- Modal de Confirmación de Finalización -->
<div id="finalizeModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; padding: 0; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); animation: modalSlideIn 0.3s ease-out;">
        <div style="background: linear-gradient(135deg, #f39c12 0%, #d68910 100%); padding: 2rem; border-radius: 20px 20px 0 0; text-align: center;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 11l3 3L22 4"/>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
            </svg>
            <h3 style="margin: 0; color: white; font-size: 1.5rem; font-weight: 700;">Finalizar Paciente</h3>
        </div>
        <div style="padding: 2rem;">
            <p style="color: #384959; font-size: 1rem; line-height: 1.6; margin: 0 0 1.5rem 0; text-align: center;">
                ¿Está seguro que desea <strong>finalizar</strong> al paciente:
            </p>
            <div style="background: linear-gradient(135deg, rgba(243, 156, 18, 0.1) 0%, rgba(214, 137, 16, 0.05) 100%); border: 2px solid rgba(243, 156, 18, 0.3); border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem;">
                <p id="finalizePatientName" style="margin: 0; font-weight: 700; font-size: 1.125rem; color: #d68910; text-align: center;"></p>
            </div>
            <div style="background: #e8f4fd; border-left: 4px solid #3498db; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                <p style="margin: 0; color: #2c3e50; font-size: 0.875rem; line-height: 1.5;">
                    <strong>Información:</strong> El paciente pasará a estado "Finalizado". Los datos permanecerán en el sistema y podrá consultarlos cuando lo necesite.
                </p>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeFinalizeModal()" style="background: #ecf0f1; color: #34495e; border: 2px solid #bdc3c7;">
                    Cancelar
                </button>
                <button type="button" class="btn btn-warning" onclick="confirmFinalizePatient()" style="background: linear-gradient(135deg, #f39c12 0%, #d68910 100%); color: white; border: none; box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);">
                    Sí, Finalizar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación Permanente -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 10000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; padding: 0; max-width: 500px; width: 90%; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); animation: modalSlideIn 0.3s ease-out;">
        <!-- Header con gradiente de peligro -->
        <div style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); padding: 2rem; border-radius: 20px 20px 0 0; text-align: center;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <h3 style="margin: 1rem 0 0 0; color: white; font-size: 1.5rem; font-weight: 700;">Eliminar Permanentemente</h3>
        </div>

        <!-- Contenido -->
        <div style="padding: 2rem;">
            <p style="color: #384959; font-size: 1rem; line-height: 1.6; margin: 0 0 1.5rem 0; text-align: center;">
                ¿Está seguro que desea <strong style="color: #c0392b;">ELIMINAR PERMANENTEMENTE</strong> al paciente:
            </p>

            <div style="background: linear-gradient(135deg, rgba(231, 76, 60, 0.15) 0%, rgba(192, 57, 43, 0.1) 100%); border: 2px solid rgba(231, 76, 60, 0.3); border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem;">
                <p id="deletePatientName" style="margin: 0; font-weight: 700; font-size: 1.125rem; color: #c0392b; text-align: center;"></p>
            </div>

            <div style="background: #ffebee; border-left: 4px solid #c0392b; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                <p style="margin: 0 0 0.5rem 0; color: #c0392b; font-size: 0.875rem; line-height: 1.5;">
                    <strong>⚠️ ADVERTENCIA - ACCIÓN IRREVERSIBLE:</strong>
                </p>
                <ul style="margin: 0.5rem 0 0 1.25rem; padding: 0; color: #7f1d1d; font-size: 0.875rem; line-height: 1.6;">
                    <li>Se eliminarán TODOS los datos del paciente</li>
                    <li>Se eliminarán archivos, prestaciones e historial</li>
                    <li><strong>Esta acción NO se puede deshacer</strong></li>
                </ul>
            </div>

            <!-- Botones -->
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()" style="background: #ecf0f1; color: #34495e; border: 2px solid #bdc3c7;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                    Cancelar
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDeletePatient()" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                    Sí, Eliminar Permanentemente
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Form oculto para enviar la eliminación -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
</form>

<style>
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    #deleteModal .btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #deleteModal .btn:hover {
        transform: translateY(-2px);
    }

    #deleteModal .btn-danger:hover {
        box-shadow: 0 6px 20px rgba(231, 76, 60, 0.5);
    }

    #deleteModal .btn-secondary:hover {
        background: #d5dbdb;
        border-color: #95a5a6;
    }
</style>

<script>
    let patientIdToFinalize = null;
    let patientIdToDelete = null;

    // Modal de Finalizar
    function showFinalizeModal(patientId, patientName) {
        patientIdToFinalize = patientId;
        document.getElementById('finalizePatientName').textContent = patientName;
        document.getElementById('finalizeModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeFinalizeModal() {
        document.getElementById('finalizeModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        patientIdToFinalize = null;
    }

    function confirmFinalizePatient() {
        if (patientIdToFinalize) {
            const form = document.getElementById('deleteForm');
            form.action = '<?= baseUrl('patients/finalize/') ?>' + patientIdToFinalize;
            form.submit();
        }
    }

    // Modal de Eliminar
    function showDeleteModal(patientId, patientName) {
        patientIdToDelete = patientId;
        document.getElementById('deletePatientName').textContent = patientName;
        document.getElementById('deleteModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        patientIdToDelete = null;
    }

    function confirmDeletePatient() {
        if (patientIdToDelete) {
            const form = document.getElementById('deleteForm');
            form.action = '<?= baseUrl('patients/delete/') ?>' + patientIdToDelete;
            form.submit();
        }
    }

    // Cerrar modales al hacer clic fuera
    document.getElementById('finalizeModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeFinalizeModal();
        }
    });

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Cerrar modales con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('finalizeModal').style.display === 'flex') {
                closeFinalizeModal();
            }
            if (document.getElementById('deleteModal').style.display === 'flex') {
                closeDeleteModal();
            }
        }
    });
</script>

<?php if (!empty($patients) && $pagination['total_pages'] > 1): ?>
<!-- Paginación -->
<div class="pagination-wrapper">
    <div class="pagination-info">
        Mostrando <?= $pagination['offset'] + 1 ?> - <?= min($pagination['offset'] + $pagination['items_per_page'], $pagination['total_items']) ?> de <?= $pagination['total_items'] ?> pacientes
    </div>

    <ul class="pagination">
        <!-- Botón Anterior -->
        <li>
            <?php if ($pagination['current_page'] > 1): ?>
                <a href="<?= baseUrl('patients?' . http_build_query(array_merge($filters, ['page' => $pagination['current_page'] - 1]))) ?>"
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
                <a href="<?= baseUrl('patients?' . http_build_query(array_merge($filters, ['page' => 1]))) ?>"
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
                    <a href="<?= baseUrl('patients?' . http_build_query(array_merge($filters, ['page' => $i]))) ?>"
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
                <a href="<?= baseUrl('patients?' . http_build_query(array_merge($filters, ['page' => $pagination['total_pages']]))) ?>"
                   class="pagination-btn"><?= $pagination['total_pages'] ?></a>
            </li>
        <?php endif; ?>

        <!-- Botón Siguiente -->
        <li>
            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                <a href="<?= baseUrl('patients?' . http_build_query(array_merge($filters, ['page' => $pagination['current_page'] + 1]))) ?>"
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

<?php include __DIR__ . '/../layouts/footer.php'; ?>

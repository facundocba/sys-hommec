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
        grid-template-columns: 2fr 2fr auto;
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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(255, 255, 255, 0.95) 100%
        );
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 24px rgba(56, 73, 89, 0.06);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--stormy-cyan), var(--stormy-blue));
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--stormy-blue);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-dark), var(--stormy-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .professionals-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .professional-card {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%
        );
        border: 2px solid rgba(136, 219, 242, 0.25);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(56, 73, 89, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .professional-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg,
            var(--stormy-cyan) 0%,
            var(--stormy-blue) 50%,
            var(--stormy-cyan) 100%
        );
        background-size: 200% 100%;
        animation: shimmer 3s linear infinite;
    }

    .professional-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(136, 219, 242, 0.25);
        border-color: var(--stormy-cyan);
    }

    .prof-card-header {
        padding: 2rem 2rem 1.5rem 2rem;
        border-bottom: 2px solid rgba(136, 219, 242, 0.15);
        background: linear-gradient(135deg,
            rgba(136, 219, 242, 0.05) 0%,
            transparent 100%
        );
    }

    .prof-name {
        font-size: 1.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-dark), var(--stormy-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.02em;
    }

    .prof-specialty {
        font-size: 0.9375rem;
        color: var(--stormy-blue);
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .prof-specialty::before {
        content: '';
        width: 8px;
        height: 8px;
        background: var(--stormy-cyan);
        border-radius: 50%;
        box-shadow: 0 0 8px rgba(136, 219, 242, 0.6);
    }

    .prof-meta {
        display: flex;
        gap: 1.5rem;
        margin-top: 1rem;
    }

    .prof-meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .prof-meta-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--stormy-blue);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .prof-meta-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--stormy-dark);
    }

    .prof-card-body {
        padding: 1.5rem 2rem 2rem 2rem;
    }

    .periods-compact {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .period-row {
        background: linear-gradient(135deg,
            rgba(136, 219, 242, 0.05) 0%,
            rgba(189, 221, 252, 0.03) 100%
        );
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 16px;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }

    .period-row:hover {
        background: linear-gradient(135deg,
            rgba(136, 219, 242, 0.1) 0%,
            rgba(189, 221, 252, 0.05) 100%
        );
        border-color: var(--stormy-cyan);
        transform: translateX(4px);
    }

    .period-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(136, 219, 242, 0.2);
    }

    .period-label {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--stormy-dark);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .period-label svg {
        width: 16px;
        height: 16px;
        stroke: var(--stormy-cyan);
    }

    .period-total {
        font-size: 1.25rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--stormy-dark), var(--stormy-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .period-breakdown {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .breakdown-item {
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
    }

    .breakdown-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .breakdown-label.prof {
        color: #10b981;
    }

    .breakdown-label.emp {
        color: #6366f1;
    }

    .breakdown-label::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .breakdown-label.prof::before {
        background: #10b981;
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.4);
    }

    .breakdown-label.emp::before {
        background: #6366f1;
        box-shadow: 0 0 8px rgba(99, 102, 241, 0.4);
    }

    .breakdown-value {
        font-size: 1.125rem;
        font-weight: 700;
    }

    .breakdown-value.prof {
        color: #10b981;
    }

    .breakdown-value.emp {
        color: #6366f1;
    }


    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--stormy-blue);
    }

    @media (max-width: 768px) {
        .page-header-content {
            flex-direction: column;
            gap: 1rem;
        }

        .filters-row {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .professional-card {
            margin-bottom: 1rem;
        }

        .prof-card-header {
            padding: 1.5rem;
        }

        .prof-card-body {
            padding: 1.5rem;
        }
    }
</style>

<div class="page-header">
    <div class="page-header-content">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue)); border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 24px rgba(106, 137, 167, 0.35);">
                <svg style="width: 28px; height: 28px; stroke: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="20" x2="12" y2="10"/>
                    <line x1="18" y1="20" x2="18" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="16"/>
                </svg>
            </div>
            <div>
                <h1>Reportes Financieros de Profesionales</h1>
                <p style="margin: 0.5rem 0 0 0; color: var(--stormy-blue);">Acumulados por período según frecuencia y sesiones</p>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="filters-card">
    <form method="GET" action="<?= baseUrl('professionals/reports') ?>">
        <div class="filters-row">
            <div class="form-group">
                <label>Profesional</label>
                <select name="professional" class="form-control form-select">
                    <option value="">Todos los profesionales</option>
                    <?php foreach ($professionals as $prof): ?>
                        <option value="<?= $prof['id'] ?>" <?= ($professionalId == $prof['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($prof['nombre']) ?> - <?= htmlspecialchars($prof['especialidad'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Período Base</label>
                <select name="period" class="form-control form-select">
                    <option value="30" <?= $period == 30 ? 'selected' : '' ?>>Últimos 30 días</option>
                    <option value="60" <?= $period == 60 ? 'selected' : '' ?>>Últimos 60 días</option>
                    <option value="90" <?= $period == 90 ? 'selected' : '' ?>>Últimos 90 días</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-filter">Generar Reporte</button>
            </div>
        </div>
    </form>
</div>

<!-- Estadísticas Generales -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Profesionales</div>
        <div class="stat-value"><?= $reportData['totals']['total_profesionales'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Pacientes</div>
        <div class="stat-value"><?= $reportData['totals']['total_pacientes'] ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Sesiones/Mes</div>
        <div class="stat-value"><?= number_format($reportData['totals']['total_sesiones']) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ingreso Profesional (30d)</div>
        <div class="stat-value" style="color: #10b981;">
            $<?= number_format($reportData['totals']['total_valor_profesional'], 2) ?>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Ingreso Empresa (30d)</div>
        <div class="stat-value" style="color: #6366f1;">
            $<?= number_format($reportData['totals']['total_valor_empresa'], 2) ?>
        </div>
    </div>
</div>

<!-- Cuadrícula de Tarjetas de Profesionales -->
<?php if (empty($reportData['professionals'])): ?>
    <div class="empty-state">
        <h3>No se encontraron datos</h3>
        <p>Intente ajustar los filtros de búsqueda</p>
    </div>
<?php else: ?>
    <div class="professionals-grid">
    <?php foreach ($reportData['professionals'] as $index => $prof): ?>
        <div class="professional-card">
            <!-- Header de la tarjeta -->
            <div class="prof-card-header">
                <h3 class="prof-name"><?= htmlspecialchars($prof['profesional_nombre']) ?></h3>
                <div class="prof-specialty"><?= htmlspecialchars($prof['especialidad'] ?? 'Sin especialidad') ?></div>
                <div class="prof-meta">
                    <div class="prof-meta-item">
                        <span class="prof-meta-label">Pacientes</span>
                        <span class="prof-meta-value"><?= $prof['total_pacientes'] ?></span>
                    </div>
                    <div class="prof-meta-item">
                        <span class="prof-meta-label">Sesiones/Mes</span>
                        <span class="prof-meta-value"><?= $prof['total_sesiones'] ?></span>
                    </div>
                </div>
            </div>

            <!-- Body con períodos compactos -->
            <div class="prof-card-body">
                <div class="periods-compact">
                    <!-- 30 Días -->
                    <div class="period-row">
                        <div class="period-header">
                            <span class="period-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                30 Días
                            </span>
                            <span class="period-total">$<?= number_format($prof['acumulado_profesional_30'] + $prof['acumulado_empresa_30'], 2) ?></span>
                        </div>
                        <div class="period-breakdown">
                            <div class="breakdown-item">
                                <span class="breakdown-label prof">Profesional</span>
                                <span class="breakdown-value prof">$<?= number_format($prof['acumulado_profesional_30'], 2) ?></span>
                            </div>
                            <div class="breakdown-item">
                                <span class="breakdown-label emp">Empresa</span>
                                <span class="breakdown-value emp">$<?= number_format($prof['acumulado_empresa_30'], 2) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- 60 Días -->
                    <div class="period-row">
                        <div class="period-header">
                            <span class="period-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                60 Días
                            </span>
                            <span class="period-total">$<?= number_format($prof['acumulado_profesional_60'] + $prof['acumulado_empresa_60'], 2) ?></span>
                        </div>
                        <div class="period-breakdown">
                            <div class="breakdown-item">
                                <span class="breakdown-label prof">Profesional</span>
                                <span class="breakdown-value prof">$<?= number_format($prof['acumulado_profesional_60'], 2) ?></span>
                            </div>
                            <div class="breakdown-item">
                                <span class="breakdown-label emp">Empresa</span>
                                <span class="breakdown-value emp">$<?= number_format($prof['acumulado_empresa_60'], 2) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- 90 Días -->
                    <div class="period-row">
                        <div class="period-header">
                            <span class="period-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                90 Días
                            </span>
                            <span class="period-total">$<?= number_format($prof['acumulado_profesional_90'] + $prof['acumulado_empresa_90'], 2) ?></span>
                        </div>
                        <div class="period-breakdown">
                            <div class="breakdown-item">
                                <span class="breakdown-label prof">Profesional</span>
                                <span class="breakdown-value prof">$<?= number_format($prof['acumulado_profesional_90'], 2) ?></span>
                            </div>
                            <div class="breakdown-item">
                                <span class="breakdown-label emp">Empresa</span>
                                <span class="breakdown-value emp">$<?= number_format($prof['acumulado_empresa_90'], 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>


<?php include __DIR__ . '/../layouts/footer.php'; ?>

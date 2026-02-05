<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
    .patient-hero {
        background: linear-gradient(135deg,
            rgba(136, 219, 242, 0.15) 0%,
            rgba(106, 137, 167, 0.1) 100%);
        border: 2px solid rgba(136, 219, 242, 0.3);
        border-radius: 24px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .patient-hero::before {
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
    }

    .patient-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .stat-box {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 1.75rem 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .stat-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, var(--stormy-cyan), var(--stormy-blue));
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-box:hover {
        transform: translateY(-4px);
        border-color: rgba(136, 219, 242, 0.4);
        box-shadow: 0 12px 35px rgba(136, 219, 242, 0.2);
    }

    .stat-box:hover::before {
        opacity: 1;
    }

    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #94a3b8;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .stat-label i {
        font-size: 0.9rem;
        color: var(--stormy-cyan);
        opacity: 0.8;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        letter-spacing: -0.03em;
        line-height: 1.3;
    }

    .services-grid {
        display: grid;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .service-card {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(255, 255, 255, 0.95) 100%);
        border: 2px solid rgba(136, 219, 242, 0.25);
        border-radius: 20px;
        padding: 0;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(136, 219, 242, 0.1);
    }

    .service-card:hover {
        transform: translateY(-4px);
        border-color: var(--stormy-cyan);
        box-shadow: 0 12px 40px rgba(136, 219, 242, 0.25);
    }

    .service-header {
        background: linear-gradient(135deg,
            var(--stormy-cyan) 0%,
            var(--stormy-blue) 100%);
        color: white;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .service-title {
        font-size: 1.35rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.02em;
    }

    .service-badges {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .service-badge {
        background: rgba(255, 255, 255, 0.25);
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .service-body {
        padding: 1.75rem;
    }

    .service-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-item {
        display: flex;
        align-items: start;
        gap: 1rem;
        padding: 1rem;
        background: rgba(136, 219, 242, 0.05);
        border-radius: 12px;
        transition: all 0.2s;
    }

    .info-item:hover {
        background: rgba(136, 219, 242, 0.1);
    }

    .info-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
        font-size: 1.1rem;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--stormy-blue);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--stormy-dark);
    }

    .info-sub {
        font-size: 0.8rem;
        color: #666;
        margin-top: 0.15rem;
    }

    .observations-box {
        background: linear-gradient(135deg,
            rgba(136, 219, 242, 0.08) 0%,
            rgba(106, 137, 167, 0.05) 100%);
        border: 1px solid rgba(136, 219, 242, 0.2);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .observations-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--stormy-blue);
        font-weight: 600;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .observations-text {
        color: var(--stormy-dark);
        line-height: 1.6;
    }

    .service-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.75rem;
        background: rgba(136, 219, 242, 0.05);
        border-top: 1px solid rgba(136, 219, 242, 0.15);
    }

    .price-tags {
        display: flex;
        gap: 0.75rem;
    }

    .price-tag {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: white;
        border: 1px solid rgba(136, 219, 242, 0.3);
        border-radius: 10px;
        font-weight: 600;
    }

    .price-tag i {
        color: var(--stormy-cyan);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(136, 219, 242, 0.2);
    }

    .section-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.4rem;
        box-shadow: 0 4px 16px rgba(136, 219, 242, 0.3);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--stormy-dark);
        margin: 0;
        letter-spacing: -0.02em;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(135deg,
            rgba(136, 219, 242, 0.05) 0%,
            rgba(106, 137, 167, 0.03) 100%);
        border: 2px dashed rgba(136, 219, 242, 0.3);
        border-radius: 20px;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        margin: 0 auto 1.5rem;
        opacity: 0.7;
    }

    .empty-state-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--stormy-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: #666;
        margin-bottom: 1.5rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem 0.75rem;
        border-radius: 10px;
        border: 1px solid rgba(136, 219, 242, 0.3);
        background: white;
        color: var(--stormy-blue);
        transition: all 0.2s;
        font-size: 0.9rem;
    }

    .btn-action:hover {
        background: var(--stormy-cyan);
        color: white;
        border-color: var(--stormy-cyan);
        transform: translateY(-1px);
    }

    .btn-action.delete {
        border-color: rgba(231, 76, 60, 0.3);
        color: #e74c3c;
    }

    .btn-action.delete:hover {
        background: #e74c3c;
        border-color: #e74c3c;
        color: white;
    }

    /* Confirm Modal */
    .confirm-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(56, 73, 89, 0.75);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .confirm-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .confirm-modal {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%
        );
        border-radius: 24px;
        padding: 0;
        max-width: 480px;
        width: 90%;
        box-shadow:
            0 20px 80px rgba(56, 73, 89, 0.3),
            0 8px 32px rgba(136, 219, 242, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        border: 1px solid rgba(136, 219, 242, 0.3);
        transform: scale(0.9) translateY(20px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .confirm-modal-overlay.active .confirm-modal {
        transform: scale(1) translateY(0);
    }

    .confirm-modal::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg,
            transparent 0%,
            #f59e0b 20%,
            #fbbf24 50%,
            #f59e0b 80%,
            transparent 100%
        );
    }

    .confirm-modal-header {
        padding: 2rem 2.5rem 1.5rem;
        border-bottom: 1px solid rgba(136, 219, 242, 0.15);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .confirm-modal-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: linear-gradient(135deg,
            rgba(245, 158, 11, 0.15) 0%,
            rgba(245, 158, 11, 0.08) 100%
        );
        border: 2px solid rgba(245, 158, 11, 0.3);
    }

    .confirm-modal-icon svg {
        width: 28px;
        height: 28px;
        stroke: #f59e0b;
    }

    .confirm-modal-title {
        font-size: 1.5rem;
        font-weight: 800;
        background: linear-gradient(135deg,
            var(--stormy-dark) 0%,
            var(--stormy-blue) 100%
        );
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
        letter-spacing: -0.02em;
    }

    .confirm-modal-body {
        padding: 2rem 2.5rem;
    }

    .confirm-modal-message {
        color: var(--stormy-blue);
        font-size: 1rem;
        font-weight: 500;
        line-height: 1.6;
        margin: 0;
    }

    .confirm-modal-footer {
        padding: 1.5rem 2.5rem 2rem;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .confirm-modal-btn {
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        position: relative;
        overflow: hidden;
    }

    .confirm-modal-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.5s ease, height 0.5s ease;
    }

    .confirm-modal-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .confirm-modal-btn-cancel {
        background: rgba(106, 137, 167, 0.1);
        color: var(--stormy-blue);
        border: 2px solid rgba(106, 137, 167, 0.3);
    }

    .confirm-modal-btn-cancel:hover {
        background: rgba(106, 137, 167, 0.2);
        border-color: var(--stormy-blue);
        transform: translateY(-2px);
    }

    .confirm-modal-btn-confirm {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        color: var(--white);
        box-shadow:
            0 6px 20px rgba(245, 158, 11, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .confirm-modal-btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow:
            0 10px 28px rgba(245, 158, 11, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.15);
    }

    .confirm-modal-btn svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        position: relative;
        z-index: 1;
    }

    .confirm-modal-btn span {
        position: relative;
        z-index: 1;
    }
</style>

<!-- Page Header -->
<div class="top-bar" style="background: linear-gradient(135deg, var(--stormy-cyan) 0%, var(--stormy-blue) 100%); border: none; border-radius: 20px; padding: 2rem 2.5rem; margin-bottom: 2rem; box-shadow: 0 8px 32px rgba(136, 219, 242, 0.3); position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; right: 0; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%); pointer-events: none;"></div>
    <div style="position: absolute; bottom: -50px; left: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); pointer-events: none;"></div>

    <div style="display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; position: relative; z-index: 1; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="position: relative;">
                <svg style="width: 40px; height: 40px; position: relative; z-index: 10;" viewBox="0 0 24 24" fill="white" stroke="none">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <div style="position: absolute; bottom: -6px; right: -6px; width: 28px; height: 28px; background: <?php echo $patient['estado'] === 'activo' ? '#10b981' : '#6b7280'; ?>; border: 4px solid white; border-radius: 50%; box-shadow: 0 2px 12px rgba(0,0,0,0.2); z-index: 20;"></div>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.75rem; flex-wrap: wrap;">
                    <span style="font-size: 0.8rem; color: rgba(255, 255, 255, 0.9); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 0.5rem;">
                        <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="white" stroke="none">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        Paciente
                    </span>
                    <?php if (!empty($patient['dni'])): ?>
                        <span style="font-size: 0.8rem; color: rgba(255, 255, 255, 0.95); font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                            <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="M7 15h0M7 11h0M7 7h10"/>
                            </svg>
                            DNI: <?php echo htmlspecialchars($patient['dni']); ?>
                        </span>
                    <?php endif; ?>
                </div>
                <h1 style="margin: 0; font-size: 2.25rem; font-weight: 800; color: white; letter-spacing: -0.03em; line-height: 1.2; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                    <?php echo htmlspecialchars($patient['nombre_completo']); ?>
                </h1>
            </div>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <a href="<?php echo baseUrl('patients/edit/' . $patient['id']); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.75rem; background: white; color: var(--stormy-blue); border: 2px solid white; border-radius: 14px; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: all 0.2s; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.1)'">
                <i class="bi bi-pencil-square"></i>
                Editar Datos
            </a>
            <a href="<?php echo baseUrl('prestaciones-pacientes/create/' . $patient['id']); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.75rem; background: rgba(255, 255, 255, 0.15); color: white; border: 2px solid rgba(255, 255, 255, 0.4); border-radius: 14px; font-weight: 600; font-size: 0.9rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='rgba(255, 255, 255, 0.25)'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'; this.style.transform='translateY(0)'">
                <i class="bi bi-plus-circle-fill"></i>
                Asignar Servicio
            </a>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 0.6; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.1); }
}
</style>

<!-- Datos del Paciente -->
<div class="patient-hero">
    <div class="patient-stats">
        <div class="stat-box">
            <div class="stat-label">
                <i class="bi bi-geo-alt-fill"></i>
                Localidad
            </div>
            <div class="stat-value"><?php echo htmlspecialchars($patient['localidad'] ?? 'No especificada'); ?></div>
        </div>
        <div class="stat-box">
            <div class="stat-label">
                <i class="bi bi-heart-pulse-fill"></i>
                Obra Social
            </div>
            <div class="stat-value">
                <?php if (!empty($patient['obra_social_nombre'])): ?>
                    <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                        <span><?php echo htmlspecialchars($patient['obra_social_nombre']); ?></span>
                        <?php if (!empty($patient['obra_social_sigla'])): ?>
                            <span style="display: inline-block; background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue)); color: white; padding: 0.25rem 0.75rem; border-radius: 8px; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                <?php echo htmlspecialchars($patient['obra_social_sigla']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    Particular
                <?php endif; ?>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-label">
                <i class="bi bi-activity"></i>
                Estado
            </div>
            <div class="stat-value">
                <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: <?php echo $patient['estado'] === 'activo' ? '#10b981' : '#6b7280'; ?>; color: white; padding: 0.5rem 1.25rem; border-radius: 12px; font-size: 0.95rem; font-weight: 600; box-shadow: 0 2px 8px <?php echo $patient['estado'] === 'activo' ? 'rgba(16, 185, 129, 0.3)' : 'rgba(107, 114, 128, 0.3)'; ?>;">
                    <i class="bi bi-<?php echo $patient['estado'] === 'activo' ? 'check-circle-fill' : 'pause-circle-fill'; ?>"></i>
                    <?php echo ucfirst($patient['estado']); ?>
                </span>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-label">
                <i class="bi bi-check2-circle"></i>
                Servicios Activos
            </div>
            <div class="stat-value" style="color: var(--stormy-cyan); font-size: 2.5rem; font-weight: 800;">
                <?php echo count(array_filter($servicios, fn($s) => $s['estado'] === 'activo')); ?>
            </div>
        </div>
    </div>
</div>

<!-- Servicios del Paciente -->
<div class="section-header">
    <div class="section-icon">
        <i class="bi bi-heart-pulse-fill"></i>
    </div>
    <h2 class="section-title">Servicios Asignados</h2>
</div>

<?php if (empty($servicios)): ?>
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="bi bi-inbox"></i>
        </div>
        <div class="empty-state-title">No hay servicios asignados</div>
        <p class="empty-state-text">Este paciente aún no tiene servicios médicos asignados.</p>
        <a href="<?php echo baseUrl('prestaciones-pacientes/create/' . $patient['id']); ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Asignar primer servicio
        </a>
    </div>
<?php else: ?>
    <div class="services-grid">
    <?php foreach ($servicios as $servicio): ?>
        <div class="service-card">
            <div class="service-header">
                <h3 class="service-title"><?php echo htmlspecialchars($servicio['prestacion_nombre']); ?></h3>
                <div class="service-badges">
                    <span class="service-badge">
                        <?php echo ucfirst($servicio['estado']); ?>
                    </span>
                    <?php if ($servicio['es_recurrente']): ?>
                        <span class="service-badge">
                            <i class="bi bi-arrow-repeat me-1"></i>Recurrente
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="service-body">
                <?php if (!empty($servicio['tipo_prestacion_nombre'])): ?>
                    <div style="margin-bottom: 1.5rem; padding: 0.75rem; background: rgba(136, 219, 242, 0.08); border-radius: 10px; border-left: 4px solid var(--stormy-cyan);">
                        <small style="color: var(--stormy-blue); font-weight: 600; text-transform: uppercase; font-size: 0.7rem;">Tipo</small>
                        <div style="color: var(--stormy-dark); font-weight: 600; margin-top: 0.25rem;"><?php echo htmlspecialchars($servicio['tipo_prestacion_nombre']); ?></div>
                    </div>
                <?php endif; ?>

                <div class="service-info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Profesional</div>
                            <div class="info-value"><?php echo htmlspecialchars($servicio['profesional_nombre']); ?></div>
                            <?php if (!empty($servicio['profesional_especialidad'])): ?>
                                <div class="info-sub"><?php echo htmlspecialchars($servicio['profesional_especialidad']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($servicio['empresa_nombre'])): ?>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Empresa</div>
                                <div class="info-value"><?php echo htmlspecialchars($servicio['empresa_nombre']); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-calendar-range"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Período</div>
                            <div class="info-value">
                                <?php echo date('d/m/Y', strtotime($servicio['fecha_inicio'])); ?>
                            </div>
                            <div class="info-sub">
                                <?php if ($servicio['fecha_fin']): ?>
                                    hasta <?php echo date('d/m/Y', strtotime($servicio['fecha_fin'])); ?>
                                <?php else: ?>
                                    Indefinido
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php
                    // Detectar modo de frecuencia
                    $modoFrecuencia = $servicio['modo_frecuencia'] ?? 'sesiones';
                    $tieneFrencuencia = !empty($servicio['frecuencia_servicio']) || !empty($servicio['id_frecuencia']) || !empty($servicio['horas_semana']);

                    if ($tieneFrencuencia):
                    ?>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Frecuencia</div>
                                <div class="info-value">
                                    <?php
                                    if ($modoFrecuencia === 'horas') {
                                        // Modo horas: mostrar horas por semana y días
                                        $horasSemana = floatval($servicio['horas_semana'] ?? 0);
                                        echo $horasSemana . ' hs/semana';

                                        // Mostrar distribución por días si existe
                                        if (!empty($servicio['dias_semana'])) {
                                            $dias = is_string($servicio['dias_semana']) ? json_decode($servicio['dias_semana'], true) : $servicio['dias_semana'];
                                            if (is_array($dias)) {
                                                $diasActivos = [];
                                                $nombresDias = ['lun' => 'Lun', 'mar' => 'Mar', 'mie' => 'Mié', 'jue' => 'Jue', 'vie' => 'Vie', 'sab' => 'Sáb', 'dom' => 'Dom'];
                                                foreach ($dias as $dia => $horasDia) {
                                                    if ($horasDia > 0) {
                                                        $diasActivos[] = $nombresDias[$dia] ?? $dia;
                                                    }
                                                }
                                                if (!empty($diasActivos)) {
                                                    echo ' <small style="color: var(--stormy-blue);">(' . implode(', ', $diasActivos) . ')</small>';
                                                }
                                            }
                                        }
                                    } else {
                                        // Modo sesiones (comportamiento actual)
                                        if (!empty($servicio['frecuencia_nombre'])) {
                                            echo htmlspecialchars($servicio['frecuencia_nombre']);
                                            if ($servicio['id_frecuencia'] == 9 && !empty($servicio['sesiones_personalizadas'])) {
                                                echo ' (' . $servicio['sesiones_personalizadas'] . ' sesiones/mes)';
                                            } elseif (!empty($servicio['frecuencia_sesiones'])) {
                                                echo ' (' . $servicio['frecuencia_sesiones'] . ' sesiones/mes)';
                                            }
                                        } else {
                                            echo htmlspecialchars($servicio['frecuencia_servicio']);
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($servicio['observaciones'])): ?>
                    <div class="observations-box">
                        <div class="observations-label">
                            <i class="bi bi-chat-left-text"></i>
                            Observaciones
                        </div>
                        <div class="observations-text">
                            <?php echo nl2br(htmlspecialchars($servicio['observaciones'])); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="service-footer">
                    <div class="price-tags">
                        <?php if ($servicio['valor_profesional']): ?>
                            <div class="price-tag">
                                <i class="bi bi-person-badge"></i>
                                <span>$<?php echo number_format($servicio['valor_profesional'], 2); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($servicio['valor_empresa'] && !isCoordinator()): ?>
                            <div class="price-tag">
                                <i class="bi bi-building"></i>
                                <span>$<?php echo number_format($servicio['valor_empresa'], 2); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="action-buttons">
                        <a href="<?php echo baseUrl('prestaciones-pacientes/edit/' . $servicio['id']); ?>" class="btn-action">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </a>
                        <?php if (hasRole('administrador') && $servicio['estado'] === 'activo'): ?>
                            <button type="button" class="btn-action delete" onclick="confirmDelete(<?php echo $servicio['id']; ?>)">
                                <i class="bi bi-trash me-1"></i>Finalizar
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
    <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Sección de Archivos -->
<?php include __DIR__ . '/_archivos_section.php'; ?>

<div class="mt-4">
    <a href="<?php echo baseUrl('patients'); ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Volver a Pacientes
    </a>
</div>

<!-- Form para eliminar -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
</form>

<!-- Modal de Confirmación -->
<div id="confirmModal" class="confirm-modal-overlay">
    <div class="confirm-modal">
        <div class="confirm-modal-header">
            <div class="confirm-modal-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <h3 class="confirm-modal-title">Finalizar Servicio</h3>
        </div>
        <div class="confirm-modal-body">
            <p class="confirm-modal-message">¿Está seguro que desea finalizar este servicio? Esta acción marcará el servicio como inactivo.</p>
        </div>
        <div class="confirm-modal-footer">
            <button type="button" class="confirm-modal-btn confirm-modal-btn-cancel" onclick="closeConfirmModal()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
                <span>Cancelar</span>
            </button>
            <button type="button" class="confirm-modal-btn confirm-modal-btn-confirm" onclick="submitDelete()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                <span>Sí, finalizar</span>
            </button>
        </div>
    </div>
</div>

<script>
    let deleteServiceId = null;

    function confirmDelete(id) {
        deleteServiceId = id;
        document.getElementById('confirmModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.remove('active');
        document.body.style.overflow = '';
        deleteServiceId = null;
    }

    function submitDelete() {
        if (deleteServiceId) {
            const form = document.getElementById('deleteForm');
            form.action = '<?php echo baseUrl('prestaciones-pacientes/delete/'); ?>' + deleteServiceId;
            form.submit();
        }
    }

    // Cerrar modal al hacer click fuera
    document.getElementById('confirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmModal();
        }
    });

    // Cerrar modal con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmModal();
        }
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

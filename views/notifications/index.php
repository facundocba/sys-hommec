<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(136, 219, 242, 0.2);
    }

    .notifications-title {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--stormy-dark);
    }

    .notifications-title i {
        color: var(--stormy-cyan);
    }

    .notifications-filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-label {
        font-weight: 600;
        color: var(--stormy-dark);
        font-size: 0.875rem;
    }

    .filter-select {
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: white;
        color: var(--stormy-dark);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--stormy-cyan);
        box-shadow: 0 0 0 3px rgba(136, 219, 242, 0.1);
    }

    .notification-item {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(255, 255, 255, 0.95) 100%);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(136, 219, 242, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        box-shadow:
            0 4px 16px rgba(56, 73, 89, 0.08),
            0 2px 4px rgba(0, 0, 0, 0.04);
        position: relative;
    }

    .notification-item.unread {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%);
        border-color: var(--stormy-cyan);
        box-shadow:
            0 6px 24px rgba(136, 219, 242, 0.15),
            0 2px 8px rgba(56, 73, 89, 0.08);
    }

    .notification-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        border-radius: 16px 0 0 16px;
    }

    .notification-item:hover {
        transform: translateY(-4px);
        border-color: var(--stormy-cyan);
        box-shadow:
            0 8px 32px rgba(136, 219, 242, 0.2),
            0 4px 12px rgba(56, 73, 89, 0.1);
    }

    .notification-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .notification-icon.archivo_subido {
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        color: white;
    }

    .notification-icon.paciente_vencimiento {
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: white;
    }

    .notification-icon.sistema {
        background: linear-gradient(135deg, #8b5cf6, #a855f7);
        color: white;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--stormy-dark);
        margin-bottom: 0.5rem;
    }

    .notification-message {
        font-size: 0.95rem;
        color: #475569;
        margin-bottom: 0.75rem;
        line-height: 1.6;
    }

    .notification-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .notification-meta i {
        color: var(--stormy-cyan);
    }

    .notification-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
    }

    .btn-mark-read {
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-mark-read:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(136, 219, 242, 0.4);
    }

    .btn-mark-all-read {
        background: linear-gradient(135deg, var(--stormy-dark), var(--stormy-blue));
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-mark-all-read:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(56, 73, 89, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 2px dashed rgba(136, 219, 242, 0.3);
        box-shadow:
            0 4px 16px rgba(56, 73, 89, 0.08),
            0 2px 4px rgba(0, 0, 0, 0.04);
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, rgba(136, 219, 242, 0.1), rgba(189, 221, 252, 0.1));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: var(--stormy-cyan);
    }

    .empty-state-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--stormy-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        color: #64748b;
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .notifications-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .notification-item {
            flex-direction: column;
        }

        .notification-actions {
            width: 100%;
            flex-direction: row;
            justify-content: space-between;
        }
    }
</style>

<div class="notifications-header">
    <h1 class="notifications-title">
        <i class="bi bi-bell"></i>
        Notificaciones
    </h1>
    <?php if (!empty($notificaciones)): ?>
        <form method="POST" action="<?php echo baseUrl('notifications/markAllAsRead'); ?>" style="display: inline;">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <button type="submit" class="btn-mark-all-read">
                <i class="bi bi-check-all"></i>
                Marcar todas como leídas
            </button>
        </form>
    <?php endif; ?>
</div>

<!-- Filtros -->
<form method="GET" action="<?php echo baseUrl('notifications'); ?>" class="notifications-filters">
    <div class="filter-group">
        <label class="filter-label" for="leida">Estado:</label>
        <select name="leida" id="leida" class="filter-select" onchange="this.form.submit()">
            <option value="">Todas</option>
            <option value="0" <?php echo (isset($_GET['leida']) && $_GET['leida'] === '0') ? 'selected' : ''; ?>>No leídas</option>
            <option value="1" <?php echo (isset($_GET['leida']) && $_GET['leida'] === '1') ? 'selected' : ''; ?>>Leídas</option>
        </select>
    </div>

    <div class="filter-group">
        <label class="filter-label" for="tipo">Tipo:</label>
        <select name="tipo" id="tipo" class="filter-select" onchange="this.form.submit()">
            <option value="">Todos</option>
            <option value="archivo_subido" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'archivo_subido') ? 'selected' : ''; ?>>Archivos</option>
            <option value="paciente_vencimiento" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'paciente_vencimiento') ? 'selected' : ''; ?>>Vencimientos</option>
            <option value="sistema" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'sistema') ? 'selected' : ''; ?>>Sistema</option>
        </select>
    </div>
</form>

<!-- Lista de Notificaciones -->
<?php if (empty($notificaciones)): ?>
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="bi bi-bell-slash"></i>
        </div>
        <h3 class="empty-state-title">No hay notificaciones</h3>
        <p class="empty-state-text">No tienes notificaciones en este momento.</p>
    </div>
<?php else: ?>
    <?php foreach ($notificaciones as $notificacion): ?>
        <div class="notification-item <?php echo $notificacion['leida'] ? '' : 'unread'; ?>">
            <div class="notification-icon <?php echo $notificacion['tipo']; ?>">
                <?php
                $icon = match($notificacion['tipo']) {
                    'archivo_subido' => 'bi-file-earmark-arrow-up',
                    'paciente_vencimiento' => 'bi-exclamation-triangle',
                    'sistema' => 'bi-info-circle',
                    default => 'bi-bell'
                };
                ?>
                <i class="bi <?php echo $icon; ?>"></i>
            </div>

            <div class="notification-content">
                <div class="notification-title"><?php echo htmlspecialchars($notificacion['titulo']); ?></div>
                <div class="notification-message"><?php echo htmlspecialchars($notificacion['mensaje']); ?></div>
                <div class="notification-meta">
                    <span>
                        <i class="bi bi-calendar"></i>
                        <?php echo date('d/m/Y H:i', strtotime($notificacion['fecha_creacion'])); ?>
                    </span>
                    <?php if ($notificacion['paciente_nombre']): ?>
                        <span>
                            <i class="bi bi-person"></i>
                            <?php echo htmlspecialchars($notificacion['paciente_nombre']); ?>
                        </span>
                    <?php endif; ?>
                    <?php if ($notificacion['leida']): ?>
                        <span>
                            <i class="bi bi-check-circle"></i>
                            Leída el <?php echo date('d/m/Y H:i', strtotime($notificacion['fecha_leida'])); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="notification-actions">
                <?php if (!$notificacion['leida']): ?>
                    <button type="button" class="btn-mark-read" onclick="markAsRead(<?php echo $notificacion['id']; ?>)">
                        <i class="bi bi-check"></i>
                        Marcar como leída
                    </button>
                <?php endif; ?>
                <?php if ($notificacion['id_paciente']): ?>
                    <a href="<?php echo baseUrl('patients/view/' . $notificacion['id_paciente']); ?>"
                       class="btn-mark-read" style="text-decoration: none;">
                        <i class="bi bi-eye"></i>
                        Ver paciente
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
function markAsRead(id) {
    fetch('<?php echo baseUrl('notifications/markAsRead/'); ?>' + id, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error al marcar como leída');
        }
    })
    .catch(error => {
        alert('Error al marcar como leída');
    });
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<?php
include __DIR__ . '/../layouts/header.php';

$formData = $_SESSION['form_data'] ?? [];
$formErrors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_data'], $_SESSION['form_errors']);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    .form-section {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .form-section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .form-section-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
    }

    .form-section-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--stormy-dark);
        margin: 0;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--stormy-cyan);
        box-shadow: 0 0 0 3px rgba(136, 219, 242, 0.1);
        outline: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(136, 219, 242, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(136, 219, 242, 0.4);
    }

    .btn-secondary {
        background: white;
        border: 1px solid #e2e8f0;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        color: #475569;
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        border-color: var(--stormy-cyan);
        color: var(--stormy-cyan);
        transform: translateY(-2px);
    }

    .invalid-feedback {
        font-size: 0.8rem;
        color: #ef4444;
        margin-top: 0.25rem;
        display: block;
    }

    .is-invalid {
        border-color: #ef4444 !important;
    }

    .is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
</style>

<!-- Page Header -->
<div class="top-bar">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; font-weight: 700;">
            <i class="bi bi-pencil me-2"></i>
            <?php echo $title; ?>
        </h1>
    </div>
    <a href="<?php echo baseUrl('prestaciones'); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Volver
    </a>
</div>

<form method="POST" action="<?php echo baseUrl('prestaciones/update/' . $prestacion['id']); ?>">
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

    <!-- Datos de la Prestación -->
    <div class="form-section">
        <div class="form-section-header">
            <div class="form-section-icon">
                <i class="bi bi-heart-pulse"></i>
            </div>
            <h2 class="form-section-title">Datos de la Prestación</h2>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control <?php echo isset($formErrors['nombre']) ? 'is-invalid' : ''; ?>"
                       id="nombre" name="nombre" required
                       value="<?php echo htmlspecialchars($formData['nombre'] ?? $prestacion['nombre']); ?>"
                       placeholder="Ej: Kinesiología">
                <?php if (isset($formErrors['nombre'])): ?>
                    <div class="invalid-feedback"><?php echo $formErrors['nombre']; ?></div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="activo" <?php echo ($formData['estado'] ?? $prestacion['estado']) === 'activo' ? 'selected' : ''; ?>>Activo</option>
                    <option value="inactivo" <?php echo ($formData['estado'] ?? $prestacion['estado']) === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>

            <div class="col-12">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                          placeholder="Descripción de la prestación..."><?php echo htmlspecialchars($formData['descripcion'] ?? $prestacion['descripcion'] ?? ''); ?></textarea>
            </div>

            <div class="col-md-6">
                <label for="modo_frecuencia" class="form-label">Modo de Frecuencia</label>
                <select class="form-select" id="modo_frecuencia" name="modo_frecuencia">
                    <option value="sesiones" <?php echo ($formData['modo_frecuencia'] ?? $prestacion['modo_frecuencia'] ?? 'sesiones') === 'sesiones' ? 'selected' : ''; ?>>Sesiones por mes</option>
                    <option value="horas" <?php echo ($formData['modo_frecuencia'] ?? $prestacion['modo_frecuencia'] ?? '') === 'horas' ? 'selected' : ''; ?>>Horas por semana</option>
                </select>
                <small class="text-muted">Define cómo se mide la frecuencia de esta prestación</small>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>
            Guardar Cambios
        </button>
        <a href="<?php echo baseUrl('prestaciones'); ?>" class="btn btn-secondary">
            Cancelar
        </a>
    </div>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

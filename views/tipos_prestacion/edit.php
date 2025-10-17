<?php
$formData = $_SESSION['form_data'] ?? [];
$formErrors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_data'], $_SESSION['form_errors']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - MedFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo baseUrl('assets/css/style.css'); ?>">
</head>
<body>
    <?php include __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include __DIR__ . '/../partials/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="bi bi-tags me-2"></i>
                        <?php echo $title; ?>
                    </h1>
                    <a href="<?php echo baseUrl('tipos-prestacion'); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Volver
                    </a>
                </div>

                <?php if (hasFlash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo getFlash('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="<?php echo baseUrl('tipos-prestacion/update/' . $tipoPrestacion['id']); ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Información del Tipo</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control <?php echo isset($formErrors['nombre']) ? 'is-invalid' : ''; ?>"
                                                   id="nombre" name="nombre" required
                                                   value="<?php echo htmlspecialchars($formData['nombre'] ?? $tipoPrestacion['nombre']); ?>"
                                                   placeholder="Ej: Kinesiología, Enfermería, Cuidados Domiciliarios">
                                            <?php if (isset($formErrors['nombre'])): ?>
                                                <div class="invalid-feedback"><?php echo $formErrors['nombre']; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-12">
                                            <label for="descripcion" class="form-label">Descripción</label>
                                            <textarea class="form-control" id="descripcion" name="descripcion"
                                                      rows="3" placeholder="Descripción del tipo de prestación..."><?php echo htmlspecialchars($formData['descripcion'] ?? $tipoPrestacion['descripcion']); ?></textarea>
                                            <small class="text-muted">Describe brevemente este tipo de prestación</small>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="estado" class="form-label">Estado</label>
                                            <select class="form-select" id="estado" name="estado">
                                                <option value="activo" <?php echo ($formData['estado'] ?? $tipoPrestacion['estado']) === 'activo' ? 'selected' : ''; ?>>Activo</option>
                                                <option value="inactivo" <?php echo ($formData['estado'] ?? $tipoPrestacion['estado']) === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>
                                    Guardar Cambios
                                </button>
                                <a href="<?php echo baseUrl('tipos-prestacion'); ?>" class="btn btn-secondary">
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

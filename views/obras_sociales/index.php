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
    <style>
        .obras-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            padding: 2rem;
        }

        .obra-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.95) 100%);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(136, 219, 242, 0.25);
            border-radius: 18px;
            padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(136, 219, 242, 0.1);
        }

        .obra-card:hover {
            transform: translateY(-4px);
            border-color: var(--stormy-cyan);
            box-shadow: 0 8px 30px rgba(136, 219, 242, 0.2);
        }

        .obra-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1.2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid rgba(136, 219, 242, 0.15);
        }

        .obra-title {
            flex: 1;
        }

        .obra-title h5 {
            color: var(--stormy-dark);
            font-weight: 600;
            margin-bottom: 0.3rem;
            font-size: 1.2rem;
        }

        .obra-sigla {
            background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .obra-body {
            margin-bottom: 1rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0;
            color: #555;
            font-size: 0.9rem;
        }

        .info-item i {
            color: var(--stormy-cyan);
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .info-item .label {
            font-weight: 500;
            color: var(--stormy-dark);
            min-width: 80px;
        }

        .obra-footer {
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
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 1px solid rgba(136, 219, 242, 0.3);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../partials/navbar.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <?php include __DIR__ . '/../partials/sidebar.php'; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="bi bi-hospital me-2"></i>
                        <?php echo $title; ?>
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="<?php echo baseUrl('obras-sociales/create'); ?>" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>
                            Nueva Obra Social
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                <?php if (hasFlash('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo getFlash('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (hasFlash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo getFlash('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Filtros -->
                <div class="filter-section">
                    <form method="GET" action="<?php echo baseUrl('obras-sociales'); ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Buscar</label>
                                <input type="text" name="search" class="form-control"
                                       placeholder="Buscar por nombre o sigla..."
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

                <!-- Grid de Obras Sociales -->
                <?php if (empty($obrasSociales)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No se encontraron obras sociales.
                    </div>
                <?php else: ?>
                    <div class="obras-grid">
                        <?php foreach ($obrasSociales as $obra): ?>
                            <div class="obra-card">
                                <div class="obra-header">
                                    <div class="obra-title">
                                        <h5><?php echo htmlspecialchars($obra['nombre']); ?></h5>
                                        <?php if (!empty($obra['sigla'])): ?>
                                            <span class="obra-sigla"><?php echo htmlspecialchars($obra['sigla']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="obra-body">
                                    <?php if (!empty($obra['telefono'])): ?>
                                        <div class="info-item">
                                            <i class="bi bi-telephone"></i>
                                            <span class="label">Teléfono:</span>
                                            <span><?php echo htmlspecialchars($obra['telefono']); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($obra['email'])): ?>
                                        <div class="info-item">
                                            <i class="bi bi-envelope"></i>
                                            <span class="label">Email:</span>
                                            <span><?php echo htmlspecialchars($obra['email']); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($obra['direccion'])): ?>
                                        <div class="info-item">
                                            <i class="bi bi-geo-alt"></i>
                                            <span class="label">Dirección:</span>
                                            <span><?php echo htmlspecialchars($obra['direccion']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="obra-footer">
                                    <span class="badge-estado <?php echo $obra['estado'] === 'activo' ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo ucfirst($obra['estado']); ?>
                                    </span>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo baseUrl('obras-sociales/edit/' . $obra['id']); ?>"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if (hasRole('administrador')): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete(<?php echo $obra['id']; ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <!-- Form para eliminar -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(id) {
            if (confirm('¿Está seguro de desactivar esta obra social?')) {
                const form = document.getElementById('deleteForm');
                form.action = '<?php echo baseUrl('obras-sociales/delete/'); ?>' + id;
                form.submit();
            }
        }
    </script>
</body>
</html>

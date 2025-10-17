<?php
$formData = $_SESSION['form_data'] ?? [];
$formErrors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_data'], $_SESSION['form_errors']);
?>

<?php include __DIR__ . '/../layouts/header.php'; ?>

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

    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid #e2e8f0;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--stormy-cyan);
        border-color: var(--stormy-cyan);
    }

    .form-check-label {
        font-size: 0.95rem;
        font-weight: 500;
        color: #475569;
        cursor: pointer;
    }

    .text-muted {
        font-size: 0.8rem;
        color: #94a3b8 !important;
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
    <a href="<?php echo baseUrl('patients/view/' . $prestacion['id_paciente']); ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Volver
    </a>
</div>

<form method="POST" action="<?php echo baseUrl('prestaciones-pacientes/update/' . $prestacion['id']); ?>">
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

    <!-- Servicio y Profesional -->
    <div class="form-section">
        <div class="form-section-header">
            <div class="form-section-icon">
                <i class="bi bi-heart-pulse"></i>
            </div>
            <h2 class="form-section-title">Prestación y Profesional</h2>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="id_tipo_prestacion" class="form-label">Prestación <span class="text-danger">*</span></label>
                <select class="form-select <?php echo isset($formErrors['id_tipo_prestacion']) ? 'is-invalid' : ''; ?>"
                        id="id_tipo_prestacion" name="id_tipo_prestacion" required>
                    <option value="">Seleccionar prestación</option>
                    <?php foreach ($prestaciones as $prestacion_item): ?>
                        <option value="<?php echo $prestacion_item['id']; ?>"
                                <?php echo ($formData['id_tipo_prestacion'] ?? $prestacion['id_tipo_prestacion']) == $prestacion_item['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($prestacion_item['nombre']); ?>
                            <?php if (!empty($prestacion_item['descripcion'])): ?>
                                - <?php echo htmlspecialchars($prestacion_item['descripcion']); ?>
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($formErrors['id_tipo_prestacion'])): ?>
                    <div class="invalid-feedback"><?php echo $formErrors['id_tipo_prestacion']; ?></div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label for="id_profesional" class="form-label">Profesional <span class="text-danger">*</span></label>
                <select class="form-select <?php echo isset($formErrors['id_profesional']) ? 'is-invalid' : ''; ?>"
                        id="id_profesional" name="id_profesional" required>
                    <option value="">Seleccionar profesional</option>
                    <?php foreach ($professionals as $prof): ?>
                        <option value="<?php echo $prof['id']; ?>"
                                <?php echo ($formData['id_profesional'] ?? $prestacion['id_profesional']) == $prof['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($prof['nombre']); ?>
                            <?php if (!empty($prof['especialidad'])): ?>
                                - <?php echo htmlspecialchars($prof['especialidad']); ?>
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($formErrors['id_profesional'])): ?>
                    <div class="invalid-feedback"><?php echo $formErrors['id_profesional']; ?></div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label for="id_empresa" class="form-label">Empresa (opcional)</label>
                <select class="form-select" id="id_empresa" name="id_empresa">
                    <option value="">Sin empresa (profesional independiente)</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?php echo $company['id']; ?>"
                                <?php echo ($formData['id_empresa'] ?? $prestacion['id_empresa']) == $company['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($company['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted">Selecciona si la prestación se brinda a través de una empresa</small>
            </div>
        </div>
    </div>

    <!-- Fechas -->
    <div class="form-section">
        <div class="form-section-header">
            <div class="form-section-icon">
                <i class="bi bi-calendar-range"></i>
            </div>
            <h2 class="form-section-title">Período de Prestación</h2>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="fecha_inicio" class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                <input type="date" class="form-control <?php echo isset($formErrors['fecha_inicio']) ? 'is-invalid' : ''; ?>"
                       id="fecha_inicio" name="fecha_inicio" required
                       value="<?php echo htmlspecialchars($formData['fecha_inicio'] ?? $prestacion['fecha_inicio']); ?>">
                <?php if (isset($formErrors['fecha_inicio'])): ?>
                    <div class="invalid-feedback"><?php echo $formErrors['fecha_inicio']; ?></div>
                <?php endif; ?>
            </div>

            <div class="col-md-6" id="fechaFinGroup">
                <label for="fecha_fin" class="form-label">Fecha de Finalización</label>
                <input type="date" class="form-control <?php echo isset($formErrors['fecha_fin']) ? 'is-invalid' : ''; ?>"
                       id="fecha_fin" name="fecha_fin"
                       value="<?php echo htmlspecialchars($formData['fecha_fin'] ?? $prestacion['fecha_fin'] ?? ''); ?>">
                <small class="text-muted" id="fechaHint">Fecha estimada de finalización</small>
                <?php if (isset($formErrors['fecha_fin'])): ?>
                    <div class="invalid-feedback"><?php echo $formErrors['fecha_fin']; ?></div>
                <?php endif; ?>
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="es_recurrente" name="es_recurrente" value="1"
                           <?php echo !empty($formData['es_recurrente'] ?? $prestacion['es_recurrente']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="es_recurrente">
                        Prestación Recurrente (sin fecha de finalización)
                    </label>
                </div>
            </div>

            <div class="col-6">
                <label for="id_frecuencia" class="form-label">Frecuencia del Servicio</label>
                <select
                    id="id_frecuencia"
                    name="id_frecuencia"
                    class="form-control form-select"
                    onchange="toggleCustomFrequency()"
                >
                    <option value="">Seleccione una frecuencia</option>
                    <?php foreach ($frecuencias as $freq): ?>
                        <option
                            value="<?= $freq['id'] ?>"
                            data-sessions="<?= $freq['sesiones_por_mes'] ?>"
                            <?= (isset($formData['id_frecuencia']) ? $formData['id_frecuencia'] : ($prestacion['id_frecuencia'] ?? '')) == $freq['id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($freq['nombre']) ?> (<?= $freq['sesiones_por_mes'] ?> sesiones/mes)
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="frecuencia_servicio" id="frecuencia_servicio_hidden" value="<?= htmlspecialchars($formData['frecuencia_servicio'] ?? $prestacion['frecuencia_servicio'] ?? '') ?>">
            </div>

            <div class="col-6" id="custom-frequency-wrapper" style="display: none;">
                <label for="sesiones_personalizadas" class="form-label">Sesiones Personalizadas por Mes</label>
                <input
                    type="number"
                    id="sesiones_personalizadas"
                    name="sesiones_personalizadas"
                    class="form-control"
                    min="1"
                    max="30"
                    placeholder="Ej: 6"
                    value="<?= htmlspecialchars($formData['sesiones_personalizadas'] ?? $prestacion['sesiones_personalizadas'] ?? '') ?>"
                >
                <small class="text-muted">Solo si seleccionó "Personalizada"</small>
            </div>
        </div>
    </div>

    <!-- Valores y Observaciones -->
    <div class="form-section">
        <div class="form-section-header">
            <div class="form-section-icon">
                <i class="bi bi-cash-coin"></i>
            </div>
            <h2 class="form-section-title">Valores y Observaciones</h2>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="valor_profesional" class="form-label">Valor Profesional</label>
                <input type="number" step="0.01" class="form-control <?php echo isset($formErrors['valor_profesional']) ? 'is-invalid' : ''; ?>"
                       id="valor_profesional" name="valor_profesional"
                       value="<?php echo htmlspecialchars($formData['valor_profesional'] ?? $prestacion['valor_profesional'] ?? ''); ?>"
                       placeholder="0.00">
                <?php if (isset($formErrors['valor_profesional'])): ?>
                    <div class="invalid-feedback"><?php echo $formErrors['valor_profesional']; ?></div>
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label for="valor_empresa" class="form-label">Valor Empresa</label>
                <input type="number" step="0.01" class="form-control <?php echo isset($formErrors['valor_empresa']) ? 'is-invalid' : ''; ?>"
                       id="valor_empresa" name="valor_empresa"
                       value="<?php echo htmlspecialchars($formData['valor_empresa'] ?? $prestacion['valor_empresa'] ?? ''); ?>"
                       placeholder="0.00">
                <?php if (isset($formErrors['valor_empresa'])): ?>
                    <div class="invalid-feedback"><?php echo $formErrors['valor_empresa']; ?></div>
                <?php endif; ?>
            </div>

            <div class="col-12">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"
                          placeholder="Información adicional sobre la prestación..."><?php echo htmlspecialchars($formData['observaciones'] ?? $prestacion['observaciones'] ?? ''); ?></textarea>
            </div>

            <div class="col-md-4">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="activo" <?php echo ($formData['estado'] ?? $prestacion['estado']) === 'activo' ? 'selected' : ''; ?>>Activo</option>
                    <option value="pausado" <?php echo ($formData['estado'] ?? $prestacion['estado']) === 'pausado' ? 'selected' : ''; ?>>Pausado</option>
                    <option value="finalizado" <?php echo ($formData['estado'] ?? $prestacion['estado']) === 'finalizado' ? 'selected' : ''; ?>>Finalizado</option>
                </select>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>
            Guardar Cambios
        </button>
        <a href="<?php echo baseUrl('patients/view/' . $prestacion['id_paciente']); ?>" class="btn btn-secondary">
            Cancelar
        </a>
    </div>
</form>

<script>
    const recurrenteCheckbox = document.getElementById('es_recurrente');
    const fechaFinInput = document.getElementById('fecha_fin');
    const fechaFinGroup = document.getElementById('fechaFinGroup');
    const fechaHint = document.getElementById('fechaHint');

    recurrenteCheckbox.addEventListener('change', toggleFechaFin);

    function toggleFechaFin() {
        if (recurrenteCheckbox.checked) {
            fechaFinGroup.classList.add('disabled');
            fechaFinInput.removeAttribute('required');
            fechaFinInput.value = '';
            fechaHint.textContent = 'No aplica para servicios recurrentes';
        } else {
            fechaFinGroup.classList.remove('disabled');
            fechaFinInput.setAttribute('required', 'required');
            fechaHint.textContent = 'Fecha estimada de finalización del servicio';
        }
    }

    // Ejecutar al cargar
    toggleFechaFin();

    // Toggle campo de sesiones personalizadas
    function toggleCustomFrequency() {
        const frecuenciaSelect = document.getElementById('id_frecuencia');
        const customWrapper = document.getElementById('custom-frequency-wrapper');
        const frecuenciaHidden = document.getElementById('frecuencia_servicio_hidden');

        const selectedOption = frecuenciaSelect.options[frecuenciaSelect.selectedIndex];
        const selectedText = selectedOption ? selectedOption.text : '';

        // Guardar el texto de la frecuencia seleccionada en el campo hidden
        frecuenciaHidden.value = selectedText;

        // Mostrar campo de sesiones personalizadas si se selecciona "Personalizada" (id = 9)
        if (frecuenciaSelect.value == '9') {
            customWrapper.style.display = 'block';
            document.getElementById('sesiones_personalizadas').setAttribute('required', 'required');
        } else {
            customWrapper.style.display = 'none';
            document.getElementById('sesiones_personalizadas').removeAttribute('required');
        }
    }

    // Ejecutar toggle al cargar
    toggleCustomFrequency();
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

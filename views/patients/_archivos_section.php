<style>
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -0.5rem;
    }

    .row.g-3 > * {
        padding: 0 0.5rem;
        margin-bottom: 1rem;
    }

    .col-12 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .col-md-8 {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
    }

    .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }

    .form-control {
        display: block;
        width: 100%;
        padding: 0.5rem 0.75rem;
        font-size: 0.95rem;
        line-height: 1.5;
        color: #475569;
        background-color: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: var(--stormy-cyan);
        outline: 0;
        box-shadow: 0 0 0 3px rgba(136, 219, 242, 0.1);
    }

    .w-100 {
        width: 100% !important;
    }

    @media (max-width: 768px) {
        .col-md-8, .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    .files-section {
        margin-top: 3rem;
    }

    .file-item {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .file-item:hover {
        transform: translateY(-2px);
        border-color: var(--stormy-cyan);
        box-shadow: 0 4px 16px rgba(136, 219, 242, 0.2);
    }

    .file-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .file-info {
        flex: 1;
    }

    .file-name {
        font-size: 1rem;
        font-weight: 600;
        color: var(--stormy-dark);
        margin-bottom: 0.25rem;
    }

    .file-meta {
        font-size: 0.875rem;
        color: #666;
    }

    .file-actions {
        display: flex;
        gap: 0.5rem;
    }

    .upload-form {
        background: white;
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s;
    }

    .upload-form:hover {
        border-color: var(--stormy-cyan);
        background: white;
    }

    .upload-area {
        padding: 1rem;
    }

    .upload-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.75rem;
        box-shadow: 0 4px 12px rgba(136, 219, 242, 0.3);
    }

    input[type="file"] {
        cursor: pointer;
    }

    input[type="file"]::file-selector-button {
        background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue));
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        margin-right: 1rem;
        transition: all 0.2s;
    }

    input[type="file"]::file-selector-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(136, 219, 242, 0.4);
    }
</style>

<!-- Sección de Archivos -->
<div class="files-section">
    <div class="section-header">
        <div class="section-icon">
            <i class="bi bi-file-earmark-arrow-up"></i>
        </div>
        <h2 class="section-title">Archivos del Paciente</h2>
    </div>

    <!-- Formulario de Subida -->
    <div class="upload-form">
        <form method="POST" action="<?php echo baseUrl('files/upload/' . $patient['id']); ?>" enctype="multipart/form-data" id="uploadForm">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="enviar_email" id="enviar_email" value="0">
            <input type="hidden" name="tipo_documento" id="tipo_documento" value="">
            <input type="hidden" name="asunto_email" id="asunto_email" value="">
            <input type="hidden" name="comentario_email" id="comentario_email" value="">

            <div class="upload-area">
                <div class="upload-icon">
                    <i class="bi bi-cloud-upload"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: var(--stormy-dark); margin-bottom: 0.5rem;">
                    Subir Archivo
                </h3>
                <p style="color: #666; margin-bottom: 1.5rem;">
                    Formatos permitidos: PDF, DOC, DOCX, JPG, PNG (Máx. 10MB)
                </p>

                <div class="row g-3">
                    <div class="col-md-8">
                        <input type="file" name="archivo" class="form-control" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary w-100" onclick="showUploadModal()">
                            <i class="bi bi-upload me-1"></i>
                            Subir Archivo
                        </button>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="descripcion" class="form-control" placeholder="Descripción del archivo (opcional)">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de Archivos -->
    <?php if (empty($archivos)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-file-earmark-x"></i>
            </div>
            <h3 class="empty-state-title">No hay archivos</h3>
            <p class="empty-state-text">Aún no se han subido archivos para este paciente.</p>
        </div>
    <?php else: ?>
        <?php foreach ($archivos as $archivo): ?>
            <div class="file-item">
                <div class="file-icon">
                    <?php
                    $extension = strtolower(pathinfo($archivo['nombre_archivo'], PATHINFO_EXTENSION));
                    $icon = match($extension) {
                        'pdf' => 'bi-file-pdf',
                        'doc', 'docx' => 'bi-file-word',
                        'jpg', 'jpeg', 'png' => 'bi-file-image',
                        default => 'bi-file-earmark'
                    };
                    ?>
                    <i class="bi <?php echo $icon; ?>"></i>
                </div>

                <div class="file-info">
                    <div class="file-name"><?php echo htmlspecialchars($archivo['nombre_original']); ?></div>
                    <div class="file-meta">
                        <i class="bi bi-calendar me-1"></i>
                        <?php echo date('d/m/Y H:i', strtotime($archivo['fecha_subida'])); ?>
                        &nbsp;•&nbsp;
                        <i class="bi bi-person me-1"></i>
                        <?php echo htmlspecialchars($archivo['usuario_nombre']); ?>
                        &nbsp;•&nbsp;
                        <i class="bi bi-hdd me-1"></i>
                        <?php echo formatFileSize($archivo['tamano']); ?>
                    </div>
                    <?php if ($archivo['descripcion']): ?>
                        <div class="file-meta mt-1">
                            <i class="bi bi-chat-left-text me-1"></i>
                            <?php echo htmlspecialchars($archivo['descripcion']); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="file-actions">
                    <a href="<?php echo baseUrl('files/view/' . $archivo['id']); ?>"
                       class="btn-action" target="_blank" title="Ver archivo">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="<?php echo baseUrl('files/download/' . $archivo['id']); ?>"
                       class="btn-action" title="Descargar">
                        <i class="bi bi-download"></i>
                    </a>
                    <?php if (hasRole('administrador')): ?>
                        <button type="button" class="btn-action delete"
                                onclick="confirmDeleteFile(<?php echo $archivo['id']; ?>)"
                                title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Form para eliminar archivo -->
<form id="deleteFileForm" method="POST" style="display: none;">
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
</form>

<!-- Modal de Envío de Receta -->
<div id="uploadModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto;">
    <div style="background: white; border-radius: 16px; padding: 2rem; max-width: 550px; width: 90%; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2); margin: 2rem auto;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--stormy-cyan), var(--stormy-blue)); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                <i class="bi bi-envelope"></i>
            </div>
            <h3 style="margin: 0; font-size: 1.5rem; color: var(--stormy-dark);">Enviar Documento a Empresa</h3>
        </div>

        <p style="color: #666; margin-bottom: 1.5rem;">
            ¿Desea enviar este documento por email a la empresa?
        </p>

        <div style="background: #f1f5f9; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                <input type="checkbox" id="modalEnviarEmail" style="width: 20px; height: 20px; cursor: pointer;">
                <span style="font-weight: 600; color: var(--stormy-dark);">Sí, enviar documento por email</span>
            </label>
        </div>

        <div id="emailFieldsContainer" style="display: none;">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--stormy-dark); margin-bottom: 0.5rem;">
                    Tipo de Documento <span style="color: #dc2626;">*</span>
                </label>
                <select id="modalTipoDocumento" class="form-control" required>
                    <option value="">Seleccione un tipo...</option>
                    <option value="receta">Receta médica (para facturación)</option>
                    <option value="documentacion">Documentación del paciente</option>
                    <option value="historial">Historial clínico</option>
                    <option value="autorizacion">Autorización médica</option>
                    <option value="estudios">Estudios / Análisis</option>
                    <option value="informe">Informe médico</option>
                    <option value="otro">Otro</option>
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--stormy-dark); margin-bottom: 0.5rem;">
                    Asunto <span style="color: #dc2626;">*</span>
                </label>
                <input type="text" id="modalAsunto" class="form-control" placeholder="Ej: Receta para autorización urgente" required>
                <small style="color: #64748b; font-size: 0.85rem;">Este texto aparecerá destacado en el email</small>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; color: var(--stormy-dark); margin-bottom: 0.5rem;">
                    Observaciones (opcional)
                </label>
                <textarea id="modalComentario" class="form-control" rows="4" placeholder="Información adicional relevante para la empresa..."></textarea>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeUploadModal()">
                <i class="bi bi-x-circle me-1"></i>
                Cancelar
            </button>
            <button type="button" class="btn btn-primary" onclick="submitUploadForm()">
                <i class="bi bi-check-circle me-1"></i>
                Confirmar
            </button>
        </div>
    </div>
</div>

<script>
    function showUploadModal() {
        // Validar que se haya seleccionado un archivo
        const fileInput = document.querySelector('input[name="archivo"]');
        if (!fileInput.files.length) {
            alert('Por favor seleccione un archivo primero');
            return;
        }

        // Mostrar modal
        const modal = document.getElementById('uploadModal');
        modal.style.display = 'flex';
    }

    function closeUploadModal() {
        const modal = document.getElementById('uploadModal');
        modal.style.display = 'none';
        document.getElementById('modalEnviarEmail').checked = false;
        document.getElementById('modalTipoDocumento').value = '';
        document.getElementById('modalAsunto').value = '';
        document.getElementById('modalComentario').value = '';
        document.getElementById('emailFieldsContainer').style.display = 'none';
    }

    function submitUploadForm() {
        const enviarEmail = document.getElementById('modalEnviarEmail').checked;

        if (enviarEmail) {
            const tipoDocumento = document.getElementById('modalTipoDocumento').value.trim();
            const asunto = document.getElementById('modalAsunto').value.trim();

            if (!tipoDocumento) {
                alert('Por favor seleccione el tipo de documento');
                return;
            }
            if (!asunto) {
                alert('Por favor complete el campo Asunto');
                return;
            }
        }

        const tipoDocumento = document.getElementById('modalTipoDocumento').value;
        const asunto = document.getElementById('modalAsunto').value;
        const comentario = document.getElementById('modalComentario').value;

        // Setear valores en campos hidden
        document.getElementById('enviar_email').value = enviarEmail ? '1' : '0';
        document.getElementById('tipo_documento').value = tipoDocumento;
        document.getElementById('asunto_email').value = asunto;
        document.getElementById('comentario_email').value = comentario;

        // Cerrar modal y enviar formulario
        closeUploadModal();
        document.getElementById('uploadForm').submit();
    }

    // Mostrar/ocultar campos de email cuando se marca el checkbox
    document.getElementById('modalEnviarEmail').addEventListener('change', function() {
        const emailFieldsContainer = document.getElementById('emailFieldsContainer');
        emailFieldsContainer.style.display = this.checked ? 'block' : 'none';
    });

    function confirmDeleteFile(id) {
        if (confirm('¿Está seguro de eliminar este archivo?')) {
            const form = document.getElementById('deleteFileForm');
            form.action = '<?php echo baseUrl('files/delete/'); ?>' + id;
            form.submit();
        }
    }
</script>

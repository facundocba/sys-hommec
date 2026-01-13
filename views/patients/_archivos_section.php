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

    /* Alert Modal */
    .alert-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(56, 73, 89, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10001;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .alert-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .alert-modal {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(250, 252, 255, 0.95) 100%
        );
        border-radius: 20px;
        padding: 2rem;
        max-width: 400px;
        width: 90%;
        box-shadow:
            0 20px 60px rgba(56, 73, 89, 0.2),
            0 8px 24px rgba(136, 219, 242, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        border: 1px solid rgba(136, 219, 242, 0.3);
        transform: scale(0.9) translateY(20px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .alert-modal-overlay.active .alert-modal {
        transform: scale(1) translateY(0);
    }

    .alert-modal::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg,
            transparent 0%,
            var(--stormy-cyan) 50%,
            transparent 100%
        );
    }

    .alert-modal-content {
        text-align: center;
    }

    .alert-modal-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg,
            rgba(245, 158, 11, 0.15) 0%,
            rgba(245, 158, 11, 0.08) 100%
        );
        border: 2px solid rgba(245, 158, 11, 0.3);
    }

    .alert-modal-icon svg {
        width: 32px;
        height: 32px;
        stroke: #f59e0b;
    }

    .alert-modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--stormy-dark);
        margin-bottom: 0.5rem;
    }

    .alert-modal-message {
        font-size: 0.9375rem;
        color: var(--stormy-blue);
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .alert-modal-btn {
        background: linear-gradient(135deg, var(--stormy-blue) 0%, var(--stormy-cyan) 100%);
        color: var(--white);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(106, 137, 167, 0.25);
    }

    .alert-modal-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(106, 137, 167, 0.35);
    }

    /* Confirm Modal for Delete */
    .confirm-delete-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(56, 73, 89, 0.75);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10001;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .confirm-delete-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .confirm-delete-modal {
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

    .confirm-delete-overlay.active .confirm-delete-modal {
        transform: scale(1) translateY(0);
    }

    .confirm-delete-modal::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg,
            transparent 0%,
            #ef4444 20%,
            #f87171 50%,
            #ef4444 80%,
            transparent 100%
        );
    }

    .confirm-delete-header {
        padding: 2rem 2.5rem 1.5rem;
        border-bottom: 1px solid rgba(136, 219, 242, 0.15);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .confirm-delete-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: linear-gradient(135deg,
            rgba(239, 68, 68, 0.15) 0%,
            rgba(239, 68, 68, 0.08) 100%
        );
        border: 2px solid rgba(239, 68, 68, 0.3);
    }

    .confirm-delete-icon svg {
        width: 28px;
        height: 28px;
        stroke: #ef4444;
    }

    .confirm-delete-title {
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

    .confirm-delete-body {
        padding: 2rem 2.5rem;
    }

    .confirm-delete-message {
        color: var(--stormy-blue);
        font-size: 1rem;
        font-weight: 500;
        line-height: 1.6;
        margin: 0;
    }

    .confirm-delete-footer {
        padding: 1.5rem 2.5rem 2rem;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .confirm-delete-btn {
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

    .confirm-delete-btn::before {
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

    .confirm-delete-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .confirm-delete-btn-cancel {
        background: rgba(106, 137, 167, 0.1);
        color: var(--stormy-blue);
        border: 2px solid rgba(106, 137, 167, 0.3);
    }

    .confirm-delete-btn-cancel:hover {
        background: rgba(106, 137, 167, 0.2);
        border-color: var(--stormy-blue);
        transform: translateY(-2px);
    }

    .confirm-delete-btn-confirm {
        background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
        color: var(--white);
        box-shadow:
            0 6px 20px rgba(239, 68, 68, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .confirm-delete-btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow:
            0 10px 28px rgba(239, 68, 68, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.15);
    }

    .confirm-delete-btn svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        position: relative;
        z-index: 1;
    }

    .confirm-delete-btn span {
        position: relative;
        z-index: 1;
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

<!-- Alert Modal -->
<div class="alert-modal-overlay" id="alertModalOverlay">
    <div class="alert-modal">
        <div class="alert-modal-content">
            <div class="alert-modal-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <h3 class="alert-modal-title">Atención</h3>
            <p class="alert-modal-message" id="alertModalMessage"></p>
            <button type="button" class="alert-modal-btn" onclick="closeAlertModal()">
                Entendido
            </button>
        </div>
    </div>
</div>

<!-- Confirm Delete Modal -->
<div class="confirm-delete-overlay" id="confirmDeleteOverlay">
    <div class="confirm-delete-modal">
        <div class="confirm-delete-header">
            <div class="confirm-delete-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <h3 class="confirm-delete-title">Eliminar Archivo</h3>
        </div>
        <div class="confirm-delete-body">
            <p class="confirm-delete-message">
                ¿Está seguro de que desea eliminar este archivo? Esta acción no se puede deshacer.
            </p>
        </div>
        <div class="confirm-delete-footer">
            <button type="button" class="confirm-delete-btn confirm-delete-btn-cancel" onclick="closeConfirmDeleteModal()">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>Cancelar</span>
            </button>
            <button type="button" class="confirm-delete-btn confirm-delete-btn-confirm" onclick="executeDeleteFile()">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
                <span>Eliminar</span>
            </button>
        </div>
    </div>
</div>

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
    // Alert Modal Functions
    function showAlertModal(message) {
        const overlay = document.getElementById('alertModalOverlay');
        const messageElement = document.getElementById('alertModalMessage');
        messageElement.textContent = message;
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeAlertModal() {
        const overlay = document.getElementById('alertModalOverlay');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Delete Confirmation Modal Functions
    let fileIdToDelete = null;

    function confirmDeleteFile(id) {
        fileIdToDelete = id;
        const overlay = document.getElementById('confirmDeleteOverlay');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmDeleteModal() {
        fileIdToDelete = null;
        const overlay = document.getElementById('confirmDeleteOverlay');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    function executeDeleteFile() {
        if (fileIdToDelete) {
            const form = document.getElementById('deleteFileForm');
            form.action = '<?php echo baseUrl('files/delete/'); ?>' + fileIdToDelete;
            form.submit();
        }
        closeConfirmDeleteModal();
    }

    // Close modals with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAlertModal();
            closeConfirmDeleteModal();
        }
    });

    // Close modals when clicking outside
    document.getElementById('alertModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAlertModal();
        }
    });

    document.getElementById('confirmDeleteOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmDeleteModal();
        }
    });

    function showUploadModal() {
        // Validar que se haya seleccionado un archivo
        const fileInput = document.querySelector('input[name="archivo"]');
        if (!fileInput.files.length) {
            showAlertModal('Por favor seleccione un archivo primero');
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
                showAlertModal('Por favor seleccione el tipo de documento');
                return;
            }
            if (!asunto) {
                showAlertModal('Por favor complete el campo Asunto');
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
</script>

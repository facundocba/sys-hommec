        </div>
    </div>

    <!-- Main JavaScript -->
    <script src="<?= asset('js/main.js') ?>"></script>

    <style>
        /* Confirmation Modal Styles */
        .confirm-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .confirm-modal.show {
            display: flex;
            opacity: 1;
        }

        .confirm-modal-content {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            transform: scale(0.9);
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .confirm-modal.show .confirm-modal-content {
            transform: scale(1);
        }

        .confirm-modal-header {
            padding: 2rem 2rem 1rem;
            text-align: center;
            border-bottom: 2px solid rgba(136, 219, 242, 0.2);
        }

        .confirm-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            stroke: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
            border-radius: 50%;
            padding: 12px;
        }

        .confirm-modal-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--stormy-dark);
        }

        .confirm-modal-body {
            padding: 1.5rem 2rem;
        }

        .confirm-modal-body p {
            margin: 0;
            font-size: 1rem;
            line-height: 1.6;
            color: var(--stormy-blue);
            text-align: center;
            white-space: pre-line;
        }

        .confirm-modal-footer {
            padding: 1rem 2rem 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-confirm-cancel,
        .btn-confirm-ok {
            padding: 0.75rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            min-width: 120px;
        }

        .btn-confirm-cancel {
            background: rgba(136, 219, 242, 0.1);
            color: var(--stormy-blue);
            border: 2px solid rgba(136, 219, 242, 0.3);
        }

        .btn-confirm-cancel:hover {
            background: rgba(136, 219, 242, 0.2);
            border-color: var(--stormy-cyan);
            transform: translateY(-2px);
        }

        .btn-confirm-ok {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-confirm-ok:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }
    </style>

    <script>
        // Global confirmation modal functions
        let confirmModalCallback = null;

        function showConfirmModal(message, title = 'Confirmar acción', onConfirm = null) {
            const modal = document.getElementById('confirmModal');
            const messageEl = document.getElementById('confirmModalMessage');
            const titleEl = document.getElementById('confirmModalTitle');
            const okBtn = document.getElementById('confirmModalOkBtn');

            messageEl.textContent = message;
            titleEl.textContent = title;
            confirmModalCallback = onConfirm;

            // Remove previous event listeners by cloning
            const newOkBtn = okBtn.cloneNode(true);
            okBtn.parentNode.replaceChild(newOkBtn, okBtn);

            // Add new event listener
            newOkBtn.addEventListener('click', function() {
                // Guardar callback antes de cerrar
                const callback = confirmModalCallback;
                closeConfirmModal();

                // Ejecutar callback después de cerrar
                if (callback) {
                    callback();
                }
            });

            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.remove('show');
            document.body.style.overflow = '';
            confirmModalCallback = null;
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeConfirmModal();
            }
        });

        // Close modal on backdrop click
        document.getElementById('confirmModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmModal();
            }
        });

        // Backward compatibility with old confirmDelete function
        function confirmDelete(message) {
            return confirm(message || '¿Está seguro que desea eliminar este elemento?');
        }

        // Format currency inputs
        function formatCurrency(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value) {
                input.value = parseFloat(value / 100).toFixed(2);
            }
        }

        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('mobileMenuBackdrop');
            const sidebarClose = document.getElementById('sidebarClose');

            if (mobileMenuToggle && sidebar && backdrop) {
                // Open menu
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.add('active');
                    backdrop.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });

                // Close menu - backdrop click
                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    backdrop.classList.remove('active');
                    document.body.style.overflow = '';
                });

                // Close menu - close button
                if (sidebarClose) {
                    sidebarClose.addEventListener('click', function() {
                        sidebar.classList.remove('active');
                        backdrop.classList.remove('active');
                        document.body.style.overflow = '';
                    });
                }

                // Close menu when clicking a link
                const navLinks = sidebar.querySelectorAll('.sidebar-nav-link');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar.classList.remove('active');
                        backdrop.classList.remove('active');
                        document.body.style.overflow = '';
                    });
                });

                // Close on ESC key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                        backdrop.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    </script>
</body>
</html>

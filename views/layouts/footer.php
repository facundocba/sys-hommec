        </div>
    </div>

    <!-- Main JavaScript -->
    <script src="<?= asset('js/main.js') ?>"></script>

    <script>
        // Confirm before delete actions
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

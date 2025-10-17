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
    </script>
</body>
</html>

-- Verificar tipos de columnas ID

SELECT
    TABLE_NAME,
    COLUMN_NAME,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_KEY,
    EXTRA
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME IN ('pacientes', 'prestaciones', 'profesionales', 'empresas')
  AND COLUMN_NAME = 'id'
ORDER BY TABLE_NAME;

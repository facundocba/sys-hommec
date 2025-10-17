-- Migración: Fix para agregar catálogos (sin duplicar id_obra_social)
-- Fecha: 2025-10-01

-- =====================================================
-- 5. Modificar tabla pacientes - Migrar obra_social a FK
-- =====================================================

-- Verificar si ya existe la columna
SET @column_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'pacientes'
    AND COLUMN_NAME = 'id_obra_social'
);

-- Intentar migrar datos existentes (si obra_social coincide con nombre en obras_sociales)
UPDATE `pacientes` p
LEFT JOIN `obras_sociales` os ON LOWER(TRIM(p.obra_social)) = LOWER(os.nombre)
SET p.id_obra_social = os.id
WHERE p.obra_social IS NOT NULL AND p.obra_social != '' AND os.id IS NOT NULL;

-- Verificar si ya existe la FK
SET @fk_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'pacientes'
    AND CONSTRAINT_NAME = 'fk_pacientes_obra_social'
);

-- Agregar FK constraint solo si no existe
SET @sql = IF(@fk_exists = 0,
    'ALTER TABLE `pacientes` ADD CONSTRAINT `fk_pacientes_obra_social` FOREIGN KEY (`id_obra_social`) REFERENCES `obras_sociales`(`id`) ON DELETE SET NULL ON UPDATE CASCADE',
    'SELECT "FK ya existe" as mensaje'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =====================================================
-- Verificación
-- =====================================================
SELECT 'Migración de obra_social completada' as resultado;
SELECT COUNT(*) as pacientes_con_obra_social FROM pacientes WHERE id_obra_social IS NOT NULL;

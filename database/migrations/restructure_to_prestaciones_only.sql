-- =====================================================
-- Migración: Eliminar tabla servicios y usar solo prestaciones
-- Descripción: Simplifica el modelo usando solo tipos_prestacion como prestaciones principales
-- =====================================================

-- Paso 1: Modificar prestaciones_pacientes para usar directamente tipos_prestacion
ALTER TABLE `prestaciones_pacientes`
  DROP FOREIGN KEY `fk_pp_prestacion`;

-- Paso 2: Renombrar columna id_prestacion a id_tipo_prestacion
ALTER TABLE `prestaciones_pacientes`
  CHANGE COLUMN `id_prestacion` `id_tipo_prestacion` int unsigned NOT NULL;

-- Paso 3: Agregar nueva clave foránea a tipos_prestacion
ALTER TABLE `prestaciones_pacientes`
  ADD CONSTRAINT `fk_pp_tipo_prestacion`
  FOREIGN KEY (`id_tipo_prestacion`)
  REFERENCES `tipos_prestacion`(`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

-- Paso 4: Eliminar tabla prestaciones (servicios específicos) si existe
DROP TABLE IF EXISTS `prestaciones`;

-- Paso 5: Agregar campos adicionales a tipos_prestacion si no existen
ALTER TABLE `tipos_prestacion`
  ADD COLUMN IF NOT EXISTS `codigo` varchar(50) DEFAULT NULL AFTER `nombre`,
  ADD COLUMN IF NOT EXISTS `valor_referencia` decimal(10,2) DEFAULT NULL AFTER `descripcion`;

-- Paso 6: Agregar índices para mejorar rendimiento
ALTER TABLE `tipos_prestacion`
  ADD INDEX IF NOT EXISTS `idx_codigo` (`codigo`),
  ADD INDEX IF NOT EXISTS `idx_estado` (`estado`);

-- Confirmación
SELECT 'Migración completada: Sistema ahora usa solo tipos_prestacion como prestaciones principales' AS mensaje;

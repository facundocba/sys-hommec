-- =====================================================
-- Migración: Corregir tipos de datos para compatibilidad de FK
-- Descripción: Asegurar que las columnas tengan tipos compatibles
-- =====================================================

-- Paso 1: Desactivar verificaciones de FK temporalmente
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

-- Paso 2: Eliminar FK existente de prestaciones
ALTER TABLE `prestaciones` DROP FOREIGN KEY `fk_prestaciones_tipo`;

-- Paso 3: Modificar id_tipo_prestacion en prestaciones a UNSIGNED
ALTER TABLE `prestaciones`
  MODIFY COLUMN `id_tipo_prestacion` int(11) UNSIGNED DEFAULT NULL;

-- Paso 4: Modificar tipos_prestacion.id a UNSIGNED
ALTER TABLE `tipos_prestacion`
  MODIFY COLUMN `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Paso 5: Restaurar FK en prestaciones
ALTER TABLE `prestaciones`
  ADD CONSTRAINT `fk_prestaciones_tipo`
  FOREIGN KEY (`id_tipo_prestacion`) REFERENCES `tipos_prestacion`(`id`)
  ON DELETE SET NULL ON UPDATE CASCADE;

-- Paso 6: Reactivar verificaciones de FK
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;

-- Paso 7: Ahora agregar la FK en prestaciones_pacientes
ALTER TABLE `prestaciones_pacientes`
  ADD CONSTRAINT `fk_pp_tipo_prestacion`
  FOREIGN KEY (`id_tipo_prestacion`)
  REFERENCES `tipos_prestacion`(`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

-- Confirmación
SELECT 'Migración completada: Foreign keys agregadas correctamente' AS mensaje;

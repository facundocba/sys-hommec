-- =====================================================
-- Migración: Corregir tipos de datos para compatibilidad de FK
-- Descripción: Asegurar que las columnas tengan tipos compatibles
-- =====================================================

-- Paso 1: Verificar y modificar tipos_prestacion.id a UNSIGNED
ALTER TABLE `tipos_prestacion`
  MODIFY COLUMN `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Paso 2: Ahora agregar la FK que falló anteriormente
ALTER TABLE `prestaciones_pacientes`
  ADD CONSTRAINT `fk_pp_tipo_prestacion`
  FOREIGN KEY (`id_tipo_prestacion`)
  REFERENCES `tipos_prestacion`(`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

-- Confirmación
SELECT 'Migración completada: Foreign key agregada correctamente' AS mensaje;

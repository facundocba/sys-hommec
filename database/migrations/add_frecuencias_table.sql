-- Migración: Tabla de frecuencias estandarizadas
-- Fecha: 2025-10-02

-- ====================================
-- Tabla: frecuencias
-- ====================================
CREATE TABLE IF NOT EXISTS `frecuencias` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL COMMENT 'Nombre descriptivo de la frecuencia',
  `sesiones_por_mes` INT(11) NOT NULL COMMENT 'Cantidad de sesiones por mes',
  `descripcion` VARCHAR(255) DEFAULT NULL COMMENT 'Descripción adicional',
  `orden` INT(11) NOT NULL DEFAULT 0 COMMENT 'Orden de visualización',
  `estado` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_estado` (`estado`),
  KEY `idx_orden` (`orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- Datos iniciales de frecuencias
-- ====================================
INSERT INTO `frecuencias` (`nombre`, `sesiones_por_mes`, `descripcion`, `orden`) VALUES
('1 vez por semana', 4, '1 sesión semanal - 4 sesiones al mes', 1),
('2 veces por semana', 8, '2 sesiones semanales - 8 sesiones al mes', 2),
('3 veces por semana', 12, '3 sesiones semanales - 12 sesiones al mes', 3),
('4 veces por semana', 16, '4 sesiones semanales - 16 sesiones al mes', 4),
('5 veces por semana', 20, '5 sesiones semanales - 20 sesiones al mes', 5),
('Diario (Lun-Vie)', 20, 'Todos los días hábiles - 20 sesiones al mes', 6),
('Quincenal', 2, 'Cada 15 días - 2 sesiones al mes', 7),
('Mensual', 1, 'Una vez al mes - 1 sesión al mes', 8),
('Personalizada', 0, 'Frecuencia personalizada - definir manualmente', 99);

-- ====================================
-- Modificar tabla pacientes
-- ====================================
-- Agregar columna para ID de frecuencia
ALTER TABLE `pacientes`
ADD COLUMN `id_frecuencia` INT(11) UNSIGNED DEFAULT NULL AFTER `frecuencia_servicio`,
ADD COLUMN `sesiones_personalizadas` INT(11) DEFAULT NULL COMMENT 'Sesiones/mes si frecuencia es personalizada' AFTER `id_frecuencia`,
ADD KEY `idx_frecuencia` (`id_frecuencia`),
ADD CONSTRAINT `fk_paciente_frecuencia` FOREIGN KEY (`id_frecuencia`) REFERENCES `frecuencias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- ====================================
-- Migrar datos existentes
-- ====================================
-- Script para intentar mapear frecuencias existentes a las nuevas
UPDATE `pacientes` SET `id_frecuencia` = 2 WHERE LOWER(`frecuencia_servicio`) REGEXP '2.*semana' OR LOWER(`frecuencia_servicio`) = '2x';
UPDATE `pacientes` SET `id_frecuencia` = 3 WHERE LOWER(`frecuencia_servicio`) REGEXP '3.*semana' OR LOWER(`frecuencia_servicio`) = '3x';
UPDATE `pacientes` SET `id_frecuencia` = 4 WHERE LOWER(`frecuencia_servicio`) REGEXP '4.*semana' OR LOWER(`frecuencia_servicio`) = '4x';
UPDATE `pacientes` SET `id_frecuencia` = 5 WHERE LOWER(`frecuencia_servicio`) REGEXP '5.*semana' OR LOWER(`frecuencia_servicio`) = '5x';
UPDATE `pacientes` SET `id_frecuencia` = 1 WHERE LOWER(`frecuencia_servicio`) REGEXP '1.*semana' OR LOWER(`frecuencia_servicio`) = '1x' OR LOWER(`frecuencia_servicio`) = 'semanal';
UPDATE `pacientes` SET `id_frecuencia` = 6 WHERE LOWER(`frecuencia_servicio`) REGEXP 'diario|lun.*vie|todos.*dias';
UPDATE `pacientes` SET `id_frecuencia` = 7 WHERE LOWER(`frecuencia_servicio`) REGEXP 'quincenal|cada.*15';
UPDATE `pacientes` SET `id_frecuencia` = 8 WHERE LOWER(`frecuencia_servicio`) REGEXP 'mensual|mes';

-- Los que no se pudieron mapear, marcar como personalizada
UPDATE `pacientes` SET `id_frecuencia` = 9, `sesiones_personalizadas` = 4 WHERE `id_frecuencia` IS NULL AND `frecuencia_servicio` IS NOT NULL AND `frecuencia_servicio` != '';

COMMIT;

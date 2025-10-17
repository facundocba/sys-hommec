-- Migración: Reestructurar sistema de prestaciones (TIPOS CORRECTOS)
-- Fecha: 2025-10-01

-- =====================================================
-- 1. Crear tabla prestaciones_pacientes
-- =====================================================
DROP TABLE IF EXISTS `prestaciones_pacientes`;

CREATE TABLE `prestaciones_pacientes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_paciente` int unsigned NOT NULL,
  `id_prestacion` int unsigned NOT NULL COMMENT 'Servicio del catálogo',
  `id_profesional` int unsigned NOT NULL COMMENT 'Profesional asignado',
  `id_empresa` int unsigned DEFAULT NULL COMMENT 'Empresa intermediaria (nullable si es independiente)',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL COMMENT 'NULL para servicios recurrentes sin fecha fin',
  `es_recurrente` tinyint(1) NOT NULL DEFAULT 0,
  `frecuencia_servicio` varchar(100) DEFAULT NULL COMMENT 'Ej: Lunes y Miércoles 14hs',
  `valor_profesional` decimal(10,2) DEFAULT NULL,
  `valor_empresa` decimal(10,2) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` enum('activo','finalizado','pausado') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_paciente` (`id_paciente`),
  KEY `idx_prestacion` (`id_prestacion`),
  KEY `idx_profesional` (`id_profesional`),
  KEY `idx_empresa` (`id_empresa`),
  KEY `idx_estado` (`estado`),
  KEY `idx_fecha_fin` (`fecha_fin`),
  CONSTRAINT `fk_pp_paciente` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pp_prestacion` FOREIGN KEY (`id_prestacion`) REFERENCES `prestaciones`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pp_profesional` FOREIGN KEY (`id_profesional`) REFERENCES `profesionales`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_pp_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresas`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2. Migrar datos existentes de pacientes a prestaciones_pacientes
-- =====================================================
INSERT INTO `prestaciones_pacientes`
  (`id_paciente`, `id_prestacion`, `id_profesional`, `id_empresa`, `fecha_inicio`, `fecha_fin`,
   `es_recurrente`, `frecuencia_servicio`, `valor_profesional`, `valor_empresa`, `observaciones`, `estado`)
SELECT
  p.id,
  p.id_prestacion,
  p.id_profesional,
  p.id_empresa,
  COALESCE(p.fecha_ingreso, CURDATE()),
  p.fecha_finalizacion,
  COALESCE(p.paciente_recurrente, 0),
  p.frecuencia_servicio,
  p.valor_profesional,
  p.valor_empresa,
  p.observaciones,
  p.estado
FROM `pacientes` p
WHERE p.id_prestacion IS NOT NULL
  AND p.id_profesional IS NOT NULL;

-- =====================================================
-- 3. Limpiar campos de pacientes (comentado por seguridad)
-- =====================================================
-- Descomenta estas líneas DESPUÉS de verificar que la migración funcionó correctamente

/*
-- Eliminar foreign keys
ALTER TABLE `pacientes` DROP FOREIGN KEY IF EXISTS `fk_patients_professional`;
ALTER TABLE `pacientes` DROP FOREIGN KEY IF EXISTS `fk_patients_company`;
ALTER TABLE `pacientes` DROP FOREIGN KEY IF EXISTS `fk_patients_service`;

-- Eliminar columnas antiguas
ALTER TABLE `pacientes` DROP COLUMN IF EXISTS `id_profesional`;
ALTER TABLE `pacientes` DROP COLUMN IF EXISTS `id_empresa`;
ALTER TABLE `pacientes` DROP COLUMN IF EXISTS `id_prestacion`;
ALTER TABLE `pacientes` DROP COLUMN IF EXISTS `fecha_ingreso`;
ALTER TABLE `pacientes` DROP COLUMN IF EXISTS `fecha_finalizacion`;
ALTER TABLE `pacientes` DROP COLUMN IF EXISTS `paciente_recurrente`;
ALTER TABLE `pacientes` DROP COLUMN IF EXISTS `frecuencia_servicio`;
ALTER TABLE `pacientes` DROP COLUMN IF EXISTS `valor_profesional`;
ALTER TABLE `pacientes` DROP COLUMN IF EXISTS `valor_empresa`;
ALTER TABLE `pacientes` DROP COLUMN IF EXISTS `observaciones`;
*/

-- =====================================================
-- Verificación
-- =====================================================
SELECT 'Tabla prestaciones_pacientes creada exitosamente' as resultado;
SELECT COUNT(*) as registros_migrados FROM prestaciones_pacientes;

SELECT 'IMPORTANTE: Verifica los datos antes de eliminar columnas de pacientes' as advertencia;

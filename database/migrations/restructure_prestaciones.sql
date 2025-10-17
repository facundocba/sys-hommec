-- Migración: Reestructurar sistema de prestaciones
-- Fecha: 2025-10-01
-- Descripción: Crear tabla de prestaciones activas por paciente

-- =====================================================
-- 1. Crear tabla prestaciones_pacientes
-- =====================================================
CREATE TABLE IF NOT EXISTS `prestaciones_pacientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_paciente` int(11) NOT NULL,
  `id_prestacion` int(11) NOT NULL COMMENT 'Servicio del catálogo',
  `id_profesional` int(11) NOT NULL COMMENT 'Profesional asignado',
  `id_empresa` int(11) DEFAULT NULL COMMENT 'Empresa intermediaria (nullable si es independiente)',
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
  p.fecha_ingreso,
  p.fecha_finalizacion,
  p.paciente_recurrente,
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

-- ALTER TABLE `pacientes` DROP FOREIGN KEY `fk_patients_professional`;
-- ALTER TABLE `pacientes` DROP FOREIGN KEY `fk_patients_company`;
-- ALTER TABLE `pacientes` DROP FOREIGN KEY `fk_patients_service`;

-- ALTER TABLE `pacientes` DROP COLUMN `id_profesional`;
-- ALTER TABLE `pacientes` DROP COLUMN `id_empresa`;
-- ALTER TABLE `pacientes` DROP COLUMN `id_prestacion`;
-- ALTER TABLE `pacientes` DROP COLUMN `fecha_ingreso`;
-- ALTER TABLE `pacientes` DROP COLUMN `fecha_finalizacion`;
-- ALTER TABLE `pacientes` DROP COLUMN `paciente_recurrente`;
-- ALTER TABLE `pacientes` DROP COLUMN `frecuencia_servicio`;
-- ALTER TABLE `pacientes` DROP COLUMN `valor_profesional`;
-- ALTER TABLE `pacientes` DROP COLUMN `valor_empresa`;
-- ALTER TABLE `pacientes` DROP COLUMN `observaciones`;

-- Mantener solo: nombre_completo, dni, localidad, id_obra_social, estado

-- =====================================================
-- Verificación
-- =====================================================
SELECT 'Tabla prestaciones_pacientes creada' as resultado;
SELECT COUNT(*) as registros_migrados FROM prestaciones_pacientes;

SELECT 'IMPORTANTE: Verifica los datos antes de eliminar columnas de pacientes' as advertencia;

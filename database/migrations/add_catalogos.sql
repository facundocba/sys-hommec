-- Migración: Agregar catálogos de Obras Sociales y Tipos de Prestación
-- Fecha: 2025-10-01

-- =====================================================
-- 1. Crear tabla de Obras Sociales
-- =====================================================
CREATE TABLE IF NOT EXISTS `obras_sociales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `sigla` varchar(50) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2. Crear tabla de Tipos de Prestación
-- =====================================================
CREATE TABLE IF NOT EXISTS `tipos_prestacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. Insertar datos iniciales - Obras Sociales
-- =====================================================
INSERT INTO `obras_sociales` (`nombre`, `sigla`, `estado`) VALUES
('OSDE', 'OSDE', 'activo'),
('Swiss Medical', 'SM', 'activo'),
('Galeno', 'GALENO', 'activo'),
('OSECAC', 'OSECAC', 'activo'),
('OSPEDYC', 'OSPEDYC', 'activo'),
('IOMA', 'IOMA', 'activo'),
('PAMI', 'PAMI', 'activo'),
('Medifé', 'MEDIFE', 'activo'),
('Accord Salud', 'ACCORD', 'activo'),
('Prevención Salud', 'PREVENCION', 'activo'),
('Particular', 'PART', 'activo')
ON DUPLICATE KEY UPDATE `sigla` = VALUES(`sigla`);

-- =====================================================
-- 4. Insertar datos iniciales - Tipos de Prestación
-- =====================================================
INSERT INTO `tipos_prestacion` (`nombre`, `descripcion`, `estado`) VALUES
('Kinesiología', 'Servicios de kinesiología y rehabilitación física', 'activo'),
('Enfermería', 'Servicios de enfermería y cuidados médicos', 'activo'),
('Cuidados Domiciliarios', 'Servicios de cuidado y acompañamiento domiciliario', 'activo'),
('Terapia Ocupacional', 'Servicios de terapia ocupacional y rehabilitación', 'activo'),
('Fonoaudiología', 'Servicios de fonoaudiología y rehabilitación del habla', 'activo'),
('Psicología', 'Servicios de atención psicológica', 'activo'),
('Nutrición', 'Servicios de nutrición y dietética', 'activo'),
('Podología', 'Servicios de podología y cuidado de pies', 'activo')
ON DUPLICATE KEY UPDATE `descripcion` = VALUES(`descripcion`);

-- =====================================================
-- 5. Modificar tabla pacientes - Migrar obra_social a FK
-- =====================================================

-- Agregar nueva columna id_obra_social
ALTER TABLE `pacientes`
ADD COLUMN `id_obra_social` int(11) DEFAULT NULL AFTER `localidad`;

-- Intentar migrar datos existentes (si obra_social coincide con nombre en obras_sociales)
UPDATE `pacientes` p
LEFT JOIN `obras_sociales` os ON LOWER(TRIM(p.obra_social)) = LOWER(os.nombre)
SET p.id_obra_social = os.id
WHERE p.obra_social IS NOT NULL AND p.obra_social != '' AND os.id IS NOT NULL;

-- Agregar FK constraint
ALTER TABLE `pacientes`
ADD CONSTRAINT `fk_pacientes_obra_social`
FOREIGN KEY (`id_obra_social`) REFERENCES `obras_sociales`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Opcional: Eliminar la columna antigua (comentado por seguridad)
-- ALTER TABLE `pacientes` DROP COLUMN `obra_social`;

-- =====================================================
-- 6. Modificar tabla prestaciones - Agregar tipo
-- =====================================================

-- Agregar nueva columna id_tipo_prestacion
ALTER TABLE `prestaciones`
ADD COLUMN `id_tipo_prestacion` int(11) DEFAULT NULL AFTER `codigo`;

-- Agregar FK constraint
ALTER TABLE `prestaciones`
ADD CONSTRAINT `fk_prestaciones_tipo`
FOREIGN KEY (`id_tipo_prestacion`) REFERENCES `tipos_prestacion`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- =====================================================
-- Fin de migración
-- =====================================================

-- Verificar las tablas creadas
SELECT 'Obras Sociales creadas:' as mensaje;
SELECT COUNT(*) as total FROM obras_sociales;

SELECT 'Tipos de Prestación creados:' as mensaje;
SELECT COUNT(*) as total FROM tipos_prestacion;

SELECT 'Migración completada exitosamente' as resultado;

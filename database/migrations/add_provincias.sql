-- Migración: Agregar soporte para provincias
-- Fecha: 2025-10-02
-- Descripción: Crea tabla de provincias argentinas y agrega campo provincia a pacientes

-- Crear tabla de provincias
CREATE TABLE IF NOT EXISTS `provincias` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `codigo` VARCHAR(10) NOT NULL COMMENT 'Código postal o identificador',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_provincia_nombre` (`nombre`),
  UNIQUE KEY `uk_provincia_codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar las 24 provincias argentinas
INSERT INTO `provincias` (`nombre`, `codigo`) VALUES
('Buenos Aires', 'BA'),
('Ciudad Autónoma de Buenos Aires', 'CABA'),
('Catamarca', 'CA'),
('Chaco', 'CC'),
('Chubut', 'CH'),
('Córdoba', 'CB'),
('Corrientes', 'CR'),
('Entre Ríos', 'ER'),
('Formosa', 'FO'),
('Jujuy', 'JY'),
('La Pampa', 'LP'),
('La Rioja', 'LR'),
('Mendoza', 'MZ'),
('Misiones', 'MI'),
('Neuquén', 'NQ'),
('Río Negro', 'RN'),
('Salta', 'SA'),
('San Juan', 'SJ'),
('San Luis', 'SL'),
('Santa Cruz', 'SC'),
('Santa Fe', 'SF'),
('Santiago del Estero', 'SE'),
('Tierra del Fuego', 'TF'),
('Tucumán', 'TU');

-- Agregar campo provincia a tabla pacientes
ALTER TABLE `pacientes`
ADD COLUMN `id_provincia` INT(11) UNSIGNED NULL AFTER `dni`,
ADD KEY `idx_provincia` (`id_provincia`),
ADD CONSTRAINT `fk_paciente_provincia` FOREIGN KEY (`id_provincia`) REFERENCES `provincias` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Comentario en el campo localidad para aclarar su nuevo propósito
ALTER TABLE `pacientes`
MODIFY COLUMN `localidad` VARCHAR(100) DEFAULT NULL COMMENT 'Ciudad o localidad dentro de la provincia';

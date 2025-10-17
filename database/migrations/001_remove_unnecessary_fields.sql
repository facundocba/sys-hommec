-- Migration: Remove unnecessary fields from empresas and profesionales
-- Date: 2025-10-01
-- Description: Remove redundant 'contacto' field from empresas and profesionales, and 'matricula' from profesionales

USE medflow;

-- Remove contacto from empresas
ALTER TABLE `empresas` DROP COLUMN IF EXISTS `contacto`;

-- Remove matricula and contacto from profesionales
ALTER TABLE `profesionales` DROP COLUMN IF EXISTS `matricula`;
ALTER TABLE `profesionales` DROP COLUMN IF EXISTS `contacto`;

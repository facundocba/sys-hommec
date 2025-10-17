-- MedFlow Database Schema
-- Created: 2025-09-30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: medflow
CREATE DATABASE IF NOT EXISTS `medflow` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `medflow`;

-- ====================================
-- Tabla: usuarios
-- ====================================
CREATE TABLE `usuarios` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `rol` ENUM('administrador', 'coordinador') NOT NULL DEFAULT 'coordinador',
  `estado` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ultimo_acceso` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`),
  KEY `idx_rol` (`rol`),
  KEY `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- Tabla: empresas
-- ====================================
CREATE TABLE `empresas` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `telefono` VARCHAR(50) DEFAULT NULL,
  `direccion` TEXT DEFAULT NULL,
  `estado` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`),
  KEY `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- Tabla: profesionales
-- ====================================
CREATE TABLE `profesionales` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `especialidad` VARCHAR(100) DEFAULT NULL,
  `telefono` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `estado` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- Tabla: prestaciones
-- ====================================
CREATE TABLE `prestaciones` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `codigo` VARCHAR(50) DEFAULT NULL,
  `estado` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_unique` (`codigo`),
  KEY `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- Tabla: pacientes
-- ====================================
CREATE TABLE `pacientes` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_completo` VARCHAR(150) NOT NULL,
  `dni` VARCHAR(20) DEFAULT NULL,
  `localidad` VARCHAR(100) DEFAULT NULL,
  `obra_social` VARCHAR(100) DEFAULT NULL,
  `frecuencia_servicio` VARCHAR(100) DEFAULT NULL,
  `id_profesional` INT(11) UNSIGNED DEFAULT NULL,
  `id_empresa` INT(11) UNSIGNED DEFAULT NULL,
  `id_prestacion` INT(11) UNSIGNED DEFAULT NULL,
  `fecha_ingreso` DATE NOT NULL,
  `fecha_finalizacion` DATE DEFAULT NULL,
  `paciente_recurrente` TINYINT(1) NOT NULL DEFAULT 0,
  `observaciones` TEXT DEFAULT NULL,
  `valor_profesional` DECIMAL(10,2) DEFAULT NULL,
  `valor_empresa` DECIMAL(10,2) DEFAULT NULL,
  `estado` ENUM('activo', 'finalizado', 'suspendido') NOT NULL DEFAULT 'activo',
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_profesional` (`id_profesional`),
  KEY `idx_empresa` (`id_empresa`),
  KEY `idx_prestacion` (`id_prestacion`),
  KEY `idx_estado` (`estado`),
  KEY `idx_fecha_finalizacion` (`fecha_finalizacion`),
  KEY `idx_paciente_recurrente` (`paciente_recurrente`),
  CONSTRAINT `fk_paciente_profesional` FOREIGN KEY (`id_profesional`) REFERENCES `profesionales` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_paciente_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_paciente_prestacion` FOREIGN KEY (`id_prestacion`) REFERENCES `prestaciones` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- Tabla: archivos_paciente
-- ====================================
CREATE TABLE `archivos_paciente` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_paciente` INT(11) UNSIGNED NOT NULL,
  `nombre_archivo` VARCHAR(255) NOT NULL,
  `nombre_original` VARCHAR(255) NOT NULL,
  `ruta` VARCHAR(500) NOT NULL,
  `tipo` VARCHAR(50) NOT NULL,
  `tamano` INT(11) NOT NULL COMMENT 'Tamaño en bytes',
  `fecha_subida` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_usuario_subio` INT(11) UNSIGNED NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `email_enviado` TINYINT(1) NOT NULL DEFAULT 0,
  `fecha_email_enviado` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_paciente` (`id_paciente`),
  KEY `idx_usuario` (`id_usuario_subio`),
  KEY `idx_fecha_subida` (`fecha_subida`),
  CONSTRAINT `fk_archivo_paciente` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_archivo_usuario` FOREIGN KEY (`id_usuario_subio`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- Tabla: notificaciones
-- ====================================
CREATE TABLE `notificaciones` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo` ENUM('archivo_subido', 'paciente_vencimiento', 'sistema', 'alerta') NOT NULL,
  `titulo` VARCHAR(200) NOT NULL,
  `mensaje` TEXT NOT NULL,
  `id_usuario_destino` INT(11) UNSIGNED DEFAULT NULL COMMENT 'NULL = todos los usuarios',
  `id_paciente` INT(11) UNSIGNED DEFAULT NULL,
  `id_archivo` INT(11) UNSIGNED DEFAULT NULL,
  `leida` TINYINT(1) NOT NULL DEFAULT 0,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_leida` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_usuario_destino` (`id_usuario_destino`),
  KEY `idx_paciente` (`id_paciente`),
  KEY `idx_leida` (`leida`),
  KEY `idx_tipo` (`tipo`),
  KEY `idx_fecha_creacion` (`fecha_creacion`),
  CONSTRAINT `fk_notif_usuario` FOREIGN KEY (`id_usuario_destino`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_notif_paciente` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_notif_archivo` FOREIGN KEY (`id_archivo`) REFERENCES `archivos_paciente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- Tabla: sesiones
-- ====================================
CREATE TABLE `sesiones` (
  `id` VARCHAR(128) NOT NULL,
  `id_usuario` INT(11) UNSIGNED NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_expiracion` TIMESTAMP NOT NULL,
  `ultima_actividad` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_usuario` (`id_usuario`),
  KEY `idx_expiracion` (`fecha_expiracion`),
  CONSTRAINT `fk_sesion_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- Tabla: logs_actividad
-- ====================================
CREATE TABLE `logs_actividad` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario` INT(11) UNSIGNED DEFAULT NULL,
  `accion` VARCHAR(100) NOT NULL,
  `modulo` VARCHAR(50) NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_usuario` (`id_usuario`),
  KEY `idx_modulo` (`modulo`),
  KEY `idx_fecha` (`fecha`),
  CONSTRAINT `fk_log_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- Tabla: configuracion
-- ====================================
CREATE TABLE `configuracion` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `clave` VARCHAR(100) NOT NULL,
  `valor` TEXT DEFAULT NULL,
  `descripcion` VARCHAR(255) DEFAULT NULL,
  `tipo` ENUM('string', 'number', 'boolean', 'json') NOT NULL DEFAULT 'string',
  `fecha_modificacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave_unique` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;

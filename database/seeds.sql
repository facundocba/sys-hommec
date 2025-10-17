-- MedFlow Initial Data
-- Usuario administrador por defecto
-- Password: admin123 (CAMBIAR EN PRODUCCIÓN)

USE `medflow`;

-- Insertar usuario administrador
INSERT INTO `usuarios` (`nombre`, `email`, `password_hash`, `rol`, `estado`) VALUES
('Administrador', 'admin@medflow.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrador', 'activo');

-- Insertar configuraciones iniciales
INSERT INTO `configuracion` (`clave`, `valor`, `descripcion`, `tipo`) VALUES
('dias_alerta_vencimiento', '7', 'Días antes del vencimiento para generar alerta', 'number'),
('email_notificaciones_activo', '1', 'Activar/desactivar envío de emails automáticos', 'boolean'),
('nombre_sistema', 'MedFlow', 'Nombre del sistema', 'string'),
('tamano_maximo_archivo', '20971520', 'Tamaño máximo de archivo en bytes (20MB)', 'number'),
('tipos_archivo_permitidos', '["pdf","doc","docx","jpg","jpeg","png","gif"]', 'Tipos de archivo permitidos', 'json');

-- Ejemplos de datos de prueba (comentados)
/*
-- Empresas de ejemplo
INSERT INTO `empresas` (`nombre`, `contacto`, `email`, `telefono`) VALUES
('Empresa Salud SA', 'Juan Pérez', 'contacto@empresasalud.com', '11-1234-5678'),
('Medicina Domiciliaria', 'María González', 'info@medicinadomiciliaria.com', '11-8765-4321');

-- Profesionales de ejemplo
INSERT INTO `profesionales` (`nombre`, `especialidad`, `matricula`, `contacto`, `telefono`, `email`) VALUES
('Dr. Carlos Martínez', 'Clínica Médica', 'MN12345', 'carlos.martinez@email.com', '11-2345-6789', 'carlos.martinez@email.com'),
('Lic. Ana Rodríguez', 'Kinesiología', 'MP67890', 'ana.rodriguez@email.com', '11-3456-7890', 'ana.rodriguez@email.com');

-- Prestaciones de ejemplo
INSERT INTO `prestaciones` (`nombre`, `descripcion`, `codigo`) VALUES
('Atención Domiciliaria', 'Visita médica a domicilio', 'ATD-001'),
('Kinesiología Motora', 'Sesión de kinesiología motora', 'KIN-001'),
('Enfermería Domiciliaria', 'Cuidado de enfermería en domicilio', 'ENF-001');
*/

# Scripts de MedFlow

Este directorio contiene scripts automáticos para tareas programadas del sistema.

## check_expiring_services.php

Script que verifica prestaciones próximas a vencer y envía notificaciones internas y emails a los usuarios.

### Configuración en Linux/Unix (CRON)

Para ejecutar el script diariamente a las 8:00 AM, agregar la siguiente línea al crontab:

```bash
# Editar crontab
crontab -e

# Agregar esta línea:
0 8 * * * /usr/bin/php /ruta/completa/a/MedFlow/scripts/check_expiring_services.php >> /ruta/completa/a/MedFlow/logs/cron.log 2>&1
```

### Configuración en Windows (Programador de Tareas)

#### Opción 1: Usando el archivo .bat incluido

1. Editar el archivo `check_expiring_services.bat` y ajustar las rutas según tu instalación
2. Abrir el "Programador de Tareas" de Windows
3. Crear nueva tarea básica:
   - Nombre: "MedFlow - Verificar Vencimientos"
   - Desencadenador: Diariamente a las 8:00 AM
   - Acción: Iniciar programa
   - Programa: Seleccionar el archivo `check_expiring_services.bat`
4. Guardar la tarea

#### Opción 2: Directamente con PHP

1. Abrir el "Programador de Tareas" de Windows
2. Crear nueva tarea básica:
   - Nombre: "MedFlow - Verificar Vencimientos"
   - Desencadenador: Diariamente a las 8:00 AM
   - Acción: Iniciar programa
   - Programa: `C:\wamp64\bin\php\php8.1.31\php.exe`
   - Argumentos: `C:\wamp64\www\MedFlow\scripts\check_expiring_services.php`
3. Guardar la tarea

### Ejecución Manual

Para probar el script manualmente:

**Linux/Unix:**
```bash
php /ruta/a/MedFlow/scripts/check_expiring_services.php
```

**Windows:**
```bash
C:\wamp64\bin\php\php8.1.31\php.exe C:\wamp64\www\MedFlow\scripts\check_expiring_services.php
```

### Configuración

El número de días de anticipación para las alertas se configura en el archivo `.env`:

```
PATIENT_EXPIRATION_ALERT_DAYS=7
```

Por defecto, el sistema alertará sobre prestaciones que vencen en 7 días o menos.

### Logs

Los resultados de la ejecución se pueden redirigir a un archivo de log agregando al final del comando:

```bash
>> /ruta/a/MedFlow/logs/cron.log 2>&1
```

### Notas Importantes

- El script evita enviar notificaciones duplicadas el mismo día
- Solo se envían notificaciones para prestaciones activas
- Los emails solo se envían si `ENABLE_EMAIL_NOTIFICATIONS=true` en el archivo `.env`
- Asegurarse de que las credenciales SMTP estén correctamente configuradas en `.env`

@echo off
REM Script para ejecutar verificación de vencimientos en Windows
REM Ajustar las rutas según la instalación de WAMP

REM Configurar ruta de PHP
SET PHP_PATH=C:\wamp64\bin\php\php8.1.31\php.exe

REM Configurar ruta del script
SET SCRIPT_PATH=C:\wamp64\www\MedFlow\scripts\check_expiring_services.php

REM Configurar ruta del log (opcional)
SET LOG_PATH=C:\wamp64\www\MedFlow\logs\cron.log

echo ========================================
echo MedFlow - Verificacion de Vencimientos
echo ========================================
echo Ejecutando script...
echo.

REM Ejecutar el script y guardar log
"%PHP_PATH%" "%SCRIPT_PATH%" >> "%LOG_PATH%" 2>&1

echo.
echo Script ejecutado. Revisar log en: %LOG_PATH%
echo ========================================
pause

<?php
/**
 * MedFlow - Sistema Administrativo Médico
 * Entry Point
 */

// Error reporting - desactivado en producción para mejor rendimiento
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// Set timezone
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Start session
session_start();

// Autoload configuration and helpers
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/helpers/functions.php';

// Simple router
$url = $_GET['url'] ?? 'login';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Route mapping for URLs with hyphens
$routeMap = [
    'prestaciones-pacientes' => 'PrestacionesPacientesController',
    'prestaciones-empresas' => 'PrestacionesEmpresasController',
    'obras-sociales' => 'ObrasSocialesController',
    'tipos-prestacion' => 'PrestacionesController',  // Legacy route - redirect to prestaciones
    'prestaciones' => 'PrestacionesController',
    'files' => 'FilesController',
    'notifications' => 'NotificationsController'
];

// Method mapping for URLs with hyphens
$methodMap = [
    'permanent-delete' => 'permanentDelete'
];

// Route handling
$route = $url[0] ?? 'login';
if (isset($routeMap[$route])) {
    $controllerName = $routeMap[$route];
} else {
    $controllerName = ucfirst($route) . 'Controller';
}
$controllerFile = __DIR__ . '/../src/controllers/' . $controllerName . '.php';

// Default to login if controller not found
if (!file_exists($controllerFile)) {
    $controllerName = 'LoginController';
    $controllerFile = __DIR__ . '/../src/controllers/LoginController.php';
}

require_once $controllerFile;

$controller = new $controllerName();
$method = $url[1] ?? 'index';

// Map method if it has hyphens
if (isset($methodMap[$method])) {
    $method = $methodMap[$method];
}

// Check if method exists
if (!method_exists($controller, $method)) {
    $method = 'index';
}

// Get additional parameters
$params = array_slice($url, 2);

// Call controller method
call_user_func_array([$controller, $method], $params);

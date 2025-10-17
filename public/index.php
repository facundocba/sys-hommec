<?php
/**
 * MedFlow - Sistema Administrativo Médico
 * Entry Point
 */

// Error reporting - always on for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    'obras-sociales' => 'ObrasSocialesController',
    'tipos-prestacion' => 'PrestacionesController',  // Legacy route - redirect to prestaciones
    'prestaciones' => 'PrestacionesController',
    'files' => 'FilesController',
    'notifications' => 'NotificationsController'
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

// Check if method exists
if (!method_exists($controller, $method)) {
    $method = 'index';
}

// Get additional parameters
$params = array_slice($url, 2);

// Call controller method
call_user_func_array([$controller, $method], $params);

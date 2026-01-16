<?php
// Front controller del proyecto.
// Se encarga de iniciar sesión/CSRF y despachar la petición
// al controlador y acción correspondientes.

require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/VehiculoController.php';

// Controlador y acción por defecto
$controllerName = $_GET['controller'] ?? 'auth';
$actionName     = $_GET['action'] ?? 'login';

switch ($controllerName) {
    case 'auth':
        $controller = new AuthController();
        if (!method_exists($controller, $actionName)) {
            $actionName = 'login';
        }
        $controller->$actionName();
        break;

    case 'vehiculo':
        // Proteger todas las acciones del CRUD de vehículos
        if (!isset($_SESSION['nombre'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        $controller = new VehiculoController();
        if (!method_exists($controller, $actionName)) {
            $actionName = 'index';
        }
        $controller->$actionName();
        break;

    default:
        // Cualquier otro controlador → login
        $controller = new AuthController();
        $controller->login();
        break;
}

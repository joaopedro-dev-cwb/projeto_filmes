<?php
// Initial configuration
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable display_errors in production
session_start();

// Error handler function
function handleError($errno, $errstr, $errfile, $errline) {
    $errorMessage = "Error: [$errno] $errstr - $errfile:$errline";
    error_log($errorMessage); // Log the error

    // Only show error page for serious errors
    if (in_array($errno, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
        http_response_code(500);
        require __DIR__ . '/../App/views/errors/500.php';
        exit(1);
    }

    return false; // Let PHP handle other errors
}

// Exception handler function
function handleException($e) {
    error_log($e->getMessage()); // Log the exception
    http_response_code(500);
    $errorMessage = $e->getMessage();
    require __DIR__ . '/../App/views/errors/500.php';
    exit(1);
}

// Set error and exception handlers
set_error_handler('handleError');
set_exception_handler('handleException');

// Simple autoloader to avoid multiple requires
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../App/controllers/',
        __DIR__ . '/../App/models/',
        __DIR__ . '/../App/config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

// Conexão com o banco de dados
require_once __DIR__ . '/../app/config/database.php';

// Rotas principais
$action = $_GET['action'] ?? 'home';
$id = $_GET['id'] ?? null;

try {
    switch ($action) {
        case 'home':
            $controller = new FilmController();
            $controller->index();
            break;
            
        case 'login':
            $controller = new AuthController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->handleLogin();
            } else {
                $controller->showLogin();
            }
            break;
            
        case 'register':
            $controller = new AuthController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->handleRegister();
            } else {
                $controller->showRegister();
            }
            break;
              case 'logout':
            $controller = new AuthController();
            $controller->logout();
            break;
            
        case 'reset-password':
            $controller = new AuthController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->handleResetPassword();
            } else {
                $controller->showResetPassword();
            }
            break;case 'films':
            $controller = new FilmController();
            if ($id) {
                $controller->show($id);
            } else {
                $controller->index();
            }
            break;
            
        case 'films/create':
            $controller = new FilmController();
            $controller->create();
            break;
            
        case 'films/edit':
            if (!$id) {
                $errorMessage = "ID is required for edit";
                http_response_code(400);
                require __DIR__ . '/../App/views/errors/500.php';
                exit;
            }
            $controller = new FilmController();
            $controller->edit($id);
            break;
            
        case 'films/delete':
            if (!$id) {
                $errorMessage = "ID is required for delete";
                http_response_code(400);
                require __DIR__ . '/../App/views/errors/500.php';
                exit;
            }
            $controller = new FilmController();
            $controller->delete($id);
            break;
            
        case 'films/genre':
            $genre = $_GET['genre'] ?? null;
            if (!$genre) {
                $errorMessage = "Genre is required";
                http_response_code(400);
                require __DIR__ . '/../App/views/errors/500.php';
                exit;
            }
            $controller = new FilmController();
            $controller->byGenre($genre);
            break;
            
        // Add more routes as needed
            
        default:
            http_response_code(404);
            require __DIR__ . '/../App/views/errors/404.php';
            break;
    }
} catch (Exception $e) {
    // Tratamento de erros
    error_log($e->getMessage());
    http_response_code(500);
    require_once __DIR__ . '/../app/views/errors/500.php';
}
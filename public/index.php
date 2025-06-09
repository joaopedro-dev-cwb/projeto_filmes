<?php
// Configurações iniciais
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Autoloader simples para não precisar de muitos requires
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../app/controllers/',
        __DIR__ . '/../app/models/',
        __DIR__ . '/../app/config/'
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
            
        case 'films':
            $controller = new FilmController();
            if ($id) {
                $controller->show($id);
            } else {
                $controller->index();
            }
            break;
            
        // Adicione mais rotas conforme necessário
            
        default:
            http_response_code(404);
            require_once __DIR__ . '/../app/views/errors/404.php';
            break;
    }
} catch (Exception $e) {
    // Tratamento de erros
    error_log($e->getMessage());
    http_response_code(500);
    require_once __DIR__ . '/../app/views/errors/500.php';
}
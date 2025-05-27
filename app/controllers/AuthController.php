<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User();
            $loggedUser = $user->login($_POST['email'], $_POST['password']);
            
            if ($loggedUser) {
                session_start();
                $_SESSION['user_id'] = $loggedUser['id'];
                header('Location: /');
            } else {
                $error = "Credenciais inválidas";
                require_once '../views/auth/login.php';
            }
        } else {
            require_once '../views/auth/login.php';
        }
    }

    public function showLogin() {
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    public function handleLogin() {
        // Lógica de autenticação
        $user = new User();
        $loggedUser = $user->login($_POST['email'], $_POST['password']);
        if ($loggedUser) {
            session_start();
            $_SESSION['user_id'] = $loggedUser['id'];
            header('Location: /?action=home');
            exit;
        } else {
            $error = "Credenciais inválidas";
            require_once __DIR__ . '/../views/auth/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User();
            if ($user->register($_POST['email'], $_POST['password'])) {
                header('Location: /login');
            }
        } else {
            require_once '../views/auth/register.php';
        }
    }

    public function handleRegister()
    {
        // Implement registration logic here
        // Example:
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            // Validate and save user, then redirect or show errors
        }
    }

    public function showRegister() {
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /');
    }
}
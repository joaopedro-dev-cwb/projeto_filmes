<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $viewsPath;

    public function __construct() {
        $this->viewsPath = __DIR__ . '/../views/';
    }

    private function startSession($user) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $error = "Email and password are required";
                require $this->viewsPath . 'auth/login.php';
                return;
            }

            $user = new User();
            $loggedUser = $user->login($_POST['email'], $_POST['password']);
            
            if ($loggedUser) {
                $this->startSession($loggedUser);
                header('Location: /projeto_filmes/public/?action=home');
                exit;
            } else {
                $error = "Invalid credentials";
                require $this->viewsPath . 'auth/login.php';
            }
        } else {
            require $this->viewsPath . 'auth/login.php';
        }
    }

    public function showLogin() {
        $viewFile = $this->viewsPath . 'auth/login.php';
        require $viewFile;
    }
    
    public function handleLogin() {
        $user = new User();
        $loggedUser = $user->login($_POST['email'], $_POST['password']);
        if ($loggedUser) {
            $_SESSION['user_id'] = $loggedUser['id'];
            header('Location: /projeto_filmes/public/?action=home');
            exit;
        } else {
            $error = "Invalid credentials";
            $viewFile = $this->viewsPath . 'auth/login.php';
            require $viewFile;
        }
    }    public function register() {
        $viewFile = $this->viewsPath . 'auth/register.php';
        require $viewFile;
    }

    public function handleRegister() {
        $requiredFields = ['email', 'password', 'cpf', 'birth_date', 'name'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $error = "All fields are required";
                $viewFile = $this->viewsPath . 'auth/register.php';
                require $viewFile;
                return;
            }
        }

        if ($_POST['password'] !== $_POST['confirm_password']) {
            $error = "Passwords do not match";
            $viewFile = $this->viewsPath . 'auth/register.php';
            require $viewFile;
            return;
        }

        $user = new User();
        try {
            if ($user->register($_POST)) {
                header('Location: /projeto_filmes/public/?action=login');
                exit;
            } else {
                $error = "Registration failed";
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        $viewFile = $this->viewsPath . 'auth/register.php';
        require $viewFile;
    }

    public function showRegister() {
        $viewFile = $this->viewsPath . 'auth/register.php';
        require $viewFile;
    }

    public function logout() {
        session_destroy();
        header('Location: /projeto_filmes/public/?action=home');
        exit;
    }    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['cpf']) || empty($_POST['birth_date']) || empty($_POST['new_password'])) {
                $error = "All fields are required";
                $viewFile = $this->viewsPath . 'auth/reset_password.php';
                require $viewFile;
                return;
            }

            try {
                $user = new User();
                $success = $user->resetPassword(
                    $_POST['cpf'],
                    $_POST['birth_date'],
                    $_POST['new_password']
                );

                if ($success) {
                    $_SESSION['success_message'] = "Password updated successfully";
                    header('Location: /projeto_filmes/public/?action=login');
                    exit;
                } else {
                    $error = "Failed to reset password. Please verify your information.";
                    $viewFile = $this->viewsPath . 'auth/reset_password.php';
                    require $viewFile;
                }
            } catch (Exception $e) {
                $error = "An error occurred. Please try again.";
                $viewFile = $this->viewsPath . 'auth/reset_password.php';
                require $viewFile;
            }
        } else {
            $viewFile = $this->viewsPath . 'auth/reset_password.php';
            require $viewFile;
        }
    }

    public function showResetPassword() {
        $viewFile = $this->viewsPath . 'auth/reset_password.php';
        require $viewFile;
    }

    public function handleResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['cpf']) || empty($_POST['birth_date']) || empty($_POST['new_password'])) {
                $error = "All fields are required";
                $viewFile = $this->viewsPath . 'auth/reset_password.php';
                require $viewFile;
                return;
            }

            try {
                $user = new User();
                $result = $user->resetPassword(
                    $_POST['cpf'],
                    $_POST['birth_date'],
                    $_POST['new_password']
                );

                if ($result) {
                    $_SESSION['success_message'] = "Password updated successfully";
                    header('Location: /projeto_filmes/public/?action=login');
                    exit;
                } else {
                    $error = "Failed to reset password. Please verify your information.";
                    $viewFile = $this->viewsPath . 'auth/reset_password.php';
                    require $viewFile;
                }
            } catch (Exception $e) {
                $error = "An error occurred. Please try again.";
                $viewFile = $this->viewsPath . 'auth/reset_password.php';
                require $viewFile;
            }
        } else {
            $this->showResetPassword();
        }
    }
}
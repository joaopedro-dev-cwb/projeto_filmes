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

    public function recuperarSenha() {
        $conn = new mysqli("localhost", "root", "", "filmes_php");

        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        $cpf_teste = $_POST['cpf_teste'];
        $dtaNasc_teste = $_POST['dtaNasc_teste'];
        $nova_senha = $_POST['nova_senha'];

        $resultado = $this->alterarSenha($cpf_teste, $dtaNasc_teste, $nova_senha, $conn);

        echo $resultado['mensagem'];

        $conn->close();
    }

    private function alterarSenha($cpf_teste, $dtaNasc_teste, $nova_senha, $conn) {
        $novaSenhaHash = password_hash($nova_senha, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM users WHERE cpf = ? AND data_nascimento = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $cpf_teste, $dtaNasc_teste);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $sqlUpdate = "UPDATE usuarios SET password = ? WHERE cpf = ? AND data_nascimento = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("sss", $novaSenhaHash, $cpf_teste, $dtaNasc_teste);

            if ($stmtUpdate->execute()) {
                $stmtUpdate->close();
                return [
                    'status' => true,
                    'mensagem' => 'Senha atualizada com sucesso.'
                ];
            } else {
                $stmtUpdate->close();
                return [
                    'status' => false,
                    'mensagem' => 'Erro ao atualizar a senha.'
                ];
            }
        } else {
            return [
                'status' => false,
                'mensagem' => 'CPF e Data de nascimento não encontrados.'
            ];
        }

        $stmt->close();
    }
}
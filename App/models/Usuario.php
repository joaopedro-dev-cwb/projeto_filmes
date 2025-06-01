<?php
include_once(__DIR__ . '/../../banco.php');

class Usuario {
    public static function listar() {
        global $conn;
        return mysqli_query($conn, "SELECT * FROM users");
    }
    public static function buscarPorId($id) {
        global $conn;
        return mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $id"));
    }
    public static function adicionar($nome, $email, $password, $cpf, $data_nascimento) {
        global $conn;
        return mysqli_query($conn, "INSERT INTO users (nome, email, password, cpf, data_nascimento) VALUES ('$nome', '$email', '$password', '$cpf', '$data_nascimento')");
    }
    public static function atualizar($id, $nome, $email, $password, $cpf, $data_nascimento) {
        global $conn;
        return mysqli_query($conn, "UPDATE users SET nome='$nome', email='$email', password='$password', cpf='$cpf', data_nascimento='$data_nascimento' WHERE id=$id");
    }
    public static function excluir($id) {
        global $conn;
        return mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    }
}
?>
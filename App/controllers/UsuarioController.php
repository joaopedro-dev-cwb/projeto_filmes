<?php
include_once(__DIR__ . '/../models/Usuario.php');

class UsuarioController {
    public function listar() {
        $result = Usuario::listar();
        // include '../views/usuarios/listar.php';
    }
    public function adicionar() {
        if ($_POST) {
            Usuario::adicionar($_POST['nome'], $_POST['email'], $_POST['password'], $_POST['cpf'], $_POST['data_nascimento']);
            header('Location: index.php?controller=usuario&action=listar');
        }
        // include '../views/usuarios/adicionar.php';
    }
    public function editar() {
        $id = $_GET['id'];
        $usuario = Usuario::buscarPorId($id);
        if ($_POST) {
            Usuario::atualizar($id, $_POST['nome'], $_POST['email'], $_POST['password'], $_POST['cpf'], $_POST['data_nascimento']);
            header('Location: index.php?controller=usuario&action=listar');
        }
        // include '../views/usuarios/editar.php';
    }
    public function excluir() {
        $id = $_GET['id'];
        Usuario::excluir($id);
        // header('Location: index.php?controller=usuario&action=listar');
    }
}
?>
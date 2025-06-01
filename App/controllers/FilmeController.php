<?php
include_once(__DIR__ . '/../models/Filme.php');

class FilmeController {
    
    public function listar() {
        $result = Filme::listar();
        include __DIR__ . '/../views/filmes/listar.php';
    }

    public function adicionar() {
        if ($_POST) {
            Filme::adicionar($_POST['titulo'], $_POST['diretor'], $_POST['genero'], $_POST['descricao']);
            header('Location: index.php?controller=filme&action=listar');
        }
        // include '../views/filmes/adicionar.php';
    }

    public function editar() {
        $id = $_GET['id'];
        $filme = Filme::buscarPorId($id);
        if ($_POST) {
            Filme::atualizar($id, $_POST['titulo'], $_POST['diretor'], $_POST['genero'], $_POST['descricao']);
            header('Location: index.php?controller=filme&action=listar');
        }
        // include '../views/filmes/editar.php';
    }

    public function excluir() {
        $id = $_GET['id'];
        Filme::excluir($id);
        header('Location: index.php?controller=filme&action=listar');
    }
}
?>
<?php
include_once(__DIR__ . '/../models/Review.php');

class ReviewController {

    public function listar() {
        $result = Review::listar();
        include __DIR__ . '/../views/reviews/listar.php';
    }

    public function adicionar() {
        if ($_POST) {
            Review::adicionar($_POST['filme_id'], $_POST['user_id'], $_POST['nota'], $_POST['comentario']);
            header('Location: index.php?controller=review&action=listar');
        }
        include '../views/reviews/adicionar.php';
    }

    public function editar() {
        $id = $_GET['id'];
        $review = Review::buscarPorId($id);
        if ($_POST) {
            Review::atualizar($id, $_POST['nota'], $_POST['comentario']);
            header('Location: index.php?controller=review&action=listar');
        }
        include '../views/reviews/editar.php';
    }

    public function excluir() {
        $id = $_GET['id'];
        Review::excluir($id);
        header('Location: index.php?controller=review&action=listar');
    }
}
?>
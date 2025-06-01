<?php
include_once(__DIR__ . '/../../banco.php');

class Review {
    public static function listar() {
        global $conn;
        return mysqli_query($conn, "SELECT * FROM reviews");
    }

    public static function buscarPorId($id) {
        global $conn;
        return mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM reviews WHERE id = $id"));
    }

    public static function adicionar($filme_id, $user_id, $nota, $comentario) {
        global $conn;
        return mysqli_query($conn, "INSERT INTO reviews (filme_id, user_id, nota, comentario) VALUES ('$filme_id', '$user_id', '$nota', '$comentario')");
    }

    public static function atualizar($id, $nota, $comentario) {
        global $conn;
        return mysqli_query($conn, "UPDATE reviews SET nota='$nota', comentario='$comentario' WHERE id=$id");
    }

    public static function excluir($id) {
        global $conn;
        return mysqli_query($conn, "DELETE FROM reviews WHERE id = $id");
    }
}
?>
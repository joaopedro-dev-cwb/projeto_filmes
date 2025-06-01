<?php
include_once(__DIR__ . '/../../banco.php');

class Filme
{
    public static function listar()
    {
        global $conn;
        return mysqli_query($conn, "SELECT * FROM films");
    }

    public static function buscarPorId($id)
    {
        global $conn;
        $sql = "SELECT * FROM films WHERE id = $id";
        return mysqli_fetch_assoc(mysqli_query($conn, $sql));
    }

    public static function adicionar($titulo, $diretor, $genero, $descricao)
    {
        global $conn;
        $sql = "INSERT INTO films (titulo, diretor, genero, descricao) VALUES ('$titulo', '$diretor', '$genero', '$descricao')";
        return mysqli_query($conn, $sql);
    }

    public static function atualizar($id, $titulo, $diretor, $genero, $descricao)
    {
        global $conn;
        $sql = "UPDATE films SET titulo='$titulo', diretor='$diretor', genero='$genero', descricao='$descricao' WHERE id=$id";
        return mysqli_query($conn, $sql);
    }

    public static function excluir($id)
    {
        global $conn;
        $sql = "DELETE FROM films WHERE id = $id";
        return mysqli_query($conn, $sql);
    }
}
?>
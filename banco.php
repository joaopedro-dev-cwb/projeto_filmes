<?php
    $server = "localhost";
    $user = "root";
    $password = "";
    $database = "filmes_php";

    $conn = mysqli_connect($server, $user, $password, $database);

    if (!$conn) {
        die("Falha na conexão: " . mysqli_connect_error());
    }
?>
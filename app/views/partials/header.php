<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Filmes</title>
    <link rel="stylesheet" href="/projeto_filmes/public/assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <!-- Link para Home -->
            <a href="?action=home">Home</a>
            
            <!-- Link para Login -->
            <a href="?action=login">Login</a>
            
            <!-- Link para Registro -->
            <a href="?action=register">Registrar</a>
        
            
            <!-- Link para Logout (só mostra se logado) -->
            <?php if(isset($_SESSION['user'])): ?>
                <a href="?action=logout">Sair</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
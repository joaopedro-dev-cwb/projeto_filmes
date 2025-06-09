<?php
$config = require_once __DIR__ . '/../../config/config.dev.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movies Catalog</title>
    <link rel="stylesheet" href="<?= $config['asset_url'] ?>/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header>
        <nav>
            <a href="?action=home">Home</a>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="?action=login">Login</a>
                <a href="?action=register">Register</a>
            <?php else: ?>
                <a href="?action=films/create">Add Movie</a>
                <a href="?action=logout">Logout</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="container"><?php
    if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']);
    endif; ?>
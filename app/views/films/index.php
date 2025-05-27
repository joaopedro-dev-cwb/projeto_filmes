<?php require_once __DIR__ . '/../partials/header.php'; ?>
<h1>Catálogo de Filmes</h1>

<?php if(isset($_SESSION['user_id'])): ?>
    <a href="/films/create">Adicionar Filme</a>
<?php endif; ?>

<div class="films">
    <?php foreach ($films as $film): ?>
        <div class="film">
            <h2><?= htmlspecialchars($film['title']) ?></h2>
            <p>Diretor: <?= htmlspecialchars($film['director']) ?></p>
            <a href="/films?id=<?= $film['id'] ?>">Ver detalhes</a>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
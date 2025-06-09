<?php require_once __DIR__ . '/../partials/header.php'; ?>
<h1>Movie Catalog</h1>

<?php if(isset($_SESSION['user_id'])): ?>    <a href="?action=films/create" class="btn btn-primary">Add Movie</a>
<?php endif; ?>

<div class="films">
    <?php foreach ($films as $film): ?>
        <div class="film-card">
            <?php if ($film['cover_image']): ?>
                <img src="/projeto_filmes/public/<?= htmlspecialchars($film['cover_image']) ?>" 
                     alt="<?= htmlspecialchars($film['title']) ?>" class="film-cover">
            <?php endif; ?>
            <div class="film-info">
                <h2><?= htmlspecialchars($film['title']) ?></h2>
                <p>Director: <?= htmlspecialchars($film['director']) ?></p>
                <p>Year: <?= htmlspecialchars($film['release_year']) ?></p>
                <?php if (!empty($film['genre_names'])): ?>
                    <p class="genres"><?= htmlspecialchars($film['genre_names']) ?></p>
                <?php endif; ?>
                <a href="?action=films&id=<?= $film['id'] ?>" class="btn btn-secondary">View details</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="hero">
    <h1>Welcome to Movies Catalog</h1>
    <p>Discover, rate and comment on your favorite movies</p>
</div>

<div class="featured-films">
    <h2>Featured Movies</h2>
    
    <div class="films-grid">
        <?php foreach ($featuredFilms as $film): ?>            <div class="film-card">
                <a href="?action=films&id=<?= $film['id'] ?>">
                    <div class="film-poster">
                        <?php if ($film['cover_image']): ?>
                            <img src="/uploads/<?= htmlspecialchars($film['cover_image']) ?>" alt="<?= htmlspecialchars($film['title']) ?>">
                        <?php else: ?>
                            <div class="no-image">No image</div>
                        <?php endif; ?>
                    </div>
                    <h3><?= htmlspecialchars($film['title']) ?></h3>
                    <p class="film-year"><?= htmlspecialchars($film['release_year']) ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="film-categories">
    <h2>Explore by Genre</h2>
    
    <div class="categories-list">
        <?php foreach ($genres as $genre): ?>
            <a href="/films/genre/<?= $genre['id'] ?>" class="genre-tag">
                <?= htmlspecialchars($genre['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="hero">
    <h1>Bem-vindo ao Catálogo de Filmes</h1>
    <p>Descubra, avalie e comente sobre seus filmes favoritos</p>
</div>

<div class="featured-films">
    <h2>Filmes em Destaque</h2>
    
    <div class="films-grid">
        <?php foreach ($featuredFilms as $film): ?>
            <div class="film-card">
                <a href="/films/<?= $film['id'] ?>">
                    <div class="film-poster">
                        <?php if ($film['cover_image']): ?>
                            <img src="/uploads/<?= htmlspecialchars($film['cover_image']) ?>" alt="<?= htmlspecialchars($film['title']) ?>">
                        <?php else: ?>
                            <div class="no-image">Sem imagem</div>
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
    <h2>Explore por Gênero</h2>
    
    <div class="categories-list">
        <?php foreach ($genres as $genre): ?>
            <a href="/films/genre/<?= $genre['id'] ?>" class="genre-tag">
                <?= htmlspecialchars($genre['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
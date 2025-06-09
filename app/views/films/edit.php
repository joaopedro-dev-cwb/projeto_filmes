<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="form-container">
    <h1>Edit Movie</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <form action="?action=films/edit&id=<?= $film['id'] ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" 
                value="<?= htmlspecialchars($film['title']) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4"><?= htmlspecialchars($film['description']) ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="release_year">Release Year:</label>
            <input type="number" id="release_year" name="release_year" 
                value="<?= htmlspecialchars($film['release_year']) ?>" 
                required min="1888" max="<?= date('Y') ?>">
        </div>
        
        <div class="form-group">
            <label for="duration">Duration (minutes):</label>
            <input type="number" id="duration" name="duration" 
                value="<?= htmlspecialchars($film['duration']) ?>" 
                required min="1" max="999">
        </div>

        <div class="form-group">
            <label for="cover_image">Cover Image:</label>
            <?php if (!empty($film['cover_image'])): ?>
                <div class="current-image">
                    <img src="/projeto_filmes/public/<?= htmlspecialchars($film['cover_image']) ?>" 
                         alt="Current cover" style="max-width: 200px;">
                    <p>Current cover image</p>
                </div>
            <?php endif; ?>
            <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp">
            <small>Maximum size: 5MB. Allowed types: JPG, PNG, WebP</small>
        </div>
        
        <div class="form-group">
            <label>Genres:</label>
            <div class="checkbox-group">
                <?php 
                $currentGenres = explode(',', $film['genre_names'] ?? '');
                foreach ($genres as $genre): 
                ?>
                    <label class="checkbox-label">
                        <input type="checkbox" name="genres[]" value="<?= $genre['id'] ?>"
                            <?= in_array($genre['name'], $currentGenres) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($genre['name']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="?action=films&id=<?= $filmData['id'] ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

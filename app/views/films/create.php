<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="form-container">
    <h1>Add New Movie</h1>
    
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

    <form action="?action=films/create" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required 
                value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        </div>
          <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="director">Director:</label>
            <input type="text" id="director" name="director" required 
                value="<?= htmlspecialchars($_POST['director'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="release_year">Release Year:</label>
            <input type="number" id="release_year" name="release_year" required 
                min="1888" max="<?= date('Y') ?>"
                value="<?= htmlspecialchars($_POST['release_year'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="duration">Duration (minutes):</label>
            <input type="number" id="duration" name="duration" required min="1" max="999"
                value="<?= htmlspecialchars($_POST['duration'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="cover_image">Cover Image:</label>
            <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp">
            <small>Maximum size: 5MB. Allowed types: JPG, PNG, WebP</small>
        </div>
        
        <div class="form-group">
            <label>Genres:</label>
            <div class="checkbox-group">
                <?php foreach ($genres as $genre): ?>
                    <label class="checkbox-label">
                        <input type="checkbox" name="genres[]" value="<?= $genre['id'] ?>"
                            <?= in_array($genre['id'], $_POST['genres'] ?? []) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($genre['name']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Movie</button>
            <a href="?action=films" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

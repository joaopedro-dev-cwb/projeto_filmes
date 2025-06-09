<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="film-detail">
    <div class="film-header">
        <h1><?= htmlspecialchars($film['title']) ?></h1>
        <span class="release-year">(<?= htmlspecialchars($film['release_year']) ?>)</span>
    </div>
      <div class="film-meta">
        <span class="director">Director: <?= htmlspecialchars($film['director']) ?></span>
        <span class="genre">Genre: <?= htmlspecialchars($film['genre']) ?></span>
    </div>
    
    <div class="film-synopsis">
        <h2>Synopsis</h2>
        <p><?= nl2br(htmlspecialchars($film['description'])) ?></p>
    </div>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="film-actions">
            <a href="?action=films/edit&id=<?= $film['id'] ?>" class="btn btn-edit">Edit</a>
            <form action="?action=films/delete&id=<?= $film['id'] ?>" method="POST" class="delete-form">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<!-- Reviews section -->
<div class="reviews-section">
    <h2>Reviews</h2>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="add-review">
            <h3>Leave your review</h3>
            <form action="?action=reviews/create" method="POST">
                <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
                
                <div class="rating">
                    <label>Rating:</label>
                    <select name="rating" required>
                        <option value="">Select...</option>
                        <option value="1">1 Star</option>
                        <option value="2">2 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="5">5 Stars</option>
                    </select>
                </div>
                
                <div class="form-group">                    <label for="comment">Comment:</label>
                    <textarea id="comment" name="comment" rows="4"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        </div>
    <?php endif; ?>
    
    <div class="reviews-list">
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <div class="review-header">
                    <span class="review-author"><?= htmlspecialchars($review['user_name']) ?></span>
                    <span class="review-rating"><?= str_repeat('★', $review['rating']) ?></span>
                </div>
                <div class="review-content">
                    <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                </div>
                <div class="review-date">
                    <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
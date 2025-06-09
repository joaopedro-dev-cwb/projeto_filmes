<?php
$pageTitle = "404 - Page Not Found";
require_once __DIR__ . '/../partials/header.php';
?>
<div class="error-container">
    <div class="error-code">404</div>
    <div class="error-message"><?= isset($errorMessage) ? htmlspecialchars($errorMessage) : 'Page Not Found' ?></div>
    <p>The page you're looking for doesn't exist or has been moved.</p>
    <a href="/projeto_filmes/public/" class="back-home">Back to Home</a>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>

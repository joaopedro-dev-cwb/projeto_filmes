<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="auth-container">
    <h1>Login</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>    <form action="?action=login" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    
    <div class="auth-links">        <a href="?action=register">Create new account</a> | 
        <a href="?action=reset-password">Forgot password?</a>
    </div>
</div

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
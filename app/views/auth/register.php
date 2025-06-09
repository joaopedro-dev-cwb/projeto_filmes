<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="auth-container">    <h1>Create Account</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="?action=register" method="POST">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" required>
        </div>

        <div class="form-group">
            <label for="birth_date">Birth Date:</label>
            <input type="date" id="birth_date" name="birth_date" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required minlength="6">
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    
    <div class="auth-links">
        Already have an account? <a href="?action=login">Login</a>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
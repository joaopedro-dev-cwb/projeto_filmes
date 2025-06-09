<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="auth-container">
    <h1>Reset Password</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="?action=reset-password">
        <div class="form-group">
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" required>
        </div>

        <div class="form-group">
            <label for="birth_date">Birth Date:</label>
            <input type="date" id="birth_date" name="birth_date" required>
        </div>

        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
        </div>

        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

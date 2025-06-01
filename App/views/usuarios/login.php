<?php include '../App/views/header.php'; ?>

<div class="auth-container">
    <h1>Login</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="login" method="POST">
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
    
    <div class="auth-links">
        <a href="">Criar nova conta</a> | 
        <a href="">Esqueci minha senha</a>
    </div>
</div

<?php require_once __DIR__ . '/../App/views/footer.php'; ?>
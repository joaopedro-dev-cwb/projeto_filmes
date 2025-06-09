<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="auth-container">
    <h1>Criar Conta</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="register" method="POST">
        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" required>
        </div>
        
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" required>
        </div>

        <div class="form-group">
            <label for="dtaNasc">Data de Nascimento:</label>
            <input type="date" id="dtaNasc" name="dtaNasc" required>
        </div>
        
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required minlength="6">
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmar Senha:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
    
    <div class="auth-links">
        Já tem conta? <a href="login/">Faça login</a>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
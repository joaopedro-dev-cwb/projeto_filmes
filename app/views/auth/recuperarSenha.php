<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="auth-container">
    <h1>Recuperar senha</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="cpf_teste">CPF:</label>
            <input type="text" id="cpf_teste" name="cpf_teste" required>
        </div>

        <div class="form-group">
            <label for="dtaNasc_teste">Data de Nascimento:</label>
            <input type="date" id="dtaNasc_teste" name="dtaNasc_teste" required>
        </div>

        <div class="form-group">
            <label for="nova_senha">Nova Senha:</label>
            <input type="password" id="nova_senha" name="nova_senha" required>
        </div>

        <button type="submit" class="btn btn-primary">Alterar Senha</button>
    </form>

    </div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
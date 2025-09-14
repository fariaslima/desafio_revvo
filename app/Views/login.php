<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Leo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <main style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
        <div class="modal-content">
        <form method="POST" action="/login" class="modal-form">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <div class="form-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" placeholder="Senha" required>
            <button type="submit" class="course-btn">Entrar</button>
            <p><a href="/register">Cadastrar-se</a></p>
        </form>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2024 Leo - Plataforma de Cursos Online</p>
        </div>
    </footer>
    <script src="/assets/js/main.js"></script>
</body>
</html>
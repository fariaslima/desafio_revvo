<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Leo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <main style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
        <div class="modal-content">
        <form method="POST" action="/register" class="modal-form">
            <h2>Cadastro</h2>
            <?php if (isset($error)): ?>
                <div class="form-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <label for="name">Nome</label>
            <input type="text" id="name" name="name" placeholder="Nome" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <label for="password">Senha</label>
            <input type="password" id="password" name="password" placeholder="Senha" required>

            <label for="is_admin">
                <input type="checkbox" id="is_admin" name="is_admin" value="1"> Administrador
            </label>

            <button type="submit" class="course-btn">Cadastrar</button>
            <p><a href="/login">Já tem conta? Faça login</a></p>
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

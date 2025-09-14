<div class="container">
    <div class="logo">
        <h1>Leo</h1>
    </div>
    <nav>
        <form class="search-bar">
            <input type="text" placeholder="Pesquisar cursos..." aria-label="Pesquisar cursos" />
            <button type="button" aria-label="Buscar">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <?php if ($user): ?>
            <div class="user-menu">
                <span>Olá, <?= htmlspecialchars($user->name) ?></span>
                <a href="/logout">Sair</a>
                <?php if ($user->is_admin): ?>
                    <a href="/dashboard/courses">Dashboard</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <a href="/login">Login</a>
            <a href="/register">Cadastrar</a>
        <?php endif; ?>
    </nav>
</div>

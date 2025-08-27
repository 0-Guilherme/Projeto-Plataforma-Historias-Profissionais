<?php
if (!isset($_SESSION)) {
    session_start();
}

// Get current user info if logged in
$current_user = null;
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../config/database.php';
    $stmt = $pdo->prepare("SELECT nome FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $current_user = $stmt->fetch();
}
?>

<header class="header">
    <div class="header-content">
        <h2>Networking Profissional</h2>
        
        <?php if ($current_user): ?>
            <nav class="nav-links">
                <a href="feed.php">Feed</a>
                <a href="perfil.php">Meu Perfil</a>
                <span>Olá, <?php echo htmlspecialchars($current_user['nome']); ?>!</span>
                <a href="auth/logout.php">Sair</a>
            </nav>
        <?php else: ?>
            <nav class="nav-links">
                <a href="login.html">Login</a>
                <a href="inscricao.html">Cadastro</a>
            </nav>
        <?php endif; ?>
    </div>
</header>

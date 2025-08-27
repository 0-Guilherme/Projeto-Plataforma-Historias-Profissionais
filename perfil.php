<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $empresa = trim($_POST['empresa']);
    $cargo = trim($_POST['cargo']);
    $status_tag = $_POST['status_tag'];
    
    $stmt = $pdo->prepare("UPDATE users SET nome = ?, sobrenome = ?, empresa = ?, cargo = ?, status_tag = ? WHERE id = ?");
    $stmt->execute([$nome, $sobrenome, $empresa, $cargo, $status_tag, $_SESSION['user_id']]);
    
    $success_message = "Perfil atualizado com sucesso!";
}

// Get user ID from URL or use current user
$profile_user_id = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['user_id'];

// Get user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$profile_user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: feed.php');
    exit();
}

// Get user's posts
$posts_stmt = $pdo->prepare("
    SELECT p.*, 
           COUNT(DISTINCT l.id) as likes_count,
           COUNT(DISTINCT c.id) as comments_count
    FROM posts p 
    LEFT JOIN likes l ON p.id = l.post_id
    LEFT JOIN comments c ON p.id = c.post_id
    WHERE p.user_id = ?
    GROUP BY p.id, p.user_id, p.conteudo, p.created_at
    ORDER BY p.created_at DESC
");
$posts_stmt->execute([$profile_user_id]);
$user_posts = $posts_stmt->fetchAll();

$is_own_profile = ($profile_user_id == $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - <?php echo htmlspecialchars($user['nome']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-info">
                    <h1><?php echo htmlspecialchars($user['nome'] . ' ' . ($user['sobrenome'] ?? '')); ?></h1>
                    <p class="profile-details">
                        <?php if ($user['cargo']): ?>
                            <strong>Cargo:</strong> <?php echo htmlspecialchars($user['cargo']); ?>
                        <?php endif; ?>
                        <?php if ($user['empresa']): ?>
                            <br><strong>Empresa:</strong> <?php echo htmlspecialchars($user['empresa']); ?>
                        <?php endif; ?>
                        <br><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
                        <br><strong>Membro desde:</strong> <?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                    </p>
                </div>
                
                <div class="status-badge">
                    <span class="status-tag large <?php echo $user['status_tag']; ?>">
                        <?php 
                            if ($user['status_tag'] == 'disponivel_contato') {
                                echo 'Disponível para contato';
                            } elseif ($user['status_tag'] == 'recrutador') {
                                echo 'Recrutador';
                            } else {
                                echo 'Indisponível';
                            }
                        ?>
                    </span>
                </div>
            </div>
            
            <div class="profile-content">
                <?php if ($is_own_profile): ?>
                    <?php if (isset($success_message)): ?>
                        <div class="success-message" style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                            <?php echo $success_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="edit-profile-section">
                        <h3>Editar Perfil</h3>
                        <form method="POST" class="edit-profile-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="nome">Nome:</label>
                                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="sobrenome">Sobrenome:</label>
                                    <input type="text" id="sobrenome" name="sobrenome" value="<?php echo htmlspecialchars($user['sobrenome'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="empresa">Empresa:</label>
                                    <input type="text" id="empresa" name="empresa" value="<?php echo htmlspecialchars($user['empresa']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="cargo">Cargo:</label>
                                    <input type="text" id="cargo" name="cargo" value="<?php echo htmlspecialchars($user['cargo']); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status_tag">Status:</label>
                                <select id="status_tag" name="status_tag" required>
                                    <option value="disponivel_contato" <?php echo $user['status_tag'] == 'disponivel_contato' ? 'selected' : ''; ?>>Disponível para Contato</option>
                                    <option value="recrutador" <?php echo $user['status_tag'] == 'recrutador' ? 'selected' : ''; ?>>Recrutador</option>
                                    <option value="indisponivel" <?php echo $user['status_tag'] == 'indisponivel' ? 'selected' : ''; ?>>Indisponível</option>
                                </select>
                            </div>
                            <button type="submit" name="update_profile" class="btn-primary">Salvar Alterações</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="posts-content">
                <div class="posts-section">
                    <h3>
                        <?php echo $is_own_profile ? 'Suas Postagens' : 'Postagens de ' . htmlspecialchars($user['nome'] . ' ' . ($user['sobrenome'] ?? '')); ?>
                        (<?php echo count($user_posts); ?>)
                    </h3>
                    
                    <?php if (empty($user_posts)): ?>
                        <div class="empty-state">
                            <p>
                                <?php echo $is_own_profile ? 'Você ainda não fez nenhuma postagem.' : 'Este usuário ainda não fez postagens.'; ?>
                            </p>
                            <?php if ($is_own_profile): ?>
                                <a href="feed.php" class="btn-primary">Criar primeira postagem</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="posts-history">
                            <?php foreach ($user_posts as $post): ?>
                                <a href="feed.php#post-<?php echo $post['id']; ?>" class="post-summary-link">
                                    <div class="post-summary">
                                        <div class="post-content">
                                            <p><?php echo nl2br(htmlspecialchars(substr($post['conteudo'], 0, 200))); ?>
                                            <?php if (strlen($post['conteudo']) > 200): ?>
                                                <span class="read-more">...</span>
                                            <?php endif; ?>
                                            </p>
                                        </div>
                                        
                                        <div class="post-meta">
                                            <span class="post-date"><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></span>
                                            <span class="post-stats">
                                                ❤️ <?php echo $post['likes_count']; ?> curtidas
                                                💬 <?php echo $post['comments_count']; ?> comentários
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>

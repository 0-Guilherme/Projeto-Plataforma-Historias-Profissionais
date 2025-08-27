<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

// Get user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$current_user = $stmt->fetch();

// Get all posts with user info, likes and comments count
$posts_query = "
    SELECT p.*, u.nome, u.sobrenome, u.empresa, u.cargo, u.status_tag,
           COUNT(DISTINCT l.id) as likes_count,
           COUNT(DISTINCT c.id) as comments_count,
           EXISTS(SELECT 1 FROM likes WHERE user_id = ? AND post_id = p.id) as user_liked
    FROM posts p 
    JOIN users u ON p.user_id = u.id 
    LEFT JOIN likes l ON p.id = l.post_id
    LEFT JOIN comments c ON p.id = c.post_id
    GROUP BY p.id, p.user_id, p.conteudo, p.created_at, u.nome, u.sobrenome, u.empresa, u.cargo, u.status_tag
    ORDER BY p.created_at DESC
";
$stmt = $pdo->prepare($posts_query);
$stmt->execute([$_SESSION['user_id']]);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed - Networking Profissional</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <div class="feed-container">
            <!-- Create new post -->
            <div class="post-creator">
                <h3>Compartilhar algo novo</h3>
                <form action="actions/create_post.php" method="POST">
                    <textarea name="conteudo" placeholder="O que você gostaria de compartilhar?" required></textarea>
                    <button type="submit" class="btn-primary">Publicar</button>
                </form>
            </div>
            
            <!-- Posts feed -->
            <div class="posts-feed">
                <?php if (empty($posts)): ?>
                    <div class="empty-state">
                        <p>Nenhuma postagem encontrada. Seja o primeiro a compartilhar!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="post-card" id="post-<?php echo $post['id']; ?>">
                            <div class="post-header">
                                <div class="user-info">
                                    <h4><?php echo htmlspecialchars($post['nome'] . ' ' . $post['sobrenome']); ?></h4>
                                    <p class="user-details">
                                        <?php echo htmlspecialchars($post['cargo']); ?> 
                                        <?php if ($post['empresa']): ?>
                                            em <?php echo htmlspecialchars($post['empresa']); ?>
                                        <?php endif; ?>
                                    </p>
                                    <span class="status-tag <?php echo $post['status_tag']; ?>">
                                        <?php 
                                            if ($post['status_tag'] == 'disponivel_contato') {
                                                echo 'Disponível para contato';
                                            } elseif ($post['status_tag'] == 'recrutador') {
                                                echo 'Recrutador';
                                            } else {
                                                echo 'Indisponível';
                                            }
                                        ?>
                                    </span>
                                </div>
                                <div class="post-header-right">
                                    <span class="post-date"><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></span>
                                    <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                                        <div class="post-actions-menu">
                                            <button class="edit-post-btn" onclick="toggleEditPost(<?php echo $post['id']; ?>)">✏️</button>
                                            <button class="delete-post-btn" onclick="deletePost(<?php echo $post['id']; ?>)">🗑️</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="post-content">
                                <div class="post-text" id="post-text-<?php echo $post['id']; ?>">
                                    <p><?php echo nl2br(htmlspecialchars($post['conteudo'])); ?></p>
                                </div>
                                <div class="post-edit" id="post-edit-<?php echo $post['id']; ?>" style="display: none;">
                                    <form method="POST" action="actions/edit_post.php">
                                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                        <textarea name="conteudo" required><?php echo htmlspecialchars($post['conteudo']); ?></textarea>
                                        <div class="edit-buttons">
                                            <button type="submit" class="btn-save">Salvar</button>
                                            <button type="button" class="btn-cancel" onclick="toggleEditPost(<?php echo $post['id']; ?>)">Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="post-actions">
                                <button class="like-btn <?php echo $post['user_liked'] ? 'liked' : ''; ?>" 
                                        onclick="toggleLike(<?php echo $post['id']; ?>)">
                                    ❤️ <?php echo $post['likes_count']; ?> curtidas
                                </button>
                                <button class="comment-btn" onclick="toggleComments(<?php echo $post['id']; ?>)">
                                    💬 <?php echo $post['comments_count']; ?> comentários
                                </button>
                            </div>
                            
                            <!-- Comments section -->
                            <div class="comments-section" id="comments-<?php echo $post['id']; ?>" style="display: none;">
                                <form class="comment-form" onsubmit="addComment(event, <?php echo $post['id']; ?>)">
                                    <input type="text" placeholder="Escreva um comentário..." required>
                                    <button type="submit">Comentar</button>
                                </form>
                                
                                <div class="comments-list">
                                    <?php
                                    $comments_stmt = $pdo->prepare("
                                        SELECT c.*, u.nome, u.sobrenome 
                                        FROM comments c 
                                        JOIN users u ON c.user_id = u.id 
                                        WHERE c.post_id = ? 
                                        ORDER BY c.created_at ASC
                                    ");
                                    $comments_stmt->execute([$post['id']]);
                                    $comments = $comments_stmt->fetchAll();
                                    
                                    foreach ($comments as $comment):
                                    ?>
                                        <div class="comment">
                                            <div class="comment-content">
                                                <strong><?php echo htmlspecialchars($comment['nome'] . ' ' . ($comment['sobrenome'] ?? '')); ?>:</strong>
                                                <span><?php echo htmlspecialchars($comment['comentario']); ?></span>
                                                <small><?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?></small>
                                            </div>
                                            <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                                                <button class="delete-comment-btn" onclick="deleteComment(<?php echo $comment['id']; ?>)">🗑️</button>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        function toggleLike(postId) {
            fetch('actions/like_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'post_id=' + postId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
        
        function toggleComments(postId) {
            const commentsSection = document.getElementById('comments-' + postId);
            commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none';
        }
        
        function addComment(event, postId) {
            event.preventDefault();
            const form = event.target;
            const comment = form.querySelector('input').value;
            
            fetch('actions/add_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'post_id=' + postId + '&comentario=' + encodeURIComponent(comment)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
        
        function toggleEditPost(postId) {
            const textDiv = document.getElementById('post-text-' + postId);
            const editDiv = document.getElementById('post-edit-' + postId);
            
            if (textDiv.style.display === 'none') {
                textDiv.style.display = 'block';
                editDiv.style.display = 'none';
            } else {
                textDiv.style.display = 'none';
                editDiv.style.display = 'block';
            }
        }
        
        function deletePost(postId) {
            if (confirm('Tem certeza que deseja remover esta postagem?')) {
                fetch('actions/delete_post.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'post_id=' + postId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
            }
        }
        
        function deleteComment(commentId) {
            if (confirm('Tem certeza que deseja remover este comentário?')) {
                fetch('actions/delete_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'comment_id=' + commentId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
            }
        }
    </script>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>

<?php
session_start();
require_once '../config/session_config.php';
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = (int)$_POST['post_id'];
    $conteudo = trim($_POST['conteudo']);
    $user_id = $_SESSION['user_id'];
    
    if (empty($conteudo)) {
        $_SESSION['post_error'] = "O conteúdo da postagem não pode estar vazio";
        header('Location: ../feed.php');
        exit();
    }
    
    // Validate content length
    if (strlen($conteudo) > 5000) {
        $_SESSION['post_error'] = "A postagem é muito longa. Máximo 5000 caracteres.";
        header('Location: ../feed.php');
        exit();
    }
    
    // Verify that the post belongs to the current user
    $stmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$post_id, $user_id]);
    $post = $stmt->fetch();
    
    if (!$post) {
        $_SESSION['post_error'] = "Você não tem permissão para editar esta postagem";
        header('Location: ../feed.php');
        exit();
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE posts SET conteudo = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$conteudo, $post_id, $user_id]);
        
        $_SESSION['post_success'] = "Postagem editada com sucesso!";
        header('Location: ../feed.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['post_error'] = "Erro ao editar postagem. Tente novamente.";
        header('Location: ../feed.php');
        exit();
    }
}
?>
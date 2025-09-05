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
    
    try {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, conteudo) VALUES (?, ?)");
        $stmt->execute([$user_id, $conteudo]);
        
        $_SESSION['post_success'] = "Postagem criada com sucesso!";
        header('Location: ../feed.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['post_error'] = "Erro ao criar postagem. Tente novamente.";
        header('Location: ../feed.php');
        exit();
    }
}
?>

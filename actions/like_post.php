<?php
session_start();
require_once '../config/session_config.php';
require_once '../config/database.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = (int)$_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    
    if (!$post_id) {
        echo json_encode(['success' => false, 'message' => 'ID da postagem inválido']);
        exit();
    }
    
    try {
        // Check if user already liked this post
        $stmt = $pdo->prepare("SELECT id FROM likes WHERE user_id = ? AND post_id = ?");
        $stmt->execute([$user_id, $post_id]);
        $existing_like = $stmt->fetch();
        
        if ($existing_like) {
            // Unlike - remove the like
            $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND post_id = ?");
            $stmt->execute([$user_id, $post_id]);
            echo json_encode(['success' => true, 'action' => 'unliked']);
        } else {
            // Like - add the like
            $stmt = $pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $post_id]);
            echo json_encode(['success' => true, 'action' => 'liked']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao processar curtida']);
    }
}
?>

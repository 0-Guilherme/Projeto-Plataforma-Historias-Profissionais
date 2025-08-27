<?php
session_start();
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
    
    // Verify that the post belongs to the current user
    $stmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$post_id, $user_id]);
    $post = $stmt->fetch();
    
    if (!$post) {
        echo json_encode(['success' => false, 'message' => 'Você não tem permissão para remover esta postagem']);
        exit();
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        $stmt->execute([$post_id, $user_id]);
        
        echo json_encode(['success' => true, 'message' => 'Postagem removida com sucesso']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao remover postagem']);
    }
}
?>
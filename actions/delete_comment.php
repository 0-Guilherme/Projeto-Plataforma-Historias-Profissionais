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
    $comment_id = (int)$_POST['comment_id'];
    $user_id = $_SESSION['user_id'];
    
    if (!$comment_id) {
        echo json_encode(['success' => false, 'message' => 'ID do comentário inválido']);
        exit();
    }
    
    // Verify that the comment belongs to the current user
    $stmt = $pdo->prepare("SELECT user_id FROM comments WHERE id = ? AND user_id = ?");
    $stmt->execute([$comment_id, $user_id]);
    $comment = $stmt->fetch();
    
    if (!$comment) {
        echo json_encode(['success' => false, 'message' => 'Você não tem permissão para remover este comentário']);
        exit();
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
        $stmt->execute([$comment_id, $user_id]);
        
        echo json_encode(['success' => true, 'message' => 'Comentário removido com sucesso']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao remover comentário']);
    }
}
?>
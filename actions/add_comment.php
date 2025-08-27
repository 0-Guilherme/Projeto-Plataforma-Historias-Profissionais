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
    $comentario = trim($_POST['comentario']);
    $user_id = $_SESSION['user_id'];
    
    if (!$post_id || empty($comentario)) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
        exit();
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO comments (user_id, post_id, comentario) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $post_id, $comentario]);
        
        echo json_encode(['success' => true, 'message' => 'Comentário adicionado']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao adicionar comentário']);
    }
}
?>

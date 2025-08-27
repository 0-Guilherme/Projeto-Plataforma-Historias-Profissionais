<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    
    if (empty($email) || empty($senha)) {
        $_SESSION['login_error'] = "Email e senha são obrigatórios";
        header('Location: ../login.html');
        exit();
    }
    
    // Find user by email
    $stmt = $pdo->prepare("SELECT id, nome, senha FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($senha, $user['senha'])) {
        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nome'];
        
        header('Location: ../feed.php');
        exit();
    } else {
        // Login failed
        $_SESSION['login_error'] = "Email ou senha incorretos";
        header('Location: ../login.html');
        exit();
    }
}
?>

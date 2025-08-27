<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $empresa = trim($_POST['empresa']);
    $cargo = trim($_POST['cargo']);
    $status_tag = $_POST['status_tag'];
    
    $errors = [];
    
    // Validate required fields
    if (empty($nome)) {
        $errors[] = "Nome é obrigatório";
    }
    
    if (empty($sobrenome)) {
        $errors[] = "Sobrenome é obrigatório";
    }
    
    if (empty($email)) {
        $errors[] = "Email é obrigatório";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email inválido";
    }
    
    if (empty($senha)) {
        $errors[] = "Senha é obrigatória";
    }
    
    if ($senha !== $confirmar_senha) {
        $errors[] = "Senhas não coincidem";
    }
    
    if (empty($status_tag) || !in_array($status_tag, ['disponivel_contato', 'recrutador', 'indisponivel'])) {
        $errors[] = "Status é obrigatório";
    }
    
    // Check if email already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Este email já está em uso";
        }
    }
    
    // If no errors, create account
    if (empty($errors)) {
        $hashed_password = password_hash($senha, PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (nome, sobrenome, email, senha, empresa, cargo, status_tag) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $sobrenome, $email, $hashed_password, $empresa, $cargo, $status_tag]);
            
            // Auto login after registration
            session_start();
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $nome;
            
            header('Location: ../feed.php');
            exit();
        } catch (PDOException $e) {
            $errors[] = "Erro ao criar conta. Tente novamente.";
        }
    }
    
    // If there are errors, redirect back with error message
    if (!empty($errors)) {
        session_start();
        $_SESSION['register_errors'] = $errors;
        header('Location: ../inscricao.html');
        exit();
    }
}
?>

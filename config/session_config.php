<?php
/**
 * Configurações de Segurança para Sessões
 * Inclua este arquivo no início de todos os arquivos que usam sessões
 */

// Verificar se a sessão não expirou (apenas se a sessão estiver ativa)
if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > 3600) { // 1 hora
        session_unset();
        session_destroy();
        header('Location: login.html');
        exit();
    }
    $_SESSION['last_activity'] = time();
}

// Regenerar ID de sessão periodicamente para prevenir session fixation
if (session_status() === PHP_SESSION_ACTIVE) {
    if (!isset($_SESSION['last_regeneration'])) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > 300) { // 5 minutos
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}
?>

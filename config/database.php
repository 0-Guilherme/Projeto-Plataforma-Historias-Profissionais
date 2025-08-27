<?php
// Database configuration for PostgreSQL
$host = $_ENV['PGHOST'] ?? 'localhost';
$dbname = $_ENV['PGDATABASE'] ?? 'networking_platform';
$username = $_ENV['PGUSER'] ?? 'postgres';
$password = $_ENV['PGPASSWORD'] ?? '';
$port = $_ENV['PGPORT'] ?? '5432';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>

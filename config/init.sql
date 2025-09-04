-- Database initialization script for MySQL

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS networking_platform;
USE networking_platform;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    sobrenome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    empresa VARCHAR(100),
    cargo VARCHAR(100),
    status_tag ENUM('disponivel_contato', 'procurando_oportunidades', 'recrutador', 'empregado') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Posts table
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    conteudo TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Likes table
CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (user_id, post_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Comments table
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    comentario TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Insert sample data (optional)
INSERT INTO users (nome, sobrenome, email, senha, empresa, cargo, status_tag) VALUES
('João', 'Silva', 'joao@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'TechCorp', 'Desenvolvedor', 'disponivel_contato'),
('Maria', 'Santos', 'maria@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'RecruitHR', 'Recrutadora', 'recrutador'),
('Pedro', 'Oliveira', 'pedro@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'StartupXYZ', 'Analista', 'procurando_oportunidades')
ON DUPLICATE KEY UPDATE nome = nome;

-- Insert sample posts
INSERT INTO posts (user_id, conteudo) VALUES
(1, 'Olá pessoal! Estou disponível para novas oportunidades na área de desenvolvimento web.'),
(2, 'Procurando desenvolvedores PHP experientes para nossa empresa. Interessados podem entrar em contato!'),
(3, 'Acabei de finalizar um projeto interessante. Quem mais está trabalhando com tecnologias modernas?')
ON DUPLICATE KEY UPDATE conteudo = conteudo;

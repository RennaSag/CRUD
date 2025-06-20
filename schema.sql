-- database.sql
-- Script para criar o banco de dados e tabela

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS crud_clientes CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Usar o banco de dados
USE crud_clientes;

-- Criar tabela clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    endereco TEXT NOT NULL,
    data_cadastro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_nome (nome),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Inserir alguns dados de exemplo (opcional)
INSERT INTO clientes (nome, email, telefone, endereco) VALUES
('João Silva', 'joao@email.com', '(11) 99999-9999', 'Rua das Flores, 123 - São Paulo, SP'),
('Maria Santos', 'maria@email.com', '(11) 88888-8888', 'Av. Paulista, 456 - São Paulo, SP'),
('Pedro Oliveira', 'pedro@email.com', '(11) 77777-7777', 'Rua Augusta, 789 - São Paulo, SP')
ON DUPLICATE KEY UPDATE nome=VALUES(nome);
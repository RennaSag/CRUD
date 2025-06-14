CREATE DATABASE IF NOT EXISTS crud_clientes;
USE crud_clientes;

CREATE TABLE IF NOT EXISTS clientes (
    id INT(11) NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    endereco TEXT NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- dados de exemplo
INSERT INTO clientes (nome, email, telefone, endereco) VALUES
('João Silva', 'joao@email.com', '(11) 99999-9999', 'Rua das Flores, 123 - São Paulo, SP'),
('Maria Santos', 'maria@email.com', '(11) 88888-8888', 'Av. Paulista, 456 - São Paulo, SP'),
('Pedro Oliveira', 'pedro@email.com', '(11) 77777-7777', 'Rua Augusta, 789 - São Paulo, SP');
*/
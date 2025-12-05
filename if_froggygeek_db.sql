-- ==========================================================
-- CRIAÇÃO DA ESTRUTURA DO BANCO DE DADOS - FROGGY GEEK
-- ==========================================================

-- 1. Criar o Banco de Dados
DROP DATABASE IF EXISTS froggygeek_db;
CREATE DATABASE froggygeek_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE froggygeek_db;

-- 2. Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    genero VARCHAR(50) DEFAULT 'Não informado',
    data_nascimento DATE DEFAULT NULL,
    foto_perfil VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB;

-- 3. Tabela de Produtos
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    categoria VARCHAR(50),
    preco DECIMAL(10, 2),
    estoque INT DEFAULT 0,
    desconto INT DEFAULT 0,
    descricao TEXT DEFAULT NULL
) ENGINE=InnoDB;

-- 4. Tabela de Estoque por Tamanho
CREATE TABLE estoque_tamanhos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT NOT NULL,
    tamanho VARCHAR(5) NOT NULL, -- P, M, G, GG
    quantidade INT DEFAULT 0,
    FOREIGN KEY (id_produto) REFERENCES produtos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Tabela de Cupons
CREATE TABLE cupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    desconto_percentual INT NOT NULL,
    ativo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- 6. Tabela de Cupons do Usuário (Carteira)
CREATE TABLE cupons_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_cupom INT NOT NULL,
    usado TINYINT(1) DEFAULT 0,
    data_ganho DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_cupom) REFERENCES cupons(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. Tabela de Cartões de Crédito
CREATE TABLE cartoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    apelido_cartao VARCHAR(50),
    numero_final VARCHAR(4),
    nome_titular VARCHAR(100),
    validade VARCHAR(10),
    dados_criptografados TEXT, -- Mantido para compatibilidade futura
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. Tabela de Pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    data_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    valor_total DECIMAL(10, 2),
    metodo_pagamento VARCHAR(50),
    status VARCHAR(50) DEFAULT 'Pendente',
    id_cupom INT DEFAULT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_cupom) REFERENCES cupons(id)
) ENGINE=InnoDB;

-- 9. Tabela de Itens do Pedido
CREATE TABLE itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10, 2),
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produtos(id)
) ENGINE=InnoDB;

-- 10. Tabela de Avaliações
CREATE TABLE avaliacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT NOT NULL,
    id_usuario INT NOT NULL,
    nota INT NOT NULL, -- 1 a 5
    comentario TEXT,
    data_avaliacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produto) REFERENCES produtos(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
) ENGINE=InnoDB;

-- 11. Tabela de Suporte
CREATE TABLE suporte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tipo_solicitacao VARCHAR(50),
    mensagem TEXT,
    status VARCHAR(20) DEFAULT 'Aberto',
    data_abertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_pedido INT DEFAULT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id)
) ENGINE=InnoDB;
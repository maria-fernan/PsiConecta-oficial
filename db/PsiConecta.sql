-- Criação do banco
CREATE DATABASE IF NOT EXISTS PsiConecta;
USE PsiConecta;

-- Tabela de pacientes com telefone incluso
CREATE TABLE IF NOT EXISTS paciente (
    idPaciente INT AUTO_INCREMENT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    dtNasc DATE,
    email VARCHAR(100),
    senha VARCHAR(255),
    telefone VARCHAR(15),          -- telefone agora dentro da tabela paciente
    foto VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (idPaciente)
);
DESC paciente;

-- Tabela de psicólogos com telefone, área de atuação e tempo de atuação
CREATE TABLE IF NOT EXISTS psicologo (
    nome VARCHAR(50) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    dtNasc DATE,
    email VARCHAR(100),
    senha VARCHAR(255),
    crp VARCHAR(15) NOT NULL,
    telefone VARCHAR(15),          -- telefone agora dentro da tabela psicologo
    area_atuacao VARCHAR(100),     -- nova coluna: área de atuação
    tempo_atuacao VARCHAR(50),     -- nova coluna: tempo de atuação
    foto VARCHAR(255) DEFAULT NULL,
    validado TINYINT DEFAULT 0,    -- 0 = pendente, 1 = validado, 2 = recusado
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (crp)
);
DESC psicologo;

-- Tabela de atendimentos continua a mesma
CREATE TABLE IF NOT EXISTS atende (
    data_hora TIMESTAMP,
    crp VARCHAR(15),
    idPaciente INT,
    PRIMARY KEY (crp, idPaciente),
    FOREIGN KEY (crp) REFERENCES psicologo(crp),
    FOREIGN KEY (idPaciente) REFERENCES paciente(idPaciente)
);
DESC atende;

-- Tabela de status online continua a mesma
CREATE TABLE IF NOT EXISTS on_line (
    idOnline INT AUTO_INCREMENT PRIMARY KEY,
    crp VARCHAR(15),
    inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fim TIMESTAMP NULL,
    status ENUM('online','offline') DEFAULT 'offline',
    FOREIGN KEY (crp) REFERENCES psicologo(crp)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
DESC on_line;

-- Tabela de administradores continua a mesma
CREATE TABLE IF NOT EXISTS adm (
    usuario VARCHAR(100),
    senha VARCHAR(255)
);

INSERT INTO adm (usuario, senha) VALUES ("AdministradoresPC", "PsiConecta2025");

SHOW TABLES;

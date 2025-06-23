-- Criação do banco
CREATE DATABASE IF NOT EXISTS sistema_certificados
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE sistema_certificados;

-- Tabela: usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(20) NOT NULL UNIQUE,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    is_admin BOOLEAN NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Tabela: alunos (herda de usuarios)
CREATE TABLE alunos (
    id INT PRIMARY KEY,
    curso VARCHAR(100) NOT NULL,
    fase INT,
    total_horas INT DEFAULT 0,
    FOREIGN KEY (id) REFERENCES usuarios(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Tabela: administradores (herda de usuarios)
CREATE TABLE administradores (
    id INT PRIMARY KEY,
    tipo_administrador VARCHAR(50),
    FOREIGN KEY (id) REFERENCES usuarios(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Tabela: certificados
CREATE TABLE certificados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria ENUM('ACADEMICO', 'SOCIOCULTURAL', 'PROFISSIONAL'),
    curso VARCHAR(100),
    requerente_id INT NOT NULL,
    data_criacao DATE NOT NULL,
    iniciou_atividade BOOLEAN DEFAULT FALSE,
    prazo_final DATE,
    observacao TEXT,
    status ENUM('ENTREGUE', 'APROVADO', 'REPROVADO', 'APROVADO_COM_RESSALVAS') NOT NULL,
    carga_horaria INT,
    nome_certificado VARCHAR(255),
    instituicao VARCHAR(100),
    data_emissao DATE,
    arquivo TEXT,
    FOREIGN KEY (requerente_id) REFERENCES alunos(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Tabela: gerenciamentos
CREATE TABLE gerenciamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_alteracao DATE NOT NULL,
    alteracao TEXT NOT NULL,
    observacao TEXT,
    certificado_id INT NOT NULL,
    FOREIGN KEY (certificado_id) REFERENCES certificados(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

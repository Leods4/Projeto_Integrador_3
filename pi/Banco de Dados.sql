-- Criação do banco
CREATE DATABASE IF NOT EXISTS sistema_certificados;
USE sistema_certificados;

-- Tabela: usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(20) NOT NULL UNIQUE,
    cpf varchar(20) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    is_admin BOOLEAN NOT NULL
);

-- Tabela: alunos (herda de usuarios)
CREATE TABLE alunos (
    id INT PRIMARY KEY,
    curso VARCHAR(100) NOT NULL,
    fase INT,
    total_horas INT DEFAULT 0,
    FOREIGN KEY (id) REFERENCES usuarios(id)
        ON DELETE CASCADE
);

-- Tabela: administradores (herda de usuarios)
CREATE TABLE administradores (
    id INT PRIMARY KEY,
    tipo_administrador VARCHAR(50),
    FOREIGN KEY (id) REFERENCES usuarios(id)
        ON DELETE CASCADE
);

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
    status ENUM('ENTREGUE', 'APROVADO', 'REPROVADO', 'APROVADO_RESSALVAS') NOT NULL,
    carga_horaria INT,
    nome_certificado VARCHAR(255),
    instituicao VARCHAR(100),
    data_emissao DATE,
    arquivo TEXT,
    FOREIGN KEY (requerente_id) REFERENCES alunos(id)
        ON DELETE CASCADE
);

-- Tabela: gerenciamentos
CREATE TABLE gerenciamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_alteracao DATE NOT NULL,
    alteracao TEXT NOT NULL,
    observacao TEXT,
    certificado_id INT NOT NULL,
    FOREIGN KEY (certificado_id) REFERENCES certificados(id)
        ON DELETE CASCADE
);

-- INSERÇÃO DE USUÁRIOS (3 alunos + 1 administrador)
INSERT INTO usuarios (matricula, cpf, nome, email, senha_hash, is_admin) VALUES
('2023001', '123.456.789-01', 'João da Silva', 'joao@email.com', '$2y$10$k9P7Tn84ZceR7cfnZUnZKe57zJYG2N9B6mY77FXS1xN6FBNxQoGea', 0),-- senha123
('2023002', '234.567.890-12', 'Maria Oliveira', 'maria@email.com', '$2y$10$tZ.mfGBOsDdNs/4o5kTHWeAbMH0PXw5QZul9ktcIGGvbnRJENwAMO', 0),-- maria456
('2023003', '345.678.901-23', 'Carlos Pereira', 'carlos@email.com', '$2y$10$7Zr9Zz0zvdZuNQErb8eh/OAJ8oe3S1ZyEVePVhOe7vbA40NmYUJKS', 0),-- carlos78
('ADM001', '999.999.999-99', 'Admin Geral', 'admin@email.com', '$2y$10$L4U7CVpBXdRUtXSuIuhReunxlVyIjBdM1A6ZnMPF/bw02zIu4xSCa', 1);-- admin123

-- INSERÇÃO NA TABELA ALUNOS (vinculados aos usuários com is_admin = 0)
INSERT INTO alunos (id, curso, fase, total_horas) VALUES
(1, 'Análise e Desenvolvimento de Sistemas', 3, 45),
(2, 'Engenharia de Software', 2, 20),
(3, 'Sistemas de Informação', 1, 0);

-- INSERÇÃO NA TABELA ADMINISTRADORES
INSERT INTO administradores (id, tipo_administrador) VALUES
(4, 'Coordenador Geral');

-- João da Silva (id = 1)
INSERT INTO certificados (categoria, curso, requerente_id, data_criacao, iniciou_atividade, prazo_final, observacao, status, carga_horaria, nome_certificado, instituicao, data_emissao, arquivo)
VALUES
('ACADEMICO', 'Curso de Python', 1, '2025-05-01', TRUE, '2025-05-30', 'Curso introdutório concluído com êxito.', 'APROVADO', 20, 'Python Básico', 'IFSC', '2025-05-30', 'arquivo1.pdf'),
('SOCIOCULTURAL', 'Voluntariado em ONG', 1, '2025-04-01', TRUE, '2025-04-15', 'Atuação em projeto social.', 'APROVADO COM RESSALVAS', 25, 'Projeto Social Cidadania', 'ONG Esperança', '2025-04-20', 'arquivo2.pdf');

-- Maria Oliveira (id = 2)
INSERT INTO certificados (categoria, curso, requerente_id, data_criacao, iniciou_atividade, prazo_final, observacao, status, carga_horaria, nome_certificado, instituicao, data_emissao, arquivo)
VALUES
('PROFISSIONAL', 'Estágio na empresa X', 2, '2025-03-10', TRUE, '2025-06-10', 'Relatório de estágio apresentado.', 'ENTREGUE', 40, 'Estágio Profissional', 'Empresa X', '2025-06-01', 'arquivo3.pdf'),
('ACADEMICO', 'Curso de Banco de Dados', 2, '2025-02-05', TRUE, '2025-03-05', 'Participação em curso técnico.', 'APROVADO', 15, 'SQL Intermediário', 'IFSC', '2025-03-06', 'arquivo4.pdf');

-- Carlos Pereira (id = 3)
INSERT INTO certificados (categoria, curso, requerente_id, data_criacao, iniciou_atividade, prazo_final, observacao, status, carga_horaria, nome_certificado, instituicao, data_emissao, arquivo)
VALUES
('SOCIOCULTURAL', 'Oficina de Teatro', 3, '2025-01-10', TRUE, '2025-01-30', 'Participou ativamente das apresentações.', 'REPROVADO', 10, 'Teatro e Expressão', 'Casa da Cultura', '2025-01-30', 'arquivo5.pdf'),
('PROFISSIONAL', 'Curso de Suporte Técnico', 3, '2025-04-10', TRUE, '2025-05-10', 'Treinamento prático em TI.', 'APROVADO', 30, 'Suporte Técnico em TI', 'SENAI', '2025-05-11', 'arquivo6.pdf');

INSERT INTO gerenciamentos (data_alteracao, alteracao, observacao, certificado_id)
VALUES
('2025-06-10', 'Alterado status de PENDENTE para APROVADO', 'Revisado pelo coordenador geral.', 1);

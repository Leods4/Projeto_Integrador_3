-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23/06/2025 às 22:35
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_certificados`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `tipo_administrador` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `administradores`
--

INSERT INTO `administradores` (`id`, `tipo_administrador`) VALUES
(4, 'Coordenador Geral');

-- --------------------------------------------------------

--
-- Estrutura para tabela `alunos`
--

CREATE TABLE `alunos` (
  `id` int(11) NOT NULL,
  `curso` varchar(100) NOT NULL,
  `fase` int(11) DEFAULT NULL,
  `total_horas` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `alunos`
--

INSERT INTO `alunos` (`id`, `curso`, `fase`, `total_horas`) VALUES
(1, 'Análise e Desenvolvimento de Sistemas', 3, 45),
(2, 'Administração', 2, 20),
(3, 'Pedagogia', 1, 0),
(5, 'Tecnologia em Processos Gerenciais', 2, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `certificados`
--

CREATE TABLE `certificados` (
  `id` int(11) NOT NULL,
  `categoria` enum('ACADEMICO','SOCIOCULTURAL','PROFISSIONAL') DEFAULT NULL,
  `tipo` varchar(100) DEFAULT NULL,
  `curso` varchar(100) DEFAULT NULL,
  `requerente_id` int(11) NOT NULL,
  `data_criacao` date NOT NULL,
  `iniciou_atividade` tinyint(1) DEFAULT 0,
  `prazo_final` date DEFAULT NULL,
  `observacao` text DEFAULT NULL,
  `status` enum('ENTREGUE','APROVADO','REPROVADO','APROVADO_COM_RESSALVAS') NOT NULL,
  `carga_horaria` int(11) DEFAULT NULL,
  `nome_certificado` varchar(255) DEFAULT NULL,
  `instituicao` varchar(100) DEFAULT NULL,
  `data_emissao` date DEFAULT NULL,
  `arquivo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `certificados`
--

INSERT INTO `certificados` (`id`, `categoria`, `tipo`, `curso`, `requerente_id`, `data_criacao`, `iniciou_atividade`, `prazo_final`, `observacao`, `status`, `carga_horaria`, `nome_certificado`, `instituicao`, `data_emissao`, `arquivo`) VALUES
(16, 'ACADEMICO', 'Curso', 'Análise e Desenvolvimento de Sistemas', 1, '2025-05-01', 1, '2025-12-31', 'Participação em curso de Python.', 'APROVADO', 20, 'Python Básico', 'IFSC', '2025-05-30', 'exemplo.pdf'),
(17, 'PROFISSIONAL', 'Estágio', 'Análise e Desenvolvimento de Sistemas', 1, '2025-06-01', 1, '2025-12-31', 'Estágio obrigatório concluído.', 'ENTREGUE', 40, 'Estágio Supervisionado', 'Empresa X', '2025-06-30', 'exemplo.pdf'),
(18, 'SOCIOCULTURAL', 'Voluntariado', 'Análise e Desenvolvimento de Sistemas', 1, '2025-04-01', 1, '2025-12-31', 'Atuação em projeto social.', 'REPROVADO', 15, 'Projeto Social Cidadania', 'ONG Esperança', '2025-04-20', 'exemplo.pdf'),
(19, 'PROFISSIONAL', 'Estágio', 'Administração', 2, '2025-03-10', 1, '2025-12-31', 'Relatório de estágio apresentado.', 'APROVADO', 40, 'Estágio Profissional', 'Empresa Y', '2025-06-01', 'exemplo.pdf'),
(20, 'ACADEMICO', 'Curso', 'Administração', 2, '2025-03-05', 1, '2025-12-31', 'Participação em curso técnico.', 'REPROVADO', 15, 'Curso de Gestão', 'SENAC', '2025-05-10', 'exemplo.pdf'),
(21, 'SOCIOCULTURAL', 'Teatro', 'Pedagogia', 3, '2025-01-10', 1, '2025-12-31', 'Participou ativamente das apresentações.', 'APROVADO', 10, 'Teatro e Expressão', 'Casa da Cultura', '2025-01-30', 'exemplo.pdf'),
(22, 'PROFISSIONAL', 'Treinamento', 'Pedagogia', 3, '2025-04-10', 1, '2025-12-31', 'Treinamento prático em TI.', 'ENTREGUE', 30, 'Suporte Técnico em TI', 'SENAI', '2025-05-11', 'exemplo.pdf'),
(23, 'PROFISSIONAL', 'Curso', 'Tecnologia em Processos Gerenciais', 5, '2025-03-15', 1, '2025-12-31', 'Participação em curso de liderança.', 'APROVADO', 20, 'Liderança e Gestão de Equipes', 'SENAC', '2025-03-30', 'exemplo.pdf'),
(24, 'ACADEMICO', 'Seminário', 'Tecnologia em Processos Gerenciais', 5, '2025-04-10', 1, '2025-12-31', 'Apresentação de seminário acadêmico.', 'ENTREGUE', 15, 'Seminário de Processos', 'FMP', '2025-04-25', 'exemplo.pdf'),
(25, 'SOCIOCULTURAL', 'Voluntariado', 'Tecnologia em Processos Gerenciais', 5, '2025-05-05', 1, '2025-12-31', 'Atuação em projeto social.', 'REPROVADO', 10, 'Projeto Social Empresa Júnior', 'ONG Futuro', '2025-05-20', 'exemplo.pdf');

-- --------------------------------------------------------

--
-- Estrutura para tabela `gerenciamentos`
--

CREATE TABLE `gerenciamentos` (
  `id` int(11) NOT NULL,
  `data_alteracao` date NOT NULL,
  `alteracao` text NOT NULL,
  `observacao` text DEFAULT NULL,
  `certificado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `matricula` varchar(20) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `matricula`, `cpf`, `nome`, `email`, `senha_hash`, `is_admin`) VALUES
(1, '2023001', '12345678901', 'João da Silva', 'joao@email.com', '$2y$10$zuppnrzw.w9G/CuvuySJ7ensU5Lmefla9XGM4pcWmMVfNkTzRGSw2', 0),
(2, '2023002', '23456789012', 'Maria Oliveira', 'maria@email.com', '$2y$10$18ntDRnEHXQHUwc/AgVVQejUvwKUgCFf1/jpsjaMPVy69C95jApFG', 0),
(3, '2023003', '34567890123', 'Carlos Pereira', 'carlos@email.com', '$2y$10$erTwrB8MsSJQpi6wVrihcOYtN27fCEdNoltXnRAswrBDs0PtpzOcm', 0),
(4, 'ADM001', '99999999999', 'Admin Geral', 'admin@email.com', '$2y$10$3L7FjTAnsEBZkYqLbGZU1O/1bBC8gHXyXDCELsoxAgkNfBO/uIfKG', 1),
(5, '2023004', '45678901234', 'Fernanda Souza', 'fernanda.tpg@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `certificados`
--
ALTER TABLE `certificados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requerente_id` (`requerente_id`);

--
-- Índices de tabela `gerenciamentos`
--
ALTER TABLE `gerenciamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificado_id` (`certificado_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricula` (`matricula`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `certificados`
--
ALTER TABLE `certificados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `gerenciamentos`
--
ALTER TABLE `gerenciamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `administradores`
--
ALTER TABLE `administradores`
  ADD CONSTRAINT `administradores_ibfk_1` FOREIGN KEY (`id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `alunos`
--
ALTER TABLE `alunos`
  ADD CONSTRAINT `alunos_ibfk_1` FOREIGN KEY (`id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `certificados`
--
ALTER TABLE `certificados`
  ADD CONSTRAINT `certificados_ibfk_1` FOREIGN KEY (`requerente_id`) REFERENCES `alunos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `gerenciamentos`
--
ALTER TABLE `gerenciamentos`
  ADD CONSTRAINT `gerenciamentos_ibfk_1` FOREIGN KEY (`certificado_id`) REFERENCES `certificados` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

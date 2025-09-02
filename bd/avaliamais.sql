-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/08/2025 às 13:51
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
-- Banco de dados: `avaliamais`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alternativas`
--

CREATE TABLE `alternativas` (
  `id` int(11) NOT NULL,
  `pergunta_id` int(11) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `correta` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `formularios`
--

CREATE TABLE `formularios` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `criado_em` datetime DEFAULT current_timestamp(),
  `criado_por` int(11) DEFAULT NULL,
  `materia` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `formularios`
--

INSERT INTO `formularios` (`id`, `titulo`, `criado_em`, `criado_por`, `materia`) VALUES
(1, 'aaaa', '2025-07-31 01:01:12', NULL, NULL),
(2, 'aaaa', '2025-07-31 01:03:29', NULL, NULL),
(3, 'a', '2025-07-31 01:04:29', NULL, NULL),
(4, 'aaaaa', '2025-08-09 13:18:58', NULL, NULL),
(5, 'a', '2025-08-13 22:41:47', NULL, NULL),
(6, 'Avaliacao Matematica', '2025-08-21 16:39:37', NULL, NULL),
(7, 'aaaaaaaaaaa', '2025-08-21 16:40:10', NULL, NULL),
(8, 'aaaaa', '2025-08-22 01:10:55', NULL, NULL),
(9, 'a', '2025-08-22 01:28:31', NULL, NULL),
(10, 'teste3', '2025-08-22 01:38:33', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `perguntas`
--

CREATE TABLE `perguntas` (
  `id` int(11) NOT NULL,
  `formulario_id` int(11) NOT NULL,
  `enunciado` text NOT NULL,
  `opcoes` text DEFAULT NULL,
  `tipo` enum('objetiva','dissertativa') NOT NULL DEFAULT 'objetiva'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `perguntas`
--

INSERT INTO `perguntas` (`id`, `formulario_id`, `enunciado`, `opcoes`, `tipo`) VALUES
(26, 2, 'a', '[{\"texto\":\"a\",\"correta\":false},{\"texto\":\"a\",\"correta\":true}]', 'objetiva'),
(27, 2, 'a', NULL, 'dissertativa'),
(28, 3, 'a', '[{\"texto\":\"a\",\"correta\":false},{\"texto\":\"a\",\"correta\":true}]', 'objetiva'),
(29, 3, 'a', NULL, 'dissertativa'),
(30, 3, 'a', '[{\"texto\":\"a\",\"correta\":false},{\"texto\":\"a\",\"correta\":true}]', 'objetiva'),
(31, 3, 'a', NULL, 'dissertativa'),
(32, 2, 'a', '[{\"texto\":\"a\",\"correta\":false},{\"texto\":\"a\",\"correta\":true}]', 'objetiva'),
(33, 2, 'a', NULL, 'dissertativa'),
(34, 4, 'a', '[{\"texto\":\"a\",\"correta\":false}]', 'objetiva'),
(35, 4, 'a', NULL, 'dissertativa'),
(36, 4, 'a', '[{\"texto\":\"a\",\"correta\":false}]', 'objetiva'),
(37, 4, 'a', NULL, 'dissertativa');

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores`
--

CREATE TABLE `professores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `escola` varchar(100) NOT NULL,
  `cargo` varchar(50) DEFAULT 'professor',
  `email` varchar(100) NOT NULL,
  `materia` varchar(50) NOT NULL,
  `foto` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `redacoes`
--

CREATE TABLE `redacoes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `tema` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `redacoes`
--

INSERT INTO `redacoes` (`id`, `titulo`, `tema`, `descricao`, `criado_em`, `atualizado_em`) VALUES
(1, 'Redacao 1 Bimestre', 'Sei la', 'bla, bla, bla', '2025-08-22 00:54:18', NULL),
(2, 'redacao teste', NULL, NULL, '2025-08-22 01:10:41', NULL),
(3, 'aaaa', NULL, NULL, '2025-08-22 01:36:18', '2025-08-22 01:36:18'),
(4, 'aaaaa', NULL, NULL, '2025-08-22 01:38:14', '2025-08-22 01:38:14'),
(5, 'a', 'aaaa', 'aaaaa', '2025-08-22 01:43:25', '2025-08-22 01:47:09'),
(6, 'Teste6', NULL, NULL, '2025-08-22 01:47:32', '2025-08-22 01:47:34');

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas_aluno`
--

CREATE TABLE `respostas_aluno` (
  `id` int(11) NOT NULL,
  `aluno_nome` varchar(100) NOT NULL,
  `aluno_curso` varchar(100) NOT NULL,
  `pergunta_id` int(11) NOT NULL,
  `alternativa_id` int(11) NOT NULL,
  `correta` tinyint(1) DEFAULT NULL,
  `data_resposta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `escola` varchar(255) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `cargo` enum('professor','coordenador') NOT NULL DEFAULT 'professor',
  `materias` text NOT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `escola`, `senha_hash`, `cargo`, `materias`, `criado_em`) VALUES
(1, 'Coordenador Inicial', 'coordenador@teste.com', 'Escola Modelo', '$2y$10$ztLUV9rTDVzMJm.Cm1C/1OgRe2dbTPSAuQvzFjDL1FGf9fG.Mx9gG', 'coordenador', 'Matemática, Português', '2025-08-13 23:24:42'),
(2, 'Sofia', 'leo@gmail.com', 'Etec', '$2y$10$09fXxVZFGQEIXLn175HHUOSx3IljLqcqJ68knQyvJlCoD/h4O78DO', 'professor', 'Biologia, EACNT, EAMST, Geografia, Língua Inglesa, Língua Espanhola, Língua Portuguesa', '2025-08-13 23:28:46'),
(3, 'Jonas', 'jonas@gmail.com', 'Anglo', '$2y$10$GVlqcf9nIe1vhkI/1XX1J.Z5u9f97jeK1jO.u0wytyakkjqyQAA2K', 'professor', 'Língua Inglesa, Sociologia, Matemática, Língua Portuguesa', '2025-08-13 23:58:11'),
(4, 'Moises', 'moises@gmail.com', 'Etec', '$2y$10$UWg3LOAK8gkA2ulL92.tF.5qTdjMpvsi6.uGahhRxUiYuU0ZwVzFm', 'coordenador', 'Geografia', '2025-08-21 00:22:11'),
(5, 'teste', 'teste@gmail.com', 'Etec', '$2y$10$heu9Mv.hQEwXG6EHv/DQPecTCWXkbpLu1sEHq2cliMvvEDhG5sxPO', 'professor', 'QTS', '2025-08-21 00:25:13'),
(6, 'Joao', 'joao@gmail.com', 'Anglo', '$2y$10$9t9eAhmRiIsm19AVWqOZJ.csXOu0W5JKKS3v17h9XgHcFXjIxNnjC', 'coordenador', 'Programação Web III', '2025-08-21 00:33:18'),
(7, 'Sofia', 'sofia@gmail.com', 'Etec', '$2y$10$rVbYWlT.ydURL5IeHcVwY.BVJCJOITv0HbfT7/wYiy.797OuyKqUG', 'coordenador', 'Sociologia', '2025-08-21 00:42:05');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alternativas`
--
ALTER TABLE `alternativas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pergunta_id` (`pergunta_id`);

--
-- Índices de tabela `formularios`
--
ALTER TABLE `formularios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_form_criado_por` (`criado_por`),
  ADD KEY `idx_form_materia` (`materia`);

--
-- Índices de tabela `perguntas`
--
ALTER TABLE `perguntas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `professores`
--
ALTER TABLE `professores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `redacoes`
--
ALTER TABLE `redacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_criado_em` (`criado_em`);

--
-- Índices de tabela `respostas_aluno`
--
ALTER TABLE `respostas_aluno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pergunta_id` (`pergunta_id`),
  ADD KEY `alternativa_id` (`alternativa_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alternativas`
--
ALTER TABLE `alternativas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT de tabela `formularios`
--
ALTER TABLE `formularios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `perguntas`
--
ALTER TABLE `perguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `professores`
--
ALTER TABLE `professores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `redacoes`
--
ALTER TABLE `redacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `respostas_aluno`
--
ALTER TABLE `respostas_aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=334;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `alternativas`
--
ALTER TABLE `alternativas`
  ADD CONSTRAINT `alternativas_ibfk_1` FOREIGN KEY (`pergunta_id`) REFERENCES `perguntas` (`id`);

--
-- Restrições para tabelas `respostas_aluno`
--
ALTER TABLE `respostas_aluno`
  ADD CONSTRAINT `respostas_aluno_ibfk_1` FOREIGN KEY (`pergunta_id`) REFERENCES `perguntas` (`id`),
  ADD CONSTRAINT `respostas_aluno_ibfk_2` FOREIGN KEY (`alternativa_id`) REFERENCES `alternativas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

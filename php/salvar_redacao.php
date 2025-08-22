<?php
session_start();
require_once __DIR__ . '/config.php'; // $pdo

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: redacoes.php');
  exit;
}

$titulo = trim($_POST['titulo'] ?? '');
if ($titulo === '') {
  // volta para a listagem com erro simples (ou adapte para flash)
  header('Location: redacoes.php?erro=1');
  exit;
}

try {
  $stmt = $pdo->prepare("INSERT INTO redacoes (titulo, criado_em, atualizado_em) VALUES (:titulo, NOW(), NOW())");
  $stmt->execute([':titulo' => $titulo]);
  $id = $pdo->lastInsertId();

  // redireciona direto para a tela de edição
  header('Location: editar_redacao.php?id='.(int)$id.'&sucesso=1');
  exit;
} catch (PDOException $e) {
  // log de erro se desejar
  header('Location: redacoes.php?erro=2');
  exit;
}

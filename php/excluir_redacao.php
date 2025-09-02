<?php
session_start();
require_once __DIR__ . '/config.php'; // $pdo

// Verificação CSRF
if (!isset($_POST['csrf_token'], $_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
  header('Location: redacoes.php?deleted=0');
  exit;
}

// Valida ID
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
  header('Location: redacoes.php?deleted=0');
  exit;
}

try {
  // Se houver relações, você pode apagar filhos antes (ex.: textos_apoio, parágrafos, etc.)
  // Exemplo (descomente/ajuste conforme seu schema):
  // $pdo->prepare("DELETE FROM redacoes_textos_apoio WHERE redacao_id = :id")->execute([':id' => $id]);
  // $pdo->prepare("DELETE FROM redacoes_paragrafos WHERE redacao_id = :id")->execute([':id' => $id]);

  $stmt = $pdo->prepare("DELETE FROM redacoes WHERE id = :id");
  $ok = $stmt->execute([':id' => $id]);

  if ($ok && $stmt->rowCount() > 0) {
    header('Location: redacoes.php?deleted=1');
  } else {
    header('Location: redacoes.php?deleted=0');
  }
  exit;

} catch (Throwable $e) {
  // Logue se quiser: error_log($e->getMessage());
  header('Location: redacoes.php?deleted=0');
  exit;
}

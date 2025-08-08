<?php
require 'config.php';

$aluno_nome = $_POST['aluno_nome'] ?? '';
$aluno_curso = $_POST['aluno_curso'] ?? '';
$respostas = $_POST['respostas'] ?? [];

if (!$aluno_nome || !$aluno_curso || empty($respostas)) {
  die("Dados incompletos. Verifique o nome, curso e as respostas.");
}

$acertos = 0;

foreach ($respostas as $id_pergunta => $id_alternativa) {
  // Buscar se a resposta está correta
  $stmt = $pdo->prepare("SELECT correta FROM alternativas WHERE id = ?");
  $stmt->execute([$id_alternativa]);
  $correta = $stmt->fetchColumn();

  if ($correta) $acertos++;

  // Salvar a resposta
  $stmt = $pdo->prepare("
    INSERT INTO respostas_aluno 
    (aluno_nome, aluno_curso, pergunta_id, alternativa_id, correta)
    VALUES (?, ?, ?, ?, ?)
  ");
  $stmt->execute([
    $aluno_nome,
    $aluno_curso,
    $id_pergunta,
    $id_alternativa,
    $correta
  ]);
}

echo "<h3>$aluno_nome, você enviou suas respostas com sucesso!" questões.</h3>";

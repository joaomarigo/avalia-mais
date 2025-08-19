<?php
include_once __DIR__ . '/config.php';

$formularioId = $_GET['id'] ?? null;
if (!$formularioId) {
    die("ID do formulário não informado.");
}

$questoesObjetivas = $_POST['questao'] ?? [];
foreach ($questoesObjetivas as $index => $enunciado) {
    if (!is_string($enunciado)) continue;
    $enunciado = trim($enunciado);
    if ($enunciado === '') continue;

    $opcoes = $_POST["opcoes_$index"] ?? [];
    $correta = $_POST["correta_$index"] ?? null;

    $opcoesEstruturadas = [];
    foreach ($opcoes as $i => $opcao) {
        if (!is_string($opcao)) continue;
        $opcao = trim($opcao);
        if ($opcao !== '') {
            $opcoesEstruturadas[] = [
                'texto' => $opcao,
                'correta' => ($correta !== null && (int)$correta === $i)
            ];
        }
    }

    if (count($opcoesEstruturadas) > 0) {
        $stmt = $pdo->prepare("INSERT INTO perguntas (formulario_id, enunciado, opcoes, tipo) VALUES (?, ?, ?, 'objetiva')");
        $stmt->execute([$formularioId, $enunciado, json_encode($opcoesEstruturadas, JSON_UNESCAPED_UNICODE)]);
    }
}

$questoesDissertativas = $_POST['questao_dissertativa'] ?? [];
$respostasDissertativas = $_POST['resposta_dissertativa'] ?? [];

foreach ($questoesDissertativas as $i => $enunciado) {
    if (!is_string($enunciado)) continue;
    $enunciado = trim($enunciado);
    if ($enunciado === '') continue;

    $stmt = $pdo->prepare("INSERT INTO perguntas (formulario_id, enunciado, tipo) VALUES (?, ?, 'dissertativa')");
    $stmt->execute([$formularioId, $enunciado]);
}

header("Location: formularios.php?sucesso=1");
exit;

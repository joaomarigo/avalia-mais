<?php
include_once __DIR__ . '/config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID do formulário não informado.");
}

// Buscar dados do formulário
$stmt = $pdo->prepare("SELECT titulo FROM formularios WHERE id = :id");
$stmt->execute([':id' => $id]);
$formulario = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$formulario) {
    die("Formulário não encontrado.");
}

// Buscar perguntas
$stmt = $pdo->prepare("SELECT enunciado, opcoes, tipo FROM perguntas WHERE formulario_id = :id");
$stmt->execute([':id' => $id]);
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($formulario['titulo']) ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 30px;
        }
        .formulario {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 6px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .pergunta {
            margin-bottom: 25px;
        }
        .pergunta strong {
            display: block;
            margin-bottom: 8px;
            color: #1c3b5a;
        }
        .opcao {
            margin-bottom: 5px;
        }
        textarea {
            width: 100%;
            height: 100px;
            border-radius: 5px;
            padding: 10px;
            resize: vertical;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<div class="formulario">
    <h2><?= htmlspecialchars($formulario['titulo']) ?></h2>

    <?php foreach ($perguntas as $index => $pergunta): ?>
        <div class="pergunta">
            <strong><?= ($index + 1) . '. ' . htmlspecialchars($pergunta['enunciado']) ?></strong>

            <?php if ($pergunta['tipo'] === 'dissertativa'): ?>
                <textarea disabled placeholder="Resposta do aluno..."></textarea>
            <?php else: ?>
                <?php
                    $opcoes = json_decode($pergunta['opcoes'], true);
                    foreach ($opcoes as $i => $opcao):
                ?>
                    <div class="opcao">
                        <label>
                            <input type="radio" disabled>
                            <?= htmlspecialchars($opcao['texto'] ?? $opcao) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>

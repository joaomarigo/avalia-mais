<?php
include_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);

    if (!empty($titulo)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO formularios (titulo) VALUES (:titulo)");
            $stmt->bindParam(':titulo', $titulo);
            $stmt->execute();
            $novoId = $pdo->lastInsertId();

            header("Location: editar_formulario.php?id=$novoId");
            exit;
        } catch (PDOException $e) {
            die("Erro ao salvar formulário: " . $e->getMessage());
        }
    } else {
        $erro = "O título não pode estar vazio.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novo Formulário</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 500px;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #7890a5;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #5e7587;
        }
        .erro {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Criar Novo Formulário</h1>
        <?php if (!empty($erro)): ?>
            <div class="erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="titulo" placeholder="Título do formulário" required>
            <button type="submit">Salvar</button>
        </form>
    </div>
</body>
</html>

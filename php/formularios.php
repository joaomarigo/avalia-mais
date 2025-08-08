<?php
include_once __DIR__ . '/config.php';

$stmt = $pdo->query("SELECT id, titulo, criado_em FROM formularios ORDER BY criado_em DESC");
$formularios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Formulários</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .form-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .form-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 6px rgba(0,0,0,0.1);
            width: 180px;
            text-align: center;
            text-decoration: none;
            color: black;
            transition: transform 0.2s ease;
        }
        .form-card:hover {
            transform: scale(1.05);
            background-color: #f0f0f0;
        }
        .add-button {
            background-color: #5f7a99;
            color: white;
            width: 60px;
            height: 60px;
            font-size: 30px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            margin-bottom: 20px;
        }
        .notify {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            z-index: 9999;
        }
    </style>
</head>
<body>

    <h1 style="margin: 20px;">Formulários</h1>

    <div style="margin: 0 20px;">
        <a class="add-button" href="criar_formulario.php">+</a>
    </div>

    <div class="form-grid" style="margin: 0 20px;">
        <?php foreach ($formularios as $form): ?>
            <a class="form-card" href="visualizar_formulario.php?id=<?= $form['id'] ?>">
                <strong><?= htmlspecialchars($form['titulo'] ?: 'Formulário sem título') ?></strong><br>
                <?= date('d/m/Y H:i', strtotime($form['criado_em'])) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
    <div class="notify" id="notify">Formulário salvo com sucesso!</div>
    <script>
        setTimeout(() => {
            document.getElementById('notify').style.display = 'none';
        }, 3000);
    </script>
    <?php endif; ?>

</body>
</html>


<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1): ?>
<div style="
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    z-index: 9999;
">
    Formulário salvo com sucesso!
</div>
<script>
    setTimeout(() => {
        document.querySelector('div[style*="position: fixed"]').style.display = 'none';
    }, 3000);
</script>
<?php endif; ?>

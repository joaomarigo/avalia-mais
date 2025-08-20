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

                .sidebar {
            width: 200px;
            height: 100vh;
            background-color: #a0c1e8;
            position: fixed;
            padding: 20px 10px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar img {
            width: 130px;
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 15px 0;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
        }

        body {
  margin: 0;
  padding-left: 220px;
}

/* sidebar fixa, ancorada e acima de tudo */
.sidebar {
  width: 200px;
  height: 100vh;
  background-color: #a0c1e8;
  position: fixed;
  top: 0;
  left: 0;
  padding: 20px 10px;
  box-shadow: 2px 0 5px rgba(0,0,0,0.1);
  z-index: 1000;
}
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="../assets/nw.png" alt="Logo Avalia+">
        <ul>
            <li><a href="painelusuarios.php">Inicio</a></li>
            <?php
              if (session_status() === PHP_SESSION_NONE) { session_start(); }
              $cargo = strtolower($_SESSION['cargo'] ?? '');
              if (in_array($cargo, ['coordenador','admin'], true)):
            ?>
              <li><a href="cadastrar.php">Cadastrar</a></li>
            <?php endif; ?>
            <li><a href="formularios.php">Criar Provas</a></li>
            <li><a href="calendario.php">Calendário</a></li>
            <li><a href="comousar.php">Como usar</a></li>
        </ul>
        <a class="logout" href="logout.php" style="color: white; margin-top: 30px; display: inline-block;">
            <img src="../assets/logout.png" alt="Logout" style="width: 20px; vertical-align: middle; margin-right: 5px;">
            Logout
        </a>
    </div>

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

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário Escolar 2025</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
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
        .content {
            margin-left: 200px;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        iframe {
            border: none;
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

    <div class="content">
        <h1>Tutorial de Como Utilizar o Avalia+</h1>
        <h2>Em andamento....</h2>
    </div>
</body>
</html>

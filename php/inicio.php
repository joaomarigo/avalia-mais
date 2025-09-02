<?php
session_start();
require_once __DIR__ . '/config.php'; // $pdo

$cargoSessao = strtolower($_SESSION['cargo'] ?? 'coordenador');

/** 1) tenta pegar o nome da sessão (usa as chaves corretas) */
$nomeUsuario = $_SESSION['nome'] ?? null;

/** 2) se não houver nome na sessão, busca no BD por ID ou e-mail da sessão */
if (!$nomeUsuario) {
  $usuarioId    = $_SESSION['usuario_id'] ?? null;   // <- nome correto
  $usuarioEmail = $_SESSION['email']      ?? null;   // <- nome correto

  if ($usuarioId) {
    $stmt = $pdo->prepare('SELECT nome FROM usuarios WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $usuarioId]);
  } elseif ($usuarioEmail) {
    $stmt = $pdo->prepare('SELECT nome FROM usuarios WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $usuarioEmail]);
  }

  if (!empty($stmt) && ($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
    $nomeUsuario = $row['nome'] ?? null;
    if ($nomeUsuario) {
      $_SESSION['nome'] = $nomeUsuario; // cache com a MESMA chave usada no login
    }
  }
}

/** 3) o que exibir */
$primeiroNome = $nomeUsuario ? explode(' ', trim($nomeUsuario))[0] : 'Coordenador';
$cargoLabel   = ucfirst($cargoSessao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio - Avalia+</title>
  <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Questrial', sans-serif; background-color: #f2f4f8; color: #333; }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 25px 60px;
      width: 100%;
      z-index: 10;
      position: relative;
    }

    .header-left { display: flex; align-items: center; gap: 15px; }
    .hamburger { font-size: 28px; cursor: pointer; color: #1a3a7c; }
    .capelo { width: 60px; }
    .foto-perfil { width: 45px; cursor: pointer; border-radius: 50%; }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: -250px;
      width: 250px;
      height: 100%;
      background-color: #1a3a7c;
      color: white;
      padding: 20px;
      display: flex;
      flex-direction: column;
      transition: left 0.3s ease;
      z-index: 1000;
      box-shadow: 4px 0 15px rgba(0, 0, 0, 0.3);
    }
    .sidebar.active { left: 0; }
    .sidebar-header { display: flex; align-items: center; gap: 10px; margin-bottom: 40px; }
    .sidebar-header .close-btn { font-size: 28px; cursor: pointer; }
    .sidebar-header img { height: 28px; object-fit: contain; }
    .sidebar a { color: white; text-decoration: none; font-size: 18px; margin: 5px 0; padding: 10px; border-radius: 8px; transition: background 0.2s; }
    .sidebar a:hover { background-color: rgba(255, 255, 255, 0.2); }
    .sidebar .logout { margin-top: auto; display: flex; align-items: center; gap: 10px; font-size: 18px; cursor: pointer; color: white; padding: 10px; border-radius: 8px; transition: background 0.2s; }
    .sidebar .logout i { font-size: 20px; }
    .sidebar .logout:hover { background-color: rgba(255,255,255,0.2); }

    .content { max-width: 1000px; margin: 40px auto; padding: 0 20px; display: flex; flex-direction: column; align-items: center; transition: margin-left 0.3s ease; }
    .retangulo { background-color: white; border-radius: 16px; padding: 40px; display: flex; align-items: center; justify-content: space-between; gap: 40px; width: 100%; box-shadow: 0px 6px 18px rgba(0,0,0,0.08); }
    .retangulo .text { flex: 1; }
    .retangulo .text h1 { font-size: 35px; color: #1a3a7c; margin-bottom: 15px; line-height: 1.3; }
    .retangulo .text p { font-size: 30px; color: #444; }
    .retangulo .image { width: 280px; flex-shrink: 0; }
    .retangulo .image img { width: 100%; }

    .buttons { display: flex; justify-content: space-between; align-items: center; margin: 40px 0; width: 100%; max-width: 1000px; }
    .btn {
       flex: 0 0 300px;
       height: 125px;
       padding: 22px;
       font-size: 20px;
       color: white;
       font-weight: 500;
       border: none;
       border-radius: 12px;
       cursor: pointer;
       transition: transform 0.25s ease, box-shadow 0.25s ease;
       text-align: center;
       background-size: cover;
       background-position: center;
       background-repeat: no-repeat;
    }
    .btn.provas { background-image: url('../assets/botao1.png'); }
    .btn.redacoes { background-image: url('../assets/botao3.png'); }
    .btn.gabaritos { background-image: url('../assets/botao4.png'); }
    .btn:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 14px rgba(0,0,0,0.12);
      filter: brightness(1.05);
    }

    .menu-perfil {
      position: fixed;
      top: 0;
      right: -400px;
      width: 350px;
      height: 100vh;
      background: #f8f9fb;
      box-shadow: -4px 0 15px rgba(0,0,0,0.2);
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 30px 20px;
      transition: right 0.3s ease;
      z-index: 2000;
    }
    .menu-perfil.active { right: 0; }
    .menu-perfil .close-btn {
      position: absolute;
      top: 15px;
      left: 15px;
      font-size: 22px;
      color: gray;
      cursor: pointer;
    }
    .menu-perfil .foto-grande {
      width: 120px; height: 120px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 15px;
      background: #1a3a7c;
      margin-top: 40px;
    }
    .menu-perfil .cargo {
      font-size: 16px;
      color: #444;
      margin-bottom: 10px;
    }
    .menu-perfil .btn-editar {
      border: 2px solid #1a3a7c;
      border-radius: 20px;
      padding: 6px 20px;
      background-color: transparent;
      color: #1a3a7c;
      cursor: pointer;
      margin-bottom: 30px;
      transition: 0.2s;
    }
    .menu-perfil .btn-editar:hover { background: #1a3a7c; color: white; }
    .menu-perfil .close-btn { font-size: 30px;}
    .menu-perfil .calendario { width: 100%; margin: 20px 0; text-align: center; }
    .menu-perfil .calendario table { width: 100%; border-collapse: collapse; color: #7a8ba0; }
    .menu-perfil .calendario th, .menu-perfil .calendario td { padding: 6px; font-size: 14px; }
    .menu-perfil .calendario h3 { color: #7a8ba0; margin-bottom: 10px; text-align: center; }
    .menu-perfil .logout {
      margin-top: auto;
      margin-bottom: 10px;
      color: #d33;
      font-size: 16px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .menu-perfil .nome-usuario { font-size: 18px; color: #223; margin-bottom: 4px; }
    .menu-perfil .cargo        { font-size: 13px; color: #7a8ba0; margin-bottom: 10px; }

  </style>
</head>
<body>

  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <i class="fas fa-bars close-btn" id="closeSidebar"></i>
      <img src="../assets/logo_azulclaro.png" alt="Avalia+">
    </div>
    <a href="inicio.php">Inicio</a>
    <?php if ($cargoSessao !== 'professor'): ?>
      <a href="../php/coordenador/painel.php">Painel Coordenador</a>
    <?php endif; ?>
    <a href="comousar.php">Como Usar</a>
    <a href="logout.php" class="logout" id="logoutBtn"><i class="fas fa-door-open"></i> Logout</a>
  </div>

  <div class="header">
    <div class="header-left">
      <i class="fas fa-bars hamburger" id="hamburger"></i>
      <img class="capelo" src="../assets/capelo_azul.png" alt="Ícone formatura">
    </div>
    <img class="foto-perfil" id="perfilBtn" src="../assets/perfil_padrao.png" alt="Perfil">
  </div>

  <div class="menu-perfil" id="menuPerfil">
    <i class="fas fa-times close-btn" id="closePerfil"></i>
    <img src="../assets/perfil_padrao.png" alt="Perfil" class="foto-grande">
    <p class="nome-usuario"><?= htmlspecialchars($primeiroNome) ?></p>
    <p class="cargo"><?= htmlspecialchars($cargoLabel) ?></p>

    <button class="btn-editar" onclick="window.location.href='editar_perfil.php'">Editar</button>

    <div class="calendario" id="calendario"></div>
  </div>

  <div class="content" id="mainContent">
    <div class="retangulo">
      <div class="text">
        <h1>Avalia+ praticidade e eficiência na avaliação escolar.</h1>
        <p>É uma plataforma que facilita a criação e organização de avaliações escolares</p>
      </div>
      <div class="image">
        <img src="../assets/livros.png" alt="Ícones de livros e caderno">
      </div>
    </div>
    <div class="buttons">
      <button class="btn provas" onclick="window.location.href='formularios.php'">Provas</button>
      <button class="btn provas" onclick="window.location.href='redacoes.php'">Redações</button>
      <button class="btn gabaritos">Gabaritos</button>
    </div>
  </div>

  <script>
    const hamburger = document.getElementById('hamburger');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebar = document.getElementById('sidebar');
    hamburger.addEventListener('click', () => sidebar.classList.add('active'));
    closeSidebar.addEventListener('click', () => sidebar.classList.remove('active'));
    document.addEventListener('click', (e) => {
      if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
        sidebar.classList.remove('active');
      }
    });

    const perfilBtn = document.getElementById('perfilBtn');
    const menuPerfil = document.getElementById('menuPerfil');
    const closePerfil = document.getElementById('closePerfil');
    perfilBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      menuPerfil.classList.toggle('active');
    });
    closePerfil.addEventListener('click', () => {
      menuPerfil.classList.remove('active');
    });
    document.addEventListener('click', (e) => {
      if (!menuPerfil.contains(e.target) && !perfilBtn.contains(e.target)) {
        menuPerfil.classList.remove('active');
      }
    });

    function gerarCalendario() {
      const hoje = new Date();
      const mes = hoje.getMonth();
      const ano = hoje.getFullYear();
      const primeiroDia = new Date(ano, mes, 1).getDay();
      const ultimoDia = new Date(ano, mes + 1, 0).getDate();

      const nomesMeses = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho",
                          "Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];

      let html = `<h3>${nomesMeses[mes]} ${ano}</h3>`;
      html += "<table><thead><tr>";
      const diasSemana = ["Dom","Seg","Ter","Qua","Qui","Sex","Sab"];
      diasSemana.forEach(d => html += `<th>${d}</th>`);
      html += "</tr></thead><tbody><tr>";

      for (let i = 0; i < primeiroDia; i++) html += "<td></td>";
      for (let dia = 1; dia <= ultimoDia; dia++) {
        if ((primeiroDia + dia - 1) % 7 === 0 && dia > 1) html += "</tr><tr>";
        html += `<td>${dia}</td>`;
      }
      html += "</tr></tbody></table>";
      document.getElementById("calendario").innerHTML = html;
    }
    gerarCalendario();
  </script>
</body>
</html>

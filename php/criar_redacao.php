<?php
session_start();
require_once __DIR__ . '/config.php'; // $pdo

// Sessão / Nome exibido (mesmo padrão)
$cargoSessao = strtolower($_SESSION['cargo'] ?? 'coordenador');

$nomeUsuario = $_SESSION['nome'] ?? null;
if (!$nomeUsuario) {
  $usuarioId    = $_SESSION['usuario_id'] ?? null;
  $usuarioEmail = $_SESSION['email']      ?? null;

  if ($usuarioId) {
    $stmt = $pdo->prepare('SELECT nome FROM usuarios WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $usuarioId]);
  } elseif ($usuarioEmail) {
    $stmt = $pdo->prepare('SELECT nome FROM usuarios WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $usuarioEmail]);
  }

  if (!empty($stmt) && ($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
    $nomeUsuario = $row['nome'] ?? null;
    if ($nomeUsuario) $_SESSION['nome'] = $nomeUsuario;
  }
}
$primeiroNome = $nomeUsuario ? explode(' ', trim($nomeUsuario))[0] : 'Coordenador';
$cargoLabel   = ucfirst($cargoSessao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Criar Redação - Avalia+</title>
  <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    *{margin:0;padding:0;box-sizing:border-box}
    body{font-family:'Questrial',sans-serif;background:#f2f4f8;color:#333}

    /* Header / Sidebar (mesmo padrão) */
    .header{display:flex;justify-content:space-between;align-items:center;padding:25px 60px;width:100%;position:relative;z-index:10}
    .header-left{display:flex;align-items:center;gap:15px}
    .hamburger{font-size:28px;cursor:pointer;color:#1a3a7c}
    .capelo{width:60px}
    .foto-perfil{width:45px;cursor:pointer;border-radius:50%}

    .sidebar{position:fixed;top:0;left:-250px;width:250px;height:100%;background:#1a3a7c;color:#fff;padding:20px;display:flex;flex-direction:column;transition:left .3s;z-index:1000;box-shadow:4px 0 15px rgba(0,0,0,.3)}
    .sidebar.active{left:0}
    .sidebar-header{display:flex;align-items:center;gap:10px;margin-bottom:40px}
    .sidebar-header .close-btn{font-size:28px;cursor:pointer}
    .sidebar-header img{height:28px;object-fit:contain}
    .sidebar a{color:#fff;text-decoration:none;font-size:18px;margin:5px 0;padding:10px;border-radius:8px;transition:background .2s}
    .sidebar a:hover{background:rgba(255,255,255,.2)}
    .sidebar .logout{margin-top:auto;display:flex;align-items:center;gap:10px;font-size:18px;color:#fff;padding:10px;border-radius:8px;transition:background .2s}
    .sidebar .logout:hover{background:rgba(255,255,255,.2)}

    .menu-perfil{position:fixed;top:0;right:-400px;width:350px;height:100vh;background:#f8f9fb;box-shadow:-4px 0 15px rgba(0,0,0,.2);display:flex;flex-direction:column;align-items:center;padding:30px 20px;transition:right .3s;z-index:2000}
    .menu-perfil.active{right:0}
    .menu-perfil .close-btn{position:absolute;top:15px;left:15px;font-size:22px;color:gray;cursor:pointer}
    .menu-perfil .foto-grande{width:120px;height:120px;border-radius:50%;object-fit:cover;margin-bottom:15px;background:#1a3a7c;margin-top:40px}
    .menu-perfil .nome-usuario{font-size:18px;color:#223;margin-bottom:4px}
    .menu-perfil .cargo{font-size:13px;color:#7a8ba0;margin-bottom:10px}
    .menu-perfil .btn-editar{border:2px solid #1a3a7c;border-radius:20px;padding:6px 20px;background:transparent;color:#1a3a7c;cursor:pointer;margin-bottom:30px;transition:.2s}
    .menu-perfil .btn-editar:hover{background:#1a3a7c;color:#fff}
    .menu-perfil .calendario{width:100%;margin:20px 0;text-align:center}
    .menu-perfil .calendario table{width:100%;border-collapse:collapse;color:#7a8ba0}
    .menu-perfil .calendario th,.menu-perfil .calendario td{padding:6px;font-size:14px}
    .menu-perfil .calendario h3{color:#7a8ba0;margin-bottom:10px;text-align:center}

    .content{max-width:800px;margin:40px auto;padding:0 20px}
    .card{background:#fff;border-radius:16px;padding:28px;box-shadow:0 6px 18px rgba(0,0,0,.08)}
    .card h1{font-size:28px;color:#1a3a7c;margin-bottom:18px}

    .label{font-size:13px;color:#6b7280;margin-bottom:6px;display:block}
    .input_text{width:100%;padding:10px 5px;border:none;border-bottom:2px solid #12337b;background:transparent;outline:none;font-size:20px;color:#12337b;margin-bottom:14px}

    .buttons{margin-top:18px;display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap}
    .btn-primary{height:44px;padding:0 18px;border:2px solid #1a3a7c;border-radius:25px;background:transparent;color:#1a3a7c;font-size:16px;cursor:pointer;transition:.2s}
    .btn-primary:hover{background:#1a3a7c;color:#fff}
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <i class="fas fa-bars close-btn" id="closeSidebar"></i>
      <img src="../assets/logo_azulclaro.png" alt="Avalia+">
    </div>
    <a href="inicio.php">Inicio</a>
    <?php if ($cargoSessao !== 'professor'): ?>
      <a href="cadastrar.php">Cadastro</a>
    <?php endif; ?>
    <a href="comousar.php">Como Usar</a>
    <a href="formularios.php">Provas</a>
    <a href="redacoes.php" class="active">Redações</a>
    <a href="logout.php" class="logout"><i class="fas fa-door-open"></i> Logout</a>
  </div>

  <!-- Header -->
  <div class="header">
    <div class="header-left">
      <i class="fas fa-bars hamburger" id="hamburger"></i>
      <img class="capelo" src="../assets/capelo_azul.png" alt="Ícone formatura">
    </div>
    <img class="foto-perfil" id="perfilBtn" src="../assets/perfil_padrao.png" alt="Perfil">
  </div>

  <!-- Menu Perfil -->
  <div class="menu-perfil" id="menuPerfil">
    <i class="fas fa-times close-btn" id="closePerfil"></i>
    <img src="../assets/perfil_padrao.png" alt="Perfil" class="foto-grande">
    <p class="nome-usuario"><?= htmlspecialchars($primeiroNome) ?></p>
    <p class="cargo"><?= htmlspecialchars($cargoLabel) ?></p>
    <button class="btn-editar" onclick="window.location.href='editar_perfil.php'">Editar</button>
    <div class="calendario" id="calendario"></div>
  </div>

  <!-- Conteúdo -->
  <div class="content">
    <div class="card">
      <h1>Criar Nova Redação</h1>

      <form method="POST" action="salvar_redacao.php">
        <label class="label" for="titulo">Título</label>
        <input class="input_text" id="titulo" type="text" name="titulo" maxlength="255" required
               placeholder="Ex.: Redação – Meio Ambiente e Sustentabilidade">

        <div class="buttons">
          <button type="button" class="btn-primary" onclick="window.location.href='redacoes.php'">Voltar</button>
          <button type="submit" class="btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Sidebar
    const hamburger=document.getElementById('hamburger');
    const closeSidebar=document.getElementById('closeSidebar');
    const sidebar=document.getElementById('sidebar');
    hamburger.addEventListener('click',()=>sidebar.classList.add('active'));
    if (closeSidebar) closeSidebar.addEventListener('click',()=>sidebar.classList.remove('active'));
    document.addEventListener('click',(e)=>{ if(!sidebar.contains(e.target)&&!hamburger.contains(e.target)) sidebar.classList.remove('active'); });

    // Perfil
    const perfilBtn=document.getElementById('perfilBtn');
    const menuPerfil=document.getElementById('menuPerfil');
    const closePerfil=document.getElementById('closePerfil');
    perfilBtn.addEventListener('click',(e)=>{ e.stopPropagation(); menuPerfil.classList.toggle('active'); });
    if (closePerfil) closePerfil.addEventListener('click',()=>menuPerfil.classList.remove('active'));
    document.addEventListener('click',(e)=>{ if(!menuPerfil.contains(e.target)&&!perfilBtn.contains(e.target)) menuPerfil.classList.remove('active'); });

    // Calendário
    function gerarCalendario(){
      const hoje=new Date(), mes=hoje.getMonth(), ano=hoje.getFullYear();
      const primeiroDia=new Date(ano,mes,1).getDay(), ultimoDia=new Date(ano,mes+1,0).getDate();
      const nomesMeses=["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
      const diasSemana=["Dom","Seg","Ter","Qua","Qui","Sex","Sab"];
      let html=`<h3>${nomesMeses[mes]} ${ano}</h3><table><thead><tr>`;
      diasSemana.forEach(d=>html+=`<th>${d}</th>`); html+="</tr></thead><tbody><tr>";
      for(let i=0;i<primeiroDia;i++) html+="<td></td>";
      for(let dia=1;dia<=ultimoDia;dia++){ if((primeiroDia+dia-1)%7===0&&dia>1) html+="</tr><tr>"; html+=`<td>${dia}</td>`; }
      html+="</tr></tbody></table>";
      const cal=document.getElementById("calendario"); if(cal) cal.innerHTML=html;
    }
    gerarCalendario();
  </script>
</body>
</html>

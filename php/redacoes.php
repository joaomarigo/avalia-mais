<?php
session_start();
require_once __DIR__ . '/config.php'; // $pdo

// --- Sessão / Nome exibido (igual formularios.php) ---
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
    if ($nomeUsuario) $_SESSION['nome'] = $nomeUsuario; // cache
  }
}

$primeiroNome = $nomeUsuario ? explode(' ', trim($nomeUsuario))[0] : 'Coordenador';
$cargoLabel   = ucfirst($cargoSessao);

// --- Busca de redações ---
$stmtReds = $pdo->query("SELECT id, titulo, tema, criado_em FROM redacoes ORDER BY criado_em DESC");
$redacoes = $stmtReds->fetchAll(PDO::FETCH_ASSOC);

$sucesso = (isset($_GET['sucesso']) && $_GET['sucesso'] == '1');
$deletedOk  = (isset($_GET['deleted']) && $_GET['deleted'] === '1');
$deletedErr = (isset($_GET['deleted']) && $_GET['deleted'] === '0');

// CSRF token
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redações - Avalia+</title>
  <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Questrial', sans-serif; background-color: #f2f4f8; color: #333; }

    /* Header (igual ao formularios.php) */
    .header {
      display: flex; justify-content: space-between; align-items: center;
      padding: 25px 60px; width: 100%; z-index: 10; position: relative;
    }
    .header-left { display: flex; align-items: center; gap: 15px; }
    .hamburger { font-size: 28px; cursor: pointer; color: #1a3a7c; }
    .capelo { width: 60px; }
    .foto-perfil { width: 45px; cursor: pointer; border-radius: 50%; }

    /* Sidebar deslizante */
    .sidebar {
      position: fixed; top: 0; left: -250px; width: 250px; height: 100%;
      background-color: #1a3a7c; color: white; padding: 20px;
      display: flex; flex-direction: column; transition: left 0.3s ease;
      z-index: 1000; box-shadow: 4px 0 15px rgba(0,0,0,0.3);
    }
    .sidebar.active { left: 0; }
    .sidebar-header { display: flex; align-items: center; gap: 10px; margin-bottom: 40px; }
    .sidebar-header .close-btn { font-size: 28px; cursor: pointer; }
    .sidebar-header img { height: 28px; object-fit: contain; }
    .sidebar a {
      color: white; text-decoration: none; font-size: 18px; margin: 5px 0; padding: 10px;
      border-radius: 8px; transition: background 0.2s;
    }
    .sidebar a:hover { background-color: rgba(255,255,255,0.2); }
    .sidebar a.active { background-color: rgba(255,255,255,0.28); }
    .sidebar .logout {
      margin-top: auto; display: flex; align-items: center; gap: 10px; font-size: 18px; cursor: pointer; color: white; padding: 10px; border-radius: 8px; transition: background 0.2s;
    }
    .sidebar .logout i { font-size: 20px; }
    .sidebar .logout:hover { background-color: rgba(255,255,255,0.2); }

    /* Conteúdo principal */
    .content {
      max-width: 1000px; margin: 40px auto; padding: 0 20px;
      display: flex; flex-direction: column; gap: 24px; transition: margin-left 0.3s ease;
    }

    /* Barra de ações */
    .actions { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
    .actions h1 { font-size: 28px; color: #1a3a7c; }
    .btn-primary {
      height: 44px; padding: 0 18px; border: none; border-radius: 10px; cursor: pointer;
      background: #1a3a7c; color: #fff; font-size: 16px; transition: transform .2s, box-shadow .2s, filter .2s;
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 14px rgba(0,0,0,0.12); filter: brightness(1.05); }

    /* Cards (idêntico aos do formularios.php) */
    .grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
    @media (max-width: 980px) { .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 640px) { .grid { grid-template-columns: 1fr; } }

    .card {
      background: #fff; border-radius: 16px; padding: 18px;
      box-shadow: 0px 6px 18px rgba(0,0,0,0.08);
      display: flex; flex-direction: column; gap: 10px;
    }
    .card-title {
      font-size: 18px; color: #1a3a7c; font-weight: 600; line-height: 1.2;
      text-decoration: none;
    }
    .card small { color: #6b7280; }
    .muted { color:#6b7280; font-size:13px; }

    .card a.more {
      margin-top: 6px; align-self: flex-start; text-decoration: none;
      font-size: 14px; color: #1a3a7c; padding: 6px 10px; border-radius: 8px; border: 1px solid #1a3a7c;
      transition: background .2s, color .2s;
    }
    .card a.more:hover { background:#1a3a7c; color:#fff; }

    /* Menu Perfil lateral direito */
    .menu-perfil {
      position: fixed; top: 0; right: -400px; width: 350px; height: 100vh; background: #f8f9fb;
      box-shadow: -4px 0 15px rgba(0,0,0,0.2); display: flex; flex-direction: column; align-items: center;
      padding: 30px 20px; transition: right 0.3s ease; z-index: 2000;
    }
    .menu-perfil.active { right: 0; }
    .menu-perfil .close-btn { position: absolute; top: 15px; left: 15px; font-size: 22px; color: gray; cursor: pointer; }
    .menu-perfil .foto-grande { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; background: #1a3a7c; margin-top: 40px; }
    .menu-perfil .nome-usuario { font-size: 18px; color: #223; margin-bottom: 4px; }
    .menu-perfil .cargo { font-size: 13px; color: #7a8ba0; margin-bottom: 10px; }
    .menu-perfil .btn-editar {
      border: 2px solid #1a3a7c; border-radius: 20px; padding: 6px 20px; background-color: transparent; color: #1a3a7c;
      cursor: pointer; margin-bottom: 30px; transition: 0.2s;
    }
    .menu-perfil .btn-editar:hover { background: #1a3a7c; color: white; }
    .menu-perfil .calendario { width: 100%; margin: 20px 0; text-align: center; }
    .menu-perfil .calendario table { width: 100%; border-collapse: collapse; color: #7a8ba0; }
    .menu-perfil .calendario th, .menu-perfil .calendario td { padding: 6px; font-size: 14px; }
    .menu-perfil .calendario h3 { color: #7a8ba0; margin-bottom: 10px; text-align: center; }

    /* Toast de sucesso */
    .notify {
      position: fixed; top: 20px; right: 20px; background-color: #4CAF50; color: white;
      padding: 12px 20px; border-radius: 8px; box-shadow: 0 8px 20px rgba(0,0,0,.15);
      z-index: 2500; font-size: 14px;
    }

    /* Botão lixeira */
.btn-delete {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 14px;
  padding: 6px 10px;
  border-radius: 8px;
  border: 1px solid #dc2626;
  color: #dc2626;
  background: #fff;
  text-decoration: none;
  cursor: pointer;
  transition: transform .2s, box-shadow .2s, background .2s, color .2s, border-color .2s;
}
.btn-delete:hover {
  background: #dc2626;
  color: #fff;
  transform: translateY(-2px);
  box-shadow: 0 6px 14px rgba(220,38,38,0.25);
}

/* Container da confirmação (toast/modal) */
.confirm-wrap {
  position: fixed;
  right: 20px;
  bottom: 20px;
  z-index: 3000;
  display: none;
}
.confirm-card {
  width: 360px;
  max-width: calc(100vw - 40px);
  background: #ffffff;
  border-left: 6px solid #dc2626;
  border-radius: 14px;
  padding: 16px 16px 12px 16px;
  box-shadow: 0 14px 40px rgba(0,0,0,0.18);
  animation: slideUp .25s ease-out forwards;
}
.confirm-title {
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 8px;
  display: flex; align-items: center; gap: 8px;
}
.confirm-text {
  color: #4b5563;
  font-size: 14px;
  line-height: 1.45;
  margin-bottom: 12px;
}
.confirm-actions {
  display: flex; justify-content: flex-end; gap: 8px;
}
.confirm-btn {
  height: 38px;
  padding: 0 14px;
  border-radius: 10px;
  border: none;
  cursor: pointer;
  font-size: 14px;
}
.confirm-cancel {
  background: #f3f4f6; color: #374151;
}
.confirm-cancel:hover { filter: brightness(0.96); }
.confirm-yes {
  background: #dc2626; color: #fff;
  box-shadow: 0 6px 14px rgba(220,38,38,0.25);
}
.confirm-yes:hover { filter: brightness(1.05); }

@keyframes slideUp {
  from { transform: translateY(14px); opacity: 0; }
  to   { transform: translateY(0);     opacity: 1; }
}

/* Toast de sucesso de exclusão */
.notify.success {
  background-color: #16a34a;
}
.notify.error {
  background-color: #dc2626;
}


/* Estilo base para botões secundários (Abrir/Excluir) */
.btn-secondary {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  height: 30px;          /* altura fixa igual */
  padding: 0 14px;       /* mesmo padding horizontal */
  font-size: 14px;
  border-radius: 8px;
  border: 1px solid #1a3a7c;
  background: #fff;
  cursor: pointer;
  text-decoration: none;
  transition: background .2s, color .2s;
}

/* Abrir */
.btn-secondary.open {
  color: #1a3a7c;
}
.btn-secondary.open:hover {
  background: #1a3a7c;
  color: #fff;
}

/* Excluir */
.btn-secondary.delete {
  border-color: #dc2626;
  color: #dc2626;
}
.btn-secondary.delete:hover {
  background: #dc2626;
  color: #fff;
}

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
      <a href="cadastrar.php">Painel Coordenador</a>
    <?php endif; ?>
    <a href="comousar.php">Como Usar</a>

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
  <div class="content" id="mainContent">
    <div class="actions">
      <h1>Redações</h1>
      <button class="btn-primary" onclick="window.location.href='criar_redacao.php'">
        <i class="fa-solid fa-plus" style="margin-right:6px;"></i> Nova Redação
      </button>
    </div>

    <div class="grid">
      <?php if (!empty($redacoes)): ?>
        <?php foreach ($redacoes as $r): ?>
          <div class="card">
            <a class="card-title" href="visualizar_redacao.php?id=<?= (int)$r['id'] ?>">
              <?= htmlspecialchars($r['titulo'] ?: 'Redação sem título') ?>
            </a>
            <?php if (!empty($r['tema'])): ?>
              <div class="muted">Tema: <?= htmlspecialchars($r['tema']) ?></div>
            <?php endif; ?>
            <small>Criada em: <?= htmlspecialchars(date('d/m/Y H:i', strtotime($r['criado_em']))) ?></small>
           <div style="display:flex; gap:8px; align-items:center; margin-top:6px;">
  <a class="btn-secondary open" href="visualizar_redacao.php?id=<?= (int)$r['id'] ?>">Abrir</a>

  <button class="btn-secondary delete" type="button"
          onclick="confirmarExclusao(<?= (int)$r['id'] ?>, '<?= htmlspecialchars($r['titulo'] ?: 'Redação sem título', ENT_QUOTES) ?>')">
    Excluir
  </button>
</div>


          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="card">
          <div class="card-title">Nenhuma redação cadastrada</div>
          <small>Clique em “Nova Redação” para criar a primeira.</small>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($sucesso): ?>
    <div class="notify" id="notify">Redação salva com sucesso!</div>
    <script>
      setTimeout(() => { const n = document.getElementById('notify'); if (n) n.style.display = 'none'; }, 3000);
    </script>
  <?php endif; ?>

<?php if ($deletedOk): ?>
  <div class="notify success" id="notify">Redação excluída com sucesso!</div>
  <script>setTimeout(() => { const n = document.getElementById('notify'); if (n) n.style.display = 'none'; }, 3000);</script>
<?php endif; ?>

<?php if ($deletedErr): ?>
  <div class="notify error" id="notify">Não foi possível excluir a redação.</div>
  <script>setTimeout(() => { const n = document.getElementById('notify'); if (n) n.style.display = 'none'; }, 3500);</script>
<?php endif; ?>


  <script>
    // Sidebar toggle (igual ao formularios.php)
    const hamburger = document.getElementById('hamburger');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebar = document.getElementById('sidebar');
    hamburger.addEventListener('click', () => sidebar.classList.add('active'));
    closeSidebar.addEventListener('click', () => sidebar.classList.remove('active'));
    document.addEventListener('click', (e) => {
      if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) sidebar.classList.remove('active');
    });

    // Menu perfil toggle
    const perfilBtn = document.getElementById('perfilBtn');
    const menuPerfil = document.getElementById('menuPerfil');
    const closePerfil = document.getElementById('closePerfil');
    perfilBtn.addEventListener('click', (e) => { e.stopPropagation(); menuPerfil.classList.toggle('active'); });
    if (closePerfil) closePerfil.addEventListener('click', () => menuPerfil.classList.remove('active'));
    document.addEventListener('click', (e) => {
      if (!menuPerfil.contains(e.target) && !perfilBtn.contains(e.target)) menuPerfil.classList.remove('active');
    });

    // Calendário simples
    function gerarCalendario() {
      const hoje = new Date();
      const mes = hoje.getMonth();
      const ano = hoje.getFullYear();
      const primeiroDia = new Date(ano, mes, 1).getDay();
      const ultimoDia = new Date(ano, mes + 1, 0).getDate();
      const nomesMeses = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
      const diasSemana = ["Dom","Seg","Ter","Qua","Qui","Sex","Sab"];

      let html = `<h3>${nomesMeses[mes]} ${ano}</h3>`;
      html += "<table><thead><tr>";
      diasSemana.forEach(d => html += `<th>${d}</th>`);
      html += "</tr></thead><tbody><tr>";

      for (let i = 0; i < primeiroDia; i++) html += "<td></td>";
      for (let dia = 1; dia <= ultimoDia; dia++) {
        if ((primeiroDia + dia - 1) % 7 === 0 && dia > 1) html += "</tr><tr>";
        html += `<td>${dia}</td>`;
      }
      html += "</tr></tbody></table>";
      const cal = document.getElementById("calendario");
      if (cal) cal.innerHTML = html;
    }
    gerarCalendario();

     let alvoExcluirId = null;
  let alvoExcluirTitulo = '';

  function confirmarExclusao(id, titulo) {
    alvoExcluirId = id;
    alvoExcluirTitulo = titulo || '';
    const wrap = document.getElementById('confirmWrap');
    const desc = document.getElementById('confirmDesc');
    if (desc) {
      const nome = (alvoExcluirTitulo || 'esta redação');
      desc.textContent = `Tem certeza que deseja excluir “${nome}”? Esta ação não poderá ser desfeita.`;
    }
    wrap.style.display = 'block';
    // Acessibilidade: focar botão “Excluir”
    setTimeout(() => document.getElementById('btnYes')?.focus(), 10);
  }

  function fecharConfirmacao() {
    const wrap = document.getElementById('confirmWrap');
    wrap.style.display = 'none';
    alvoExcluirId = null;
    alvoExcluirTitulo = '';
  }

  // Delegação: funciona mesmo que os botões sejam renderizados depois do script
document.addEventListener('click', (e) => {
  // Botão Cancelar
  if (e.target.closest('#btnCancel')) {
    e.preventDefault();
    fecharConfirmacao();
    return;
  }

  // Botão Excluir
  if (e.target.closest('#btnYes')) {
    e.preventDefault();
    if (!alvoExcluirId) return;
    const campoId = document.getElementById('excluirId');
    if (campoId) campoId.value = alvoExcluirId;
    const form = document.getElementById('formExcluir');
    if (form) form.submit();
    return;
  }
});

  // Fechar ao clicar fora
  document.addEventListener('click', (e) => {
    const wrap = document.getElementById('confirmWrap');
    const card = wrap?.querySelector('.confirm-card');
    if (wrap && wrap.style.display === 'block' && card && !card.contains(e.target) && !e.target.closest('.btn-delete')) {
      fecharConfirmacao();
    }
  });

  // Fechar com ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') fecharConfirmacao();
  });
  </script>
  <!-- Confirmação animada -->
<div class="confirm-wrap" id="confirmWrap" aria-hidden="true">
  <div class="confirm-card" role="dialog" aria-modal="true" aria-labelledby="confirmTitle" aria-describedby="confirmDesc">
    <div class="confirm-title" id="confirmTitle">
      <i class="fa-solid fa-triangle-exclamation" style="color:#dc2626;"></i>
      Confirmar exclusão
    </div>
    <div class="confirm-text" id="confirmDesc">
      Tem certeza que deseja excluir esta redação?
    </div>
    <div class="confirm-actions">
      <button class="confirm-btn confirm-cancel" id="btnCancel">Cancelar</button>
      <button class="confirm-btn confirm-yes" id="btnYes">Excluir</button>
    </div>
  </div>
</div>

<!-- Form oculto de exclusão -->
<form id="formExcluir" action="excluir_redacao.php" method="POST" style="display:none;">
  <input type="hidden" name="id" id="excluirId">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
</form>

</body>
</html>

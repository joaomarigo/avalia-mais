<?php
// ---------- BOOTSTRAP PHP ----------
session_start();
require_once __DIR__ . '/config.php';

// BLOQUEIO: professores não podem acessar esta página
if (strtolower($_SESSION['cargo'] ?? '') === 'professor') {
    header('Location: inicio.php');
    exit;
}

// FLASH (mensagem pós-redirect)
$mensagemOk   = '';
$mensagemErro = '';
if (!empty($_SESSION['flash_popup'])) {
    $mensagemOk = $_SESSION['flash_popup'];
    unset($_SESSION['flash_popup']);
}

// polyfill str_starts_with (PHP < 8)
if (!function_exists('str_starts_with')) {
  function str_starts_with($h,$n){return 0===strncmp($h,$n,strlen($n));}
}

// Helper: redireciona para cadastrar.php com flash
function redirect_to_cadastrar_with_flash(string $msg): void {
    $_SESSION['flash_popup'] = $msg;

    // Path absoluto baseado no diretório do script atual (ex.: /proj_avalia/php)
    $dir = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    $url = $dir . '/cadastrar.php';

    if (!headers_sent()) {
        header('Location: ' . $url);
        exit;
    }
    // Fallbacks
    echo '<!DOCTYPE html><meta charset="utf-8"><title>Redirecionando…</title>';
    echo '<script>location.href=' . json_encode($url) . ';</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($url, ENT_QUOTES) . '"></noscript>';
    exit;
}

// CSRF
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// --------- PROCESSA CADASTRO (POST) ---------
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['__form']) &&
    $_POST['__form'] === 'cadastro_prof'
) {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $mensagemErro = 'Token de segurança inválido. Recarregue a página.';
    } else {
        $nome     = trim($_POST['nome']    ?? '');
        $email    = trim($_POST['email']   ?? '');
        $escola   = trim($_POST['escola']  ?? '');
        $senha    = $_POST['senha']        ?? '';
        $cargo    = trim($_POST['cargo']   ?? 'Professor');
        $materias = trim($_POST['materias']?? '');

        $cargo = strtolower($cargo);
        $erros = [];
        if ($nome === '')                                        $erros[] = 'Informe o nome.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = 'E-mail inválido.';
        if ($escola === '')                                      $erros[] = 'Informe a escola.';
        if ($senha === '' || strlen($senha) < 6)                 $erros[] = 'Senha deve ter ao menos 6 caracteres.';
        if (!in_array($cargo, ['professor','coordenador'], true))$erros[] = 'Cargo inválido.';
        if ($materias === '')                                    $erros[] = 'Selecione ao menos uma matéria.';

        if (!$erros) {
            try {
                $st = $pdo->prepare("SELECT id FROM usuarios WHERE email = :e LIMIT 1");
                $st->execute([':e' => $email]);
                if ($st->fetch()) {
                    $mensagemErro = 'Já existe um usuário com esse e-mail.';
                } else {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO usuarios (nome, email, escola, senha_hash, cargo, materias)
                            VALUES (:nome, :email, :escola, :senha_hash, :cargo, :materias)";
                    $ins = $pdo->prepare($sql);
                    $ins->bindValue(':nome',       $nome);
                    $ins->bindValue(':email',      $email);
                    $ins->bindValue(':escola',     $escola);
                    $ins->bindValue(':senha_hash', $senha_hash);
                    $ins->bindValue(':cargo',      $cargo);
                    $ins->bindValue(':materias',   $materias);
                    $ins->execute();

                    // Gira o token para o próximo POST e redireciona (PRG)
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    redirect_to_cadastrar_with_flash('Usuário cadastrado com sucesso!');
                }
            } catch (PDOException $e) {
                $mensagemErro = 'Erro ao salvar no banco. Verifique a tabela "usuarios" e suas colunas.';
            }
        } else {
            $mensagemErro = 'Erros: ' . implode(' ', $erros);
        }
    }
}

// Papel atual (para controle de UI, se precisar)
$cargoSessao = strtolower($_SESSION['cargo'] ?? '');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - Avalia+</title>
  <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Questrial', sans-serif; background-color: #f2f4f8; color: #333; }

    .header { display:flex; justify-content:space-between; align-items:center; padding:25px 60px; width:100%; z-index:10; }
    .header-left { display:flex; align-items:center; gap:15px; }
    .hamburger { font-size:28px; cursor:pointer; color:#1a3a7c; }
    .capelo { width:60px; }
    .foto-perfil { width:45px; cursor:pointer; }

    .sidebar { position:fixed; top:0; left:-250px; width:250px; height:100%; background:#1a3a7c; color:#fff; padding:20px; display:flex; flex-direction:column; transition:left .3s ease; z-index:1000; box-shadow:4px 0 15px rgba(0,0,0,.3); }
    .sidebar.active { left:0; }
    .sidebar-header { display:flex; align-items:center; gap:10px; margin-bottom:40px; }
    .sidebar-header .close-btn { font-size:28px; cursor:pointer; }
    .sidebar-header img { height:28px; object-fit:contain; }
    .sidebar a { color:#fff; text-decoration:none; font-size:18px; margin:5px 0; padding:10px; border-radius:8px; transition: background .2s; }
    .sidebar a:hover { background-color: rgba(255, 255, 255, 0.2); }
    .sidebar .logout { margin-top:auto; display:flex; align-items:center; gap:10px; font-size:18px; cursor:pointer; color:#fff; padding:10px; border-radius:8px; transition: background .2s; }
    .sidebar .logout i { font-size:20px; }
    .sidebar .logout:hover { background-color: rgba(255,255,255,0.2); }

    .content { max-width: 1000px; margin: 40px auto; padding: 0 20px; display:flex; flex-direction:column; align-items:center; }

    .notificacao { width:100%; max-width:1000px; margin:18px auto 0; padding:12px 16px; border-radius:10px; font-size:15px; }
    .notificacao.ok  { background:#e8f7ef; border:1px solid #b5e2c5; color:#1d6b3a; }
    .notificacao.err { background:#fdeaea; border:1px solid #f1b5b5; color:#7a1f1f; }

    .card { width:100%; max-width:1000px; background:#fff; border-radius:16px; box-shadow:0 6px 18px rgba(0,0,0,.08); padding:28px; margin-top:28px; }
    .card h2 { color:#1a3a7c; font-size:26px; margin-bottom:16px; border-bottom:2px solid #cfd6e4; padding-bottom:8px; }

    form.cadastro-grid { display:grid; grid-template-columns: repeat(2, minmax(240px, 1fr)); gap:22px 28px; }
    @media (max-width:860px){ form.cadastro-grid { grid-template-columns: 1fr; } }

    .input_cadastro, .select_cadastro, .materias-container {
      width:100%; border:none; outline:none; background:transparent;
      border-bottom:2px solid #1a3a7c; padding:10px 6px; font-size:16px; color:#1a3a7c;
    }
    .input_cadastro::placeholder { color:#1a3a7c; opacity:.65; }
    .select_cadastro {
      appearance:none; -webkit-appearance:none; -moz-appearance:none; cursor:pointer;
      background-image:url("data:image/svg+xml,%3Csvg fill='gray' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3C/svg%3E");
      background-repeat:no-repeat; background-position:right 10px center; background-size:20px;
    }

    .materias-wrapper { grid-column: 1 / -1; }
    .materias-container { display:flex; flex-wrap:wrap; gap:8px; min-height:44px; align-items:center; cursor:pointer; color:#1a3a7c; }
    .chip { background:#e6edf5; color:#1a3a7c; padding:6px 12px; border-radius:20px; display:flex; align-items:center; font-size:14px; }
    .chip span { margin-left:8px; cursor:pointer; font-weight:bold; }

    .cadastro-btn { grid-column:1 / -1; justify-self:start; margin-top:6px; height:42px; width:180px; font-size:18px; background:transparent; color:#1a3a7c; border:2px solid #1a3a7c; border-radius:25px; cursor:pointer; transition:.25s; }
    .cadastro-btn:hover { background:#1a3a7c; color:#fff; }

    .modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); align-items:center; justify-content:center; z-index:1500; }
    .modal-content { background:#fff; border-radius:12px; width:520px; max-width:92vw; max-height:80vh; overflow:auto; padding:20px; }
    .modal-content h3 { text-align:center; color:#1a3a7c; margin-bottom:12px; }
    .materia-btn { display:inline-block; background:#e9eef7; color:#1a3a7c; padding:8px 14px; border-radius:20px; margin:6px; cursor:pointer; font-size:14px; }
    .materia-btn.ativo { background:#bcd0ff; }
  </style>
</head>
<body>
  <!-- SIDEBAR -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <i class="fas fa-bars close-btn" id="closeSidebar"></i>
      <img src="../assets/logo_azulclaro.png" alt="Avalia+">
    </div>
    <a href="inicio.php">Início</a>
    <a href="comousar.php">Como Usar</a>
    <a href="logout.php" class="logout" id="logoutBtn">
      <i class="fas fa-door-open"></i> Logout
    </a>
  </div>

  <!-- HEADER -->
  <div class="header">
    <div class="header-left">
      <i class="fas fa-bars hamburger" id="hamburger"></i>
      <img class="capelo" src="../assets/capelo_azul.png" alt="Ícone formatura">
    </div>
    <img class="foto-perfil" src="../assets/perfil_padrao.png" alt="Perfil">
  </div>

  <!-- CONTEÚDO -->
  <div class="content" id="mainContent">

    <!-- ALERTAS -->
    <?php if ($mensagemOk): ?>
      <div class="notificacao ok"><?= htmlspecialchars($mensagemOk) ?></div>
    <?php endif; ?>
    <?php if ($mensagemErro): ?>
      <div class="notificacao err"><?= htmlspecialchars($mensagemErro) ?></div>
    <?php endif; ?>

    <!-- CADASTRAR PROFESSOR -->
    <section class="card" id="blocoCadastro">
      <h2>Cadastrar Professor</h2>

      <form id="formCadastro" class="cadastro-grid" method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" enctype="multipart/form-data">
        <input type="hidden" name="__form" value="cadastro_prof">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="materias" id="materiasHidden" value="">

        <input class="input_cadastro" type="text"     name="nome"   placeholder="Nome Completo" required>
        <input class="input_cadastro" type="email"    name="email"  placeholder="Email" required>
        <input class="input_cadastro" type="text"     name="escola" placeholder="Escola" required>
        <input class="input_cadastro" type="password" name="senha"  placeholder="Senha (mín. 6)" required>

        <select class="select_cadastro" id="cargos" name="cargo" required>
          <option value="" disabled selected>Cargo</option>
          <option value="Professor">Professor</option>
          <option value="Coordenador">Coordenador</option>
        </select>

        <div class="materias-wrapper">
          <div class="materias-container" id="materiasSelecionadas">Matérias (clique para selecionar)</div>
        </div>

        <button class="cadastro-btn" type="submit">Cadastrar</button>
      </form>
    </section>
  </div>

  <!-- MODAL MATÉRIAS -->
  <div class="modal" id="modalMaterias">
    <div class="modal-content">
      <h3>Matérias</h3>
      <div id="listaMaterias">
        <div class="materia-btn">Biologia</div>
        <div class="materia-btn">EACNT</div>
        <div class="materia-btn">EAMST</div>
        <div class="materia-btn">Geografia</div>
        <div class="materia-btn">IPSS</div>
        <div class="materia-btn">Língua Espanhola</div>
        <div class="materia-btn">Língua Inglesa</div>
        <div class="materia-btn">Língua Portuguesa</div>
        <div class="materia-btn">Matemática</div>
        <div class="materia-btn">PAM</div>
        <div class="materia-btn">TCC</div>
        <div class="materia-btn">Programação Web III</div>
        <div class="materia-btn">QTS</div>
        <div class="materia-btn">Sociologia</div>
        <div class="materia-btn">Sistemas Embarcados</div>
      </div>
    </div>
  </div>

  <script>
    // Sidebar
    const hamburger    = document.getElementById('hamburger');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebar      = document.getElementById('sidebar');
    hamburger.addEventListener('click', () => sidebar.classList.add('active'));
    closeSidebar.addEventListener('click', () => sidebar.classList.remove('active'));
    document.addEventListener('click', (e) => {
      if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) sidebar.classList.remove('active');
    });

    // Matérias (chips + modal)
    const materiasContainer = document.getElementById("materiasSelecionadas");
    const modalMaterias     = document.getElementById("modalMaterias");
    const listaMaterias     = document.getElementById("listaMaterias");
    const materiasHidden    = document.getElementById("materiasHidden");

    let materiasSelecionadas = [];
    materiasContainer.addEventListener("click", () => { modalMaterias.style.display = "flex"; });
    listaMaterias.addEventListener("click", e => {
      if (e.target.classList.contains("materia-btn")) {
        const materia = e.target.textContent.trim();
        e.target.classList.toggle("ativo");
        if (materiasSelecionadas.includes(materia)) {
          materiasSelecionadas = materiasSelecionadas.filter(m => m !== materia);
        } else {
          materiasSelecionadas.push(materia);
        }
        atualizarChips();
      }
    });

    function atualizarChips() {
      materiasContainer.innerHTML = "";
      materiasSelecionadas.forEach(m => {
        const chip = document.createElement("div");
        chip.className = "chip";
        chip.innerHTML = `${m} <span>&times;</span>`;
        chip.querySelector("span").addEventListener("click", (ev) => {
          ev.stopPropagation();
          materiasSelecionadas = materiasSelecionadas.filter(mat => mat !== m);
          document.querySelectorAll(".materia-btn").forEach(btn => {
            if (btn.textContent.trim() === m) btn.classList.remove("ativo");
          });
          atualizarChips();
        });
        materiasContainer.appendChild(chip);
      });
      if (materiasSelecionadas.length === 0) {
        materiasContainer.textContent = "Matérias (clique para selecionar)";
      }
      materiasHidden.value = materiasSelecionadas.join(", ");
    }

    modalMaterias.addEventListener("click", e => {
      if (e.target === modalMaterias) modalMaterias.style.display = "none";
    });

    document.getElementById('formCadastro').addEventListener('submit', function() {
      materiasHidden.value = (materiasHidden.value || '').trim();
    });
  </script>
</body>
</html>

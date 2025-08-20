<?php
session_start();
require_once __DIR__ . '/config.php';

if (!function_exists('str_starts_with')) {
  function str_starts_with($h,$n){return 0===strncmp($h,$n,strlen($n));}
}

function redirect_back_with_flash(string $msg): void {
    $_SESSION['flash_popup'] = $msg;
    setcookie('flash_popup', rawurlencode($msg), [
        'expires'  => time()+60,
        'path'     => '/',
        'httponly' => false,
        'samesite' => 'Lax',
    ]);

    $fallbacks = ['/php/painelusuarios.php', '/php/inicio.php', '/login.php'];

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $self   = $scheme . '://' . $host . ($_SERVER['REQUEST_URI'] ?? '/');
    $back   = '';

    if (!empty($_SERVER['HTTP_REFERER'])) {
        $ref = $_SERVER['HTTP_REFERER'];
        $sameOrigin = str_starts_with($ref, $scheme . '://' . $host . '/');
        $notSelf    = (parse_url($ref, PHP_URL_PATH) !== parse_url($self, PHP_URL_PATH));
        if ($sameOrigin && $notSelf) $back = $ref;
    }
    if (!$back) $back = $fallbacks[0];

    if (!headers_sent()) { header('Location: '.$back); exit; }

    echo '<!DOCTYPE html><meta charset="utf-8"><title>Redirecionando…</title>';
    echo '<script>location.href='.json_encode($back).';</script>';
    echo '<noscript><meta http-equiv="refresh" content="0;url='.htmlspecialchars($back,ENT_QUOTES).'"></noscript>';
    exit;
}

if (!isset($_SESSION['usuario_id'], $_SESSION['cargo']) || strtolower($_SESSION['cargo']) !== 'coordenador') {
    redirect_back_with_flash('Acesso negado: apenas coordenadores podem cadastrar usuários.');
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$mensagemErro = '';
$mensagemOk   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

                    $mensagemOk = 'Usuário cadastrado com sucesso!';
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }
            } catch (PDOException $e) {
                $mensagemErro = 'Erro ao salvar no banco. Verifique a tabela "usuarios" e suas colunas.';
            }
        } else {
            $mensagemErro = 'Erros: ' . implode(' ', $erros);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Professor</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background-color: #ffffff; }
        .sidebar { width: 200px; height: 100vh; background-color: #a0c1e8; position: fixed; left:0; top:0; padding: 20px 10px; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar img { width: 130px; margin-bottom: 20px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { margin: 15px 0; }
        .sidebar ul li a { text-decoration: none; color: white; font-size: 16px; }
        .logout { color: white; margin-top: 30px; display: inline-block; }
        .logout img { width: 20px; vertical-align: middle; margin-right: 5px; }

        header { margin-left: 200px; padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; }
        header .logo { width: 160px; }

        /* CENTRALIZAÇÃO E TAMANHO PROPORCIONAL */
        .page {
          margin-left: 200px;           /* compensa a sidebar fixa */
          min-height: 100vh;            /* ocupa altura total */
          display: flex;
          align-items: center;          /* centraliza vertical */
          justify-content: center;      /* centraliza horizontal */
          padding: 24px;                /* respiro nas bordas */
          background: #ffffff;
        }

        /* Notificações centralizadas */
        .notificacao { 
          margin: 16px auto; 
          max-width: 900px;
          padding: 10px 14px; 
          border-radius: 8px; 
          font-size: 14px; 
        }
        .notificacao.ok { background:#e8f7ef; border:1px solid #b5e2c5; color:#1d6b3a; }
        .notificacao.err{ background:#fdeaea; border:1px solid #f1b5b5; color:#7a1f1f; }

        /* Card do formulário */
        .login-box {
          width: 100%;
          max-width: 900px;             /* limite para não ficar gigante */
          background: #fff;
        }
        .login-box h2 { color: #23405a; font-size: 30px; font-family: 'Questrial', sans-serif; }

        .titulo {
          width: 100%;
          margin: 0 0 24px 0;
          border-bottom: 2px solid #768898;
        }

        /* Grid responsivo do formulário */
        form {
          display: grid;
          grid-template-columns: repeat(2, minmax(260px, 1fr));
          gap: 24px 32px;
        }

        /* Campos fluindo no grid (sem larguras fixas) */
        .input_cadastro,
        .select_cadastro,
        .materias-container {
          width: 100%;
          max-width: 100%;
        }

        .input_cadastro {
          padding: 8px 6px;
          margin: 0;
          border: none;
          border-bottom: 2px solid #23405a;
          background: transparent;
          outline: none;
          font-size: 16px;
          color: #23405a;
          font-family: 'Poppins', sans-serif;
        }
        .input_cadastro::placeholder { color: #23405a; opacity: 0.7; font-weight: 300; }

        .select_cadastro {
          height: 40px;
          margin: 0;
          padding: 4px 36px 4px 6px;
          font-size: 16px;
          color: #23405a;
          background-color: #ffffff;
          border: none;
          outline: none;
          border-bottom: 2px solid #23405a;
          font-family: 'Poppins', sans-serif;
          appearance: none !important;
          -webkit-appearance: none !important;
          -moz-appearance: none !important;
          background-image: url("data:image/svg+xml,%3Csvg fill='gray' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3C/svg%3E") !important;
          background-repeat: no-repeat;
          background-position: right 10px center;
          background-size: 20px;
          transition: border 0.3s ease;
          cursor: pointer;
        }
        .select_cadastro:focus { border-bottom-color: #1c2f41; }

        /* Matérias ocupa a linha inteira do grid */
        .materias-wrapper { grid-column: 1 / -1; }

        .materias-container {
          padding: 8px 10px;
          margin: 0;
          border-bottom: 2px solid #23405a;
          color: #23405a;
          font-size: 16px;
          font-family: 'Poppins', sans-serif;
          display: flex;
          flex-wrap: wrap;
          gap: 8px;
          min-height: 40px;
          align-items: center;
          cursor: pointer;
        }

        .chip { background-color: #e6edf5; color: #23405a; padding: 6px 12px; border-radius: 20px; display: flex; align-items: center; font-size: 14px; }
        .chip span { margin-left: 8px; cursor: pointer; font-weight: bold; }

        .cadastro-btn {
          grid-column: 1 / -1;          /* botão ocupa a linha toda */
          justify-self: start;          /* troque para center se quiser centralizado */
          margin: 8px 0 0 0;
          height: 40px;
          width: 160px;
          padding: 4px;
          font-size: 18px;
          background-color: transparent;
          color: #23405a;
          border: 2px solid #23405a;
          border-radius: 25px;
          cursor: pointer;
          transition: background-color 0.3s ease;
        }
        .cadastro-btn:hover { background-color: #23405a; color: #fff; }

        /* Modal de matérias */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); align-items: center; justify-content: center; z-index: 9999; }
        .modal-content { background-color: white; padding: 20px; border-radius: 8px; width: 500px; max-width: 90vw; max-height: 80vh; overflow-y: auto; }
        .modal-content h3 { text-align: center; color: #23405a; margin-bottom: 10px; }
        .materia-btn { display: inline-block; background-color: #dddddd; padding: 8px 14px; border-radius: 20px; margin: 5px; cursor: pointer; }
        .materia-btn.ativo { background-color: #7491ae; color: #23405a; }
        .titulo_modal { border-bottom: #23405a solid 2px; margin-bottom: 20px; }

        /* Responsivo: 1 coluna em telas menores */
        @media (max-width: 920px) {
          form { grid-template-columns: 1fr; }
          .cadastro-btn { justify-self: stretch; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="../assets/nw.png" alt="Logo Avalia+">
        <ul>
            <li><a href="painelusuarios.php">Inicio</a></li>
            <li><a href="cadastrar.php">Cadastrar</a></li>
            <li><a href="formularios.php">Formulários</a></li>
            <li><a href="calendario.php">Calendário</a></li>
            <li><a href="comousar.php">Como usar</a></li>
        </ul>
        <a class="logout" href="logout.php">
            <img src="../assets/logout.png" alt="Logout"> Logout
        </a>
    </div>

    <header>
        <img class="logo" src="../assets/nw.png" alt="Logo Avalia+">
        <nav>
            <a href="../php/inicio.php">Voltar</a>
        </nav>
    </header>

    <?php if ($mensagemOk): ?>
        <div class="notificacao ok"><?= htmlspecialchars($mensagemOk) ?></div>
    <?php endif; ?>
    <?php if ($mensagemErro): ?>
        <div class="notificacao err"><?= htmlspecialchars($mensagemErro) ?></div>
    <?php endif; ?>

    <div class="page">
      <main class="login-box">
        <div class="titulo">
          <h2>Cadastrar Professor</h2>
        </div>

        <div class="teste">
          <!-- IMPORTANTE: mantém seu action; envia para este mesmo arquivo -->
          <form id="formCadastro" method="POST" action="cadastrar.php" enctype="multipart/form-data">
            <!-- CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <!-- Matérias (hidden para envio) -->
            <input type="hidden" name="materias" id="materiasHidden" value="">

            <input class="input_cadastro" type="text"     name="nome"   placeholder="Nome Completo" required>
            <input class="input_cadastro" type="email"    name="email"  placeholder="Email" required>
            <input class="input_cadastro" type="text"     name="escola" placeholder="Escola" required>
            <input class="input_cadastro" type="password" name="senha"  placeholder="Senha" required>

            <select class="select_cadastro" id="cargos" name="cargo" required>
              <option value="" disabled selected>Cargo</option>
              <option value="Professor">Professor</option>
              <option value="Coordenador">Coordenador</option>
            </select>

            <div class="materias-wrapper">
              <div class="materias-container" id="materiasSelecionadas">Matérias</div>
            </div>

            <button class="cadastro-btn" type="submit">Cadastrar</button>
          </form>
        </div>
      </main>
    </div>

    <!-- Modal de matérias -->
    <div class="modal" id="modalMaterias">
      <div class="modal-content">
        <div class="titulo_modal"><h3>Matérias</h3></div>
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
      // === MATÉRIAS (chips + modal) ===========================================
      const materiasContainer = document.getElementById("materiasSelecionadas");
      const modalMaterias     = document.getElementById("modalMaterias");
      const listaMaterias     = document.getElementById("listaMaterias");
      const materiasHidden    = document.getElementById("materiasHidden");

      let materiasSelecionadas = [];

      materiasContainer.addEventListener("click", () => {
          modalMaterias.style.display = "flex";
      });

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
              materiasContainer.textContent = "Clique para selecionar";
          }
          // Atualiza o hidden para enviar ao PHP
          materiasHidden.value = materiasSelecionadas.join(", ");
      }

      modalMaterias.addEventListener("click", e => {
          if (e.target === modalMaterias) modalMaterias.style.display = "none";
      });

      // garante que o hidden esteja preenchido no submit
      document.getElementById('formCadastro').addEventListener('submit', function() {
          materiasHidden.value = materiasSelecionadas.join(", ");
      });
    </script>
</body>
</html>

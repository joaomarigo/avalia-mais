<?php
session_start();
require_once __DIR__ . '/config.php'; // deve definir $pdo (PDO)

// 0) Exige login
if (empty($_SESSION['usuario_id'])) {
  header('Location: /login.php');
  exit;
}

$userId = (int) $_SESSION['usuario_id'];
$msg    = '';
$erro   = '';

// 1) Lê dados do usuário (conforme seu schema)
$stmt = $pdo->prepare('SELECT id, nome, email, escola, cargo, materias, senha_hash
                         FROM usuarios
                        WHERE id = :id
                        LIMIT 1');
$stmt->execute([':id' => $userId]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$usuario) {
  $erro = 'Usuário não encontrado.';
}

// 2) Se POST, atualiza
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($erro)) {
  $nome   = trim($_POST['nome']   ?? '');
  $escola = trim($_POST['escola'] ?? '');
  $email  = trim($_POST['email']  ?? '');

  $senhaAtual    = $_POST['senha_atual']    ?? '';
  $novaSenha     = $_POST['nova_senha']     ?? '';
  $confirmaSenha = $_POST['confirma_senha'] ?? '';

  if ($nome === '' || $email === '') {
    $erro = 'Nome e e-mail são obrigatórios.';
  }

  $atualizarSenha = false;
  $novoHash = null;

  // Valida senha apenas se o usuário tentou trocar
  if (!$erro && ($senhaAtual !== '' || $novaSenha !== '' || $confirmaSenha !== '')) {
    if ($senhaAtual === '' || $novaSenha === '' || $confirmaSenha === '') {
      $erro = 'Para alterar a senha, preencha os três campos.';
    } elseif (!password_verify($senhaAtual, $usuario['senha_hash'])) {
      $erro = 'Senha atual incorreta.';
    } elseif ($novaSenha !== $confirmaSenha) {
      $erro = 'A confirmação não confere.';
    } elseif (strlen($novaSenha) < 6) {
      $erro = 'A nova senha deve ter pelo menos 6 caracteres.';
    } else {
      $atualizarSenha = true;
      $novoHash = password_hash($novaSenha, PASSWORD_DEFAULT);
    }
  }

  // Monta SQL e parâmetros
  if (!$erro) {
    if ($atualizarSenha) {
      $sql = 'UPDATE usuarios
                 SET nome = :nome, email = :email, escola = :escola, senha_hash = :senha_hash
               WHERE id = :id';
      $params = [
        ':nome'       => $nome,
        ':email'      => $email,
        ':escola'     => $escola,
        ':senha_hash' => $novoHash,
        ':id'         => $userId,
      ];
    } else {
      $sql = 'UPDATE usuarios
                 SET nome = :nome, email = :email, escola = :escola
               WHERE id = :id';
      $params = [
        ':nome'   => $nome,
        ':email'  => $email,
        ':escola' => $escola,
        ':id'     => $userId,
      ];
    }

    try {
      // PREPARE uma única vez, fora dos ifs
      $upd = $pdo->prepare($sql);
      $ok  = $upd->execute($params);
    } catch (PDOException $e) {
      $ok  = false;
      $erro = 'Erro no banco ao atualizar.';
      // em produção, não exponha $e->getMessage()
    }

    if ($ok) {
      // Atualiza sessão e REDIRECIONA
      $_SESSION['nome']  = $nome;
      $_SESSION['email'] = $email;

      header('Location: inicio.php');
      exit;
    } else {
      if (!$erro) $erro = 'Falha ao atualizar o perfil.';
    }
  }
}


// Helpers p/ exibição
$nomeVal   = htmlspecialchars($usuario['nome']   ?? '', ENT_QUOTES, 'UTF-8');
$emailVal  = htmlspecialchars($usuario['email']  ?? '', ENT_QUOTES, 'UTF-8');
$escolaVal = htmlspecialchars($usuario['escola'] ?? '', ENT_QUOTES, 'UTF-8');
$cargoVal  = htmlspecialchars($_SESSION['cargo'] ?? ($usuario['cargo'] ?? ''), ENT_QUOTES, 'UTF-8');
$primeiro  = htmlspecialchars(explode(' ', $usuario['nome'] ?? 'Usuário')[0], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil - Avalia+</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Questrial', sans-serif; }
    html,body { height:100%; width:100%; }
    .container { display:flex; height:100vh; }
    .left {
      flex:1.1; display:flex; flex-direction:column; justify-content:center; align-items:center;
      padding:40px; background:#fff; position:relative; overflow:hidden;
    }
    .flash { position:absolute; top:18px; left:40px; padding:10px 14px; border-radius:10px; font-size:14px; }
    .flash.ok { background:#ecfff1; color:#127a3a; border:1px solid #b8f0cc; }
    .flash.err{ background:#fff2f2; color:#9b1c1c; border:1px solid #f3c0c0; }

    .profile-pic {
      width:125px; height:125px; border-radius:50%; margin-bottom:20px; display:flex; align-items:center; justify-content:center;
      box-shadow:0 4px 8px rgba(0,0,0,.1); overflow:hidden; background:#12337b;
    }
    .profile-pic img { width:100%; height:100%; object-fit:cover; }

    form { width:100%; max-width:380px; }
    .input-container { margin-bottom:22px; }
    .label { font-size:13px; color:#6b7280; margin-bottom:6px; display:block; }
    .input_perfil {
      width:100%; padding:10px 5px; border:none; border-bottom:2px solid #12337b; background:transparent;
      outline:none; font-size:20px; color:#12337b;
    }
    .botoes { display:flex; gap:12px; margin-top:8px; }
    .btn {
      height:42px; padding:0 22px; background:transparent; color:#12337b; border:2px solid #12337b;
      border-radius:25px; cursor:pointer; transition:.2s;
    }
    .btn:hover { background:#12337b; color:#fff; }

    .right {
      flex:.9; background-image:url('../assets/fundo_login.png'); background-repeat:no-repeat; background-position:center;
      background-size:100% 100%; background-attachment:fixed; display:flex; flex-direction:column; justify-content:center;
      color:#fff; padding:40px; position:relative;
    }
    .right .capelo { position:absolute; top:30px; right:40px; width:110px; }
    .right h1 { font-size:80px; padding:0 0 0 8%; line-height:.95; }
    .right h2 { font-size:28px; padding-left:8%; margin-top:8px; opacity:.9; }

    .overlay { position:fixed; inset:0; background:rgba(0,0,0,.3); display:none; z-index:50; }
    .overlay.show{ display:block; }
    .modal-senha {
      position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); width:400px; background:#fff; border-radius:15px;
      padding:30px; box-shadow:0 4px 15px rgba(0,0,0,.3); display:none; flex-direction:column; z-index:60;
    }
    .modal-senha.show { display:flex; }
    .modal-senha h2 { text-align:center; margin-bottom:20px; color:#12337b; font-size:26px; }
    .modal-senha .senha-container { margin-bottom:14px; position:relative; }
    .modal-senha input {
      width:100%; margin-top:10px; padding:10px 35px 10px 5px; border:none; border-bottom:2px solid #12337b; background:transparent;
      outline:none; font-size:18px; color:#12337b;
    }
    .modal-senha .toggle-olho { position:absolute; right:8px; bottom:8px; color:#12337b; cursor:pointer; font-size:16px; }
    .modal-senha .close-btn { position:absolute; top:8px; right:12px; font-size:28px; color:#12337b; cursor:pointer; }
    .modal-senha .salvar-btn { align-self:center; margin-top:10px; }
  </style>
</head>
<body>

<div class="container">

  <div class="left">
    <?php if ($msg):  ?><div class="flash ok"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="flash err"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

    <div class="profile-pic"><img src="../assets/perfil_padrao.png" alt="Perfil"></div>

    <!-- Form principal -->
    <form id="formPerfil" method="post" action="">
      <div class="input-container">
        <span class="label">Nome</span>
        <input class="input_perfil" type="text" name="nome" value="<?= $nomeVal ?>" required>
      </div>

      <div class="input-container">
        <span class="label">Escola</span>
        <input class="input_perfil" type="text" name="escola" value="<?= $escolaVal ?>">
      </div>

      <div class="input-container">
        <span class="label">E-mail</span>
        <input class="input_perfil" type="email" name="email" value="<?= $emailVal ?>" required>
      </div>

      <div class="botoes">
        <button type="button" class="btn" id="abrirModalSenha">Alterar senha</button>
        <button type="submit" class="btn">Salvar</button>
        <button type="button" class="btn" onclick="window.location.href='inicio.php'">Voltar</button>
    </div>
    </form>
  </div>

  <div class="right">
    <img class="capelo" src="../assets/capelo_branco.png" alt="Capelo">
    <h1>Bem - Vindo(a),</h1>
    <h1><?= $primeiro ?></h1>
  </div>

</div>

<!-- Modal de senha -->
<div class="overlay" id="overlay"></div>
<div class="modal-senha" id="modalSenha">
  <span class="close-btn" id="closeModal">&times;</span>
  <h2>Alterar Senha</h2>

  <div class="senha-container">
    <span class="label">Senha atual</span>
    <input type="password" class="input_perfil" placeholder="Senha atual" id="senhaAtual" name="senha_atual" form="formPerfil">
    <i class="fa-solid fa-eye toggle-olho" data-target="senhaAtual"></i>
  </div>

  <div class="senha-container">
    <span class="label">Nova senha</span>
    <input type="password" class="input_perfil" placeholder="Nova senha" id="novaSenha" name="nova_senha" form="formPerfil">
    <i class="fa-solid fa-eye toggle-olho" data-target="novaSenha"></i>
  </div>

  <div class="senha-container">
    <span class="label">Confirmar nova senha</span>
    <input type="password" class="input_perfil" placeholder="Confirmar nova senha" id="confirmaSenha" name="confirma_senha" form="formPerfil">
    <i class="fa-solid fa-eye toggle-olho" data-target="confirmaSenha"></i>
  </div>

  <button type="submit" class="btn salvar-btn" form="formPerfil">Salvar</button>
</div>

<script>
  const overlay    = document.getElementById('overlay');
  const modalSenha = document.getElementById('modalSenha');
  const abrirModal = document.getElementById('abrirModalSenha');
  const fecharX    = document.getElementById('closeModal');

  abrirModal.addEventListener('click', () => { overlay.classList.add('show'); modalSenha.classList.add('show'); });
  fecharX.addEventListener('click', () => { overlay.classList.remove('show'); modalSenha.classList.remove('show'); });
  overlay.addEventListener('click', () => { overlay.classList.remove('show'); modalSenha.classList.remove('show'); });

  document.querySelectorAll('.toggle-olho').forEach(icon => {
    icon.addEventListener('click', () => {
      const id = icon.getAttribute('data-target');
      const input = document.getElementById(id);
      if (input.type === 'password') {
        input.type = 'text'; icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password'; icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye');
      }
    });
  });
</script>
</body>
</html>

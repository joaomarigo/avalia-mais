<?php
session_start();
require_once __DIR__ . '/config.php'; // $pdo

// ---------------- Sessão / nome (igual padrão do projeto) ----------------
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

// ---------------- ID obrigatório ----------------
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  header('Location: redacoes.php');
  exit;
}

// ---------------- POST (salvar título/tema/descrição) ----------------
$salvou = false;
$erro   = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo    = trim($_POST['titulo'] ?? '');
  $tema      = trim($_POST['tema'] ?? '');
  $descricao = trim($_POST['descricao'] ?? '');

  if ($titulo === '') {
    $erro = 'O título é obrigatório.';
  } else {
    try {
      $stmtUp = $pdo->prepare("UPDATE redacoes
                               SET titulo = :titulo,
                                   tema = :tema,
                                   descricao = :descricao,
                                   atualizado_em = NOW()
                               WHERE id = :id");
      $stmtUp->execute([
        ':titulo' => $titulo,
        ':tema' => $tema ?: null,
        ':descricao' => $descricao ?: null,
        ':id' => $id
      ]);
      $salvou = true;
    } catch (PDOException $e) {
      $erro = 'Erro ao salvar alterações.';
    }
  }
}

// ---------------- Buscar dados atuais ----------------
$stmtR = $pdo->prepare("SELECT id,
                               COALESCE(titulo,'') AS titulo,
                               COALESCE(tema,'')   AS tema,
                               COALESCE(descricao,'') AS descricao,
                               criado_em, atualizado_em
                        FROM redacoes
                        WHERE id = :id LIMIT 1");
$stmtR->execute([':id' => $id]);
$red = $stmtR->fetch(PDO::FETCH_ASSOC);

if (!$red) {
  header('Location: redacoes.php');
  exit;
}

// Param para toast caso veio do criar
$sucessoInicial = isset($_GET['sucesso']) && $_GET['sucesso'] == '1';

// ---------------- Valores “de prova” (mostrar no cabeçalho impresso) ----------------
$formulario_titulo = $red['titulo'] ?: 'Redação';
$curso_padrao      = 'Técnico em SECRETARIADO'; // ajuste se quiser
$turma_padrao      = '';                        // deixa em branco para o prof. preencher
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($formulario_titulo) ?> - Editar Redação</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Fonte moderna (igual ao editar_formulario) -->
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />

  <style>
    :root{
      --azul:#1c3b5a;
      --cinza:#f2f2f2;
      --tinta:#333;
      --borda:#e6e6e6;
      --realce:#eef3f8;
    }
    *{box-sizing:border-box}
    html,body{margin:0;padding:0;font-family:Inter,system-ui,Arial,sans-serif;color:var(--tinta);background:var(--cinza)}
    .wrapper{max-width:900px;margin:40px auto;padding:32px;background:#fff;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,.06)}
    header.prova{text-align:center;margin-bottom:24px;padding-bottom:16px;border-bottom:2px solid var(--borda)}
    .marca{font-weight:800;letter-spacing:.4px;color:var(--azul);font-size:22px;margin-bottom:8px}
    .titulo{font-size:26px;font-weight:800;margin:6px 0;color:#111}
    .sub{display:flex;gap:16px;flex-wrap:wrap;justify-content:center;color:#444;font-weight:600}

    .linha-info{display:grid;grid-template-columns:1.2fr .8fr .8fr;gap:12px;margin:18px 0 6px}
    .campo{display:flex;gap:8px;align-items:center;border:1px solid var(--borda);border-radius:10px;padding:10px 12px}
    .campo label{white-space:nowrap;font-weight:600;color:#222}
    .campo input{border:0;outline:0;width:100%;font-size:15px;padding:6px 0;background:transparent}

    .linha-nome{margin-top:8px}
    .campo-nome{display:flex;gap:8px;align-items:center;border:1px solid var(--borda);border-radius:10px;padding:10px 12px}
    .campo-nome input{border:0;outline:0;width:100%;font-size:16px;padding:6px 0}

    .bloco{margin-top:28px;border:1px solid var(--borda);border-radius:12px;padding:18px 18px 8px}
    .bloco h3{margin:0 0 10px;font-size:18px;color:var(--azul);font-weight:800;letter-spacing:.3px}
    .bloco ol, .bloco ul{margin:0 0 12px 18px;padding-left:16px}
    .bloco li{margin:8px 0;line-height:1.5}
    .observacoes{display:grid;gap:10px;margin-top:10px}

    .linha-editar{display:grid;grid-template-columns:1fr;gap:12px;margin:8px 0}
    .label{font-size:13px;color:#6b7280}
    .input_text{width:100%;padding:10px;border:1px solid var(--borda);border-radius:10px;font-size:15px}

    .apoios{margin-top:22px;border:2px dashed #cfd8e3;border-radius:12px;padding:18px;background:linear-gradient(0deg,#fafcff,#ffffff)}
    .apoios .head{display:flex;gap:10px;flex-wrap:wrap;justify-content:center;margin:6px 0 16px}
    .apoio{border:1px solid #dfe7f0;border-radius:12px;padding:12px 12px;margin:12px 0;background:#fff}
    .apoio .row{display:flex;gap:8px;align-items:center;margin-bottom:8px}
    .apoio input[type="text"]{flex:1;border:1px solid var(--borda);border-radius:8px;padding:8px 10px}
    .apoio textarea{width:100%;border:1px solid var(--borda);border-radius:8px;padding:10px;min-height:120px;resize:vertical}

    .btn{appearance:none;border:1px solid #23405a;background:#23405a;color:#fff;padding:10px 14px;border-radius:10px;cursor:pointer;font-weight:700;transition:transform .15s ease, box-shadow .15s ease}
    .btn:hover{transform:translateY(-2px);box-shadow:0 10px 18px rgba(0,0,0,.08)}
    .btn-outline{background:#fff;color:#23405a}
    .btn-ghost{background:transparent;border-color:#d7e2ec;color:#23405a}

    .actions{display:flex;gap:10px;flex-wrap:wrap;justify-content:center;margin-top:16px}
    .flash{padding:10px 14px;border-radius:10px;font-size:14px;margin:0 0 14px}
    .flash.err{background:#fff2f2;color:#9b1c1c;border:1px solid #f3c0c0}
    .notify{position:fixed;top:20px;right:20px;background:#4CAF50;color:#fff;padding:12px 20px;border-radius:8px;box-shadow:0 8px 20px rgba(0,0,0,.15);z-index:2500;font-size:14px}

    /* Impressão */
    @media print{
      body{background:#fff}
      .wrapper{box-shadow:none;margin:0;padding:0;border-radius:0}
      .btn, .actions, .head, .linha-editar, .controles, .controles * {display:none !important}
      .apoio input[type="text"], .apoio textarea, .campo input, .campo-nome input {border:0 !important}
      a[href]:after{content:""}
    }
  </style>
</head>
<body>
  <div class="wrapper">

    <!-- Cabeçalho da Redação (igual vibe do editar_formulario.php) -->
    <header class="prova">
      <div class="marca">PROVÃO</div>
      <div class="titulo"><?= htmlspecialchars($formulario_titulo) ?> — REDAÇÃO</div>
      <div class="sub">
        <div>CURSO: <strong><?= htmlspecialchars($curso_padrao) ?></strong></div>
      </div>

      <div class="linha-info">
        <div class="campo"><label for="turma">Turma:</label></div>
        <div class="campo"><label for="data">Data:</label></div>
        <div class="campo"><label for="sala">Sala:</label></div>
      </div>
      <div class="linha-nome">
        <div class="campo-nome"><label for="aluno">Nome Completo do (a) Aluno (a):</label></div>
      </div>
    </header>

    <!-- Form para editar dados (salvar no BD) -->
    <?php if ($erro): ?>
      <div class="flash err"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="bloco">
        <h3>EDITAR DADOS DA PROPOSTA</h3>
        <div class="linha-editar">
          <label class="label" for="titulo">Título</label>
          <input class="input_text" id="titulo" name="titulo" maxlength="255" required value="<?= htmlspecialchars($red['titulo']) ?>">

          <label class="label" for="tema">Tema (aparece no cabeçalho da proposta)</label>
          <input class="input_text" id="tema" name="tema" maxlength="255" placeholder="Ex.: Tema da redação" value="<?= htmlspecialchars($red['tema']) ?>">

          <label class="label" for="descricao">Enunciado / Proposta ao aluno</label>
          <textarea class="input_text" id="descricao" name="descricao" placeholder="Escreva o enunciado (ex.: redija um texto dissertativo-argumentativo...)"><?= htmlspecialchars($red['descricao']) ?></textarea>
        </div>

        <div class="actions">
          <button type="submit" class="btn">Salvar alterações</button>
          <button type="button" class="btn btn-outline" onclick="window.location.href='redacoes.php'">Voltar</button>
        </div>
      </div>
    </form>

    <!-- Instruções gerais (prontas para imprimir) -->
    <section class="bloco">
      <h3>INSTRUÇÕES GERAIS PARA A REDAÇÃO</h3>
      <ol>
        <li>Faça o rascunho no espaço apropriado (quando fornecido).</li>
        <li>Preencha seu nome completo na parte superior desta folha.</li>
        <li>Use <strong>caneta azul ou preta</strong> na folha oficial (até 30 linhas). Não serão aceitas redações a lápis.</li>
        <li>Linhas copiadas de textos motivadores não serão contadas.</li>
        <li>Zeram nota, por exemplo: até 7 linhas (insuficiente), fuga ao tema, identificação no corpo do texto, ou texto desconectado do tema.</li>
        <li>É proibido o uso de dispositivos eletrônicos durante a prova.</li>
        <li>Mantenha silêncio. Em caso de dúvida, chame o professor.</li>
        <li>Saída permitida apenas após o tempo mínimo estabelecido.</li>
        <li>Ao terminar, entregue todas as folhas (oficial e rascunho, se houver).</li>
      </ol>
    </section>

    <!-- Proposta visível para impressão -->
    <section class="bloco">
      <h3>PROPOSTA DE REDAÇÃO</h3>
      <p><strong>Tema:</strong> <span id="tema_print"><?= htmlspecialchars($red['tema'] ?: '—') ?></span></p>
      <p id="descricao_print" style="white-space:pre-wrap"><?= htmlspecialchars($red['descricao'] ?: '—') ?></p>
    </section>

    <!-- Área dinâmica de Textos de apoio (para imprimir junto) -->
    <section class="apoios" id="area-apoios">
      <h4 style="text-align:center;margin:0;color:#2a3a4a;font-size:18px">Textos de apoio</h4>
      <div class="head">
        <button type="button" class="btn" onclick="adicionarApoio()">Adicionar texto de apoio</button>
      </div>
      <div id="lista-apoios"></div>
    </section>

    <div class="actions">
      <button type="button" class="btn btn-outline" onclick="window.print()">Imprimir</button>
      <button type="button" class="btn btn-ghost" onclick="scrollTo(0,0)">Voltar ao topo</button>
    </div>

    <footer style="text-align:center;margin-top:18px;color:#6b7785;font-size:13px">
      <small>Impressão da proposta de redação + textos de apoio.</small>
    </footer>
  </div>

  <?php if ($salvou || $sucessoInicial): ?>
    <div class="notify" id="notify"><?= $sucessoInicial ? 'Redação criada com sucesso!' : 'Alterações salvas!' ?></div>
    <script>
      setTimeout(()=>{ const n=document.getElementById('notify'); if(n) n.remove(); }, 2500);
    </script>
  <?php endif; ?>

  <script>
    // Sincroniza campos salvos com a “visualização de impressão”
    const inputTema = document.getElementById('tema');
    const inputDesc = document.getElementById('descricao');
    const temaPrint = document.getElementById('tema_print');
    const descPrint = document.getElementById('descricao_print');
    if (inputTema) inputTema.addEventListener('input', () => temaPrint.textContent = inputTema.value || '—');
    if (inputDesc) inputDesc.addEventListener('input', () => descPrint.textContent = inputDesc.value || '—');

    // -------- Textos de apoio dinâmicos (somente client-side, para impressão) --------
    const listaApoios = document.getElementById('lista-apoios');

    function blocoApoio(titulo='', texto=''){
      const wrap = document.createElement('div');
      wrap.className = 'apoio';
      wrap.innerHTML = `
        <div class="row">
          <label style="font-weight:700;color:#20364f">Título:</label>
          <input type="text" placeholder="Ex.: Texto I / Artigo / Reportagem..." value="${escapeHtml(titulo)}">
          <button type="button" class="btn-ghost" onclick="removerApoio(this)">Remover</button>
        </div>
        <textarea placeholder="Cole aqui o texto de apoio...">${escapeHtml(texto)}</textarea>
      `;
      return wrap;
    }
    function adicionarApoio(){
      const el = blocoApoio();
      listaApoios.appendChild(el);
      el.scrollIntoView({behavior:'smooth', block:'center'});
    }
    function removerApoio(btn){
      const bloco = btn.closest('.apoio');
      if (bloco) bloco.remove();
    }

    function escapeHtml(s){
      return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
    }
  </script>
</body>
</html>

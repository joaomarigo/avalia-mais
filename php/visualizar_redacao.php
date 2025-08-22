<?php
session_start();
require_once __DIR__ . '/config.php';

// --------- ID obrigatório ----------
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  header('Location: redacoes.php');
  exit;
}

// --------- Busca a redação ----------
$stmt = $pdo->prepare("SELECT id, titulo, tema, descricao, criado_em, atualizado_em
                       FROM redacoes WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$red = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$red) {
  header('Location: redacoes.php');
  exit;
}

// --------- Valores de cabeçalho (igual ao visualizar_formulario) ----------
$formulario_titulo = $red['titulo'] ?: 'Redação';
$curso_padrao      = 'Técnico em SECRETARIADO';
$turma_padrao      = '2º ANO';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($formulario_titulo) ?> - Visualizar Redação</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />

  <style>
    :root{
      --azul:#1c3b5a;
      --cinza:#f2f2f2;
      --tinta:#333;
      --borda:#e6e6e6;
      --realce:#eef3f8;
      --azul-btn:#1f66ff;
    }
    *{ box-sizing:border-box; max-width:100% }
    html,body{ margin:0; padding:0; font-family:Inter,system-ui,Arial,sans-serif; color:var(--tinta); background:var(--cinza) }
    .wrapper{ max-width:900px; margin:40px auto; padding:32px; background:#fff; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.06) }

    .top-cantos{ position:relative; height:0; }
    .top-cantos img{ position:absolute; top:-16px; width:120px; height:auto }
    .top-cantos .esq{ left:-12px } .top-cantos .dir{ right:-12px }

    header.prova{ text-align:center; margin-bottom:24px; padding-bottom:16px; border-bottom:2px solid var(--borda) }
    .titulo{ font-size:26px; font-weight:800; margin:6px 0; color:#111 }

    .sub{ display:flex; gap:16px; flex-wrap:wrap; justify-content:center; color:#444; font-weight:600 }
    .campo-inline{ display:flex; align-items:center; gap:8px; border:1px solid var(--borda); border-radius:10px; padding:8px 12px; background:#fff }
    .campo-inline label{ font-weight:700; color:#20364f }
    .campo-inline .valor{ font-weight:700 }

    .linha-info{ display:grid; grid-template-columns:1.2fr .8fr .8fr; gap:12px; margin:18px 0 6px; }
    .campo{ display:flex; gap:8px; align-items:center; border:1px solid var(--borda); border-radius:10px; padding:10px 12px }
    .campo label{ white-space:nowrap; font-weight:600; color:#222 }
    .campo .tracejado{ flex:1; border-bottom:1px dashed #bfc7d3; height:1.2em }

    .linha-nome{ margin-top:8px }
    .campo-nome{ display:flex; gap:8px; align-items:center; border:1px solid var(--borda); border-radius:10px; padding:10px 12px }
    .campo-nome .tracejado{ flex:1; border-bottom:1px dashed #bfc7d3; height:1.2em }

    .bloco{ margin-top:28px; border:1px solid var(--borda); border-radius:12px; padding:18px 18px 8px }
    .bloco h3{ margin:0 0 10px; font-size:18px; color:var(--azul); font-weight:800; letter-spacing:.3px }
    .bloco ol, .bloco ul{ margin:0 0 12px 18px; padding-left:16px }
    .bloco li{ margin:8px 0; line-height:1.5 }

    .proposta p{ margin:6px 0; }
    .proposta .texto-pre{ white-space:pre-wrap; word-wrap:break-word; }

    .apoios{ margin-top:22px; border:2px dashed #cfd8e3; border-radius:12px; padding:18px; background:linear-gradient(0deg,#fafcff,#ffffff) }
    .apoios h4{ text-align:center; margin:0; color:#2a3a4a; font-size:18px }
    .apoio{ border:1px solid #dfe7f0; border-radius:12px; padding:12px 12px; margin:12px 0; background:#fff }
    .apoio .tit{ font-weight:700; color:#20364f; margin-bottom:6px }
    .apoio .corpo{ white-space:pre-wrap }

    .actions{ display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin-top:16px }
    .btn{ appearance:none; border:1px solid #23405a; background:#23405a; color:#fff; padding:10px 14px; border-radius:10px; cursor:pointer; font-weight:700; }
    .btn:hover{ filter:brightness(1.05) }
    .btn-outline{ background:#fff; color:#23405a }
    .btn-blue{ background:var(--azul-btn); border-color:var(--azul-btn) }

    footer{ margin-top:26px; text-align:center; color:#6b7785; font-size:13px }

    @media print{
      @page { size: A4 portrait; margin: 10mm; }
      .actions{ display:none !important }
      a[href]:after{ content:"" }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Logos nos cantos (opcional, igual ao visualizar_formulario) -->
    <div class="top-cantos">
      <img class="esq" src="../assets/etec.png" alt="Logo esquerda" />
      <img class="dir" src="../assets/avalia.png" alt="Logo direita" />
    </div>

    <!-- ======= CABEÇALHO ======= -->
    <header class="prova">
      <div class="titulo"><?= htmlspecialchars($formulario_titulo) ?> — REDAÇÃO</div>

      <div class="sub">
        <div class="campo-inline"><label>CURSO:</label><span class="valor"><?= htmlspecialchars($curso_padrao) ?></span></div>
      </div>

      <div class="linha-info">
        <div class="campo"><label>Turma:</label><span class="tracejado"></span></div>
        <div class="campo"><label>Data:</label><span class="tracejado"></span></div>
        <div class="campo"><label>Sala:</label><span class="tracejado"></span></div>
      </div>

      <div class="linha-nome">
        <div class="campo-nome"><label>Nome Completo do (a) Aluno (a):</label><span class="tracejado"></span></div>
      </div>
    </header>

    <!-- ======= INSTRUÇÕES ======= -->
    <section class="bloco">
      <h3>INSTRUÇÕES GERAIS PARA A REDAÇÃO</h3>
      <ol>
        <li>Faça o rascunho no espaço apropriado (quando fornecido).</li>
        <li>Preencha seu nome completo na parte superior desta folha.</li>
        <li>Use <strong>caneta azul ou preta</strong> na folha oficial (até 30 linhas). Redações a lápis não serão aceitas.</li>
        <li>Linhas copiadas de textos motivadores não serão contadas.</li>
        <li>Zeram nota, por exemplo: até 7 linhas (insuficiente), fuga ao tema, identificação no corpo do texto, ou texto desconectado do tema.</li>
        <li>É proibido o uso de dispositivos eletrônicos durante a prova.</li>
        <li>Mantenha silêncio. Em caso de dúvida, chame o professor.</li>
        <li>Saída permitida apenas após o tempo mínimo estabelecido.</li>
        <li>Ao terminar, entregue todas as folhas (oficial e rascunho, se houver).</li>
      </ol>
    </section>

    <!-- ======= PROPOSTA DE REDAÇÃO ======= -->
    <section class="bloco proposta">
      <h3>PROPOSTA DE REDAÇÃO</h3>
      <p><strong>Tema:</strong> <?= htmlspecialchars($red['tema'] ?: '—') ?></p>
      <div class="texto-pre"><?= htmlspecialchars($red['descricao'] ?: '—') ?></div>
    </section>

    <!-- ======= TEXTOS DE APOIO (somente visual/impresso; ajuste conforme seu projeto) ======= -->
    <section class="apoios">
      <h4>Textos de apoio</h4>

      <!--
        Se no futuro você salvar Textos de Apoio no banco (ex.: tabela redacoes_apoios),
        basta iterar aqui. Por enquanto, deixamos dois “slots” de exemplo vazios,
        só para a impressão ficar pronta.
      -->
      <div class="apoio">
        <div class="tit">Texto I</div>
        <div class="corpo">_______________________________________________
_______________________________________________
_______________________________________________
_______________________________________________</div>
      </div>

      <div class="apoio">
        <div class="tit">Texto II</div>
        <div class="corpo">_______________________________________________
_______________________________________________
_______________________________________________
_______________________________________________</div>
      </div>
    </section>

    <div class="actions">
      <button type="button" class="btn btn-blue" onclick="window.print()">Imprimir</button>
      <button type="button" class="btn btn-outline" onclick="window.location.href='redacoes.php'">Voltar</button>
    </div>

    <footer><small>Impressão da proposta de redação + textos de apoio.</small></footer>
  </div>
</body>
</html>

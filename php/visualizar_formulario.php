<?php
include_once __DIR__ . '/config.php';

$id = $_GET['id'] ?? null;

$formulario_titulo = 'PROVÃO 1º SEMESTRE DE 2025';
$curso_padrao      = 'Técnico em SECRETARIADO';
$turma_padrao      = '2º ANO';

if ($id) {
  $stmt = $pdo->prepare("SELECT titulo FROM formularios WHERE id = :id");
  $stmt->execute([':id' => $id]);
  if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $formulario_titulo = $row['titulo'] ?: $formulario_titulo;
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($formulario_titulo) ?></title>
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
      --vermelho:#d93025;
    }
    *{box-sizing:border-box; max-width:100%}
    html,body{
      margin:0;padding:0;
      font-family:Inter,system-ui,Arial,sans-serif;
      color:var(--tinta);
      background:var(--cinza)
    }
    img{display:block}
    .wrapper{
      max-width:1000px;
      margin:40px auto;
      padding:32px;
      background:#fff;
      border-radius:12px;
      box-shadow:0 4px 16px rgba(0,0,0,.06)
    }

    /* --------- Logos fixas de topo (esquerda/direita) --------- */
    .top-cantos{
      position:relative;
      height:0;
    }
    .top-cantos img.esq,
    .top-cantos img.dir{
      position:absolute; top:-12px; width:130px; height:auto;
    }
    .top-cantos img.esq{left:-12px}
    .top-cantos img.dir{right:-12px}

    /* Logo fixa em TODAS as páginas (tela e impressão) */
    .logo-fixa{
      position:fixed; left:12px; top:12px; width:64px; height:auto; z-index:9999;
    }

    header.prova{
      text-align:center;
      margin-bottom:24px;
      padding-bottom:16px;
      border-bottom:2px solid var(--borda);
      position:relative;
    }

    .titulo-linha{
      display:flex; gap:10px; justify-content:center; align-items:center; flex-wrap:wrap;
    }
    .titulo-label{ font-weight:700; color:#20364f }
    .titulo-input{
      border:1px solid var(--borda); border-radius:8px; padding:10px 12px;
      min-width:260px; font-weight:800; font-size:22px; color:#111;
    }

    .sub{
      display:flex; gap:16px; flex-wrap:wrap; justify-content:center;
      color:#444; font-weight:600; margin-top:8px
    }
    .sub .campo-inline{
      display:flex; align-items:center; gap:8px; flex-wrap:wrap;
      border:1px solid var(--borda); border-radius:10px; padding:8px 12px;
    }
    .sub input[type="text"]{
      border:0; outline:0; background:transparent; font-weight:700;
    }
    .editar-btn{
      border:1px solid #23405a; background:#23405a; color:#fff; padding:6px 10px;
      border-radius:8px; cursor:pointer; font-weight:700;
    }

    .linha-info{
      display:grid; grid-template-columns:1.2fr .8fr .8fr;
      gap:12px; margin:18px 0 6px;
    }
    .campo{
      display:flex; gap:8px; align-items:center;
      border:1px solid var(--borda); border-radius:10px; padding:10px 12px
    }
    .campo label{ white-space:nowrap; font-weight:600; color:#222 }
    .campo input{
      border:0; outline:0; width:100%; font-size:15px; padding:6px 0; background:transparent
    }

    .linha-nome{margin-top:8px}
    .campo-nome{
      display:flex; gap:8px; align-items:center;
      border:1px solid var(--borda); border-radius:10px; padding:10px 12px
    }
    .campo-nome input{
      border:0; outline:0; width:100%; font-size:16px; padding:6px 0
    }

    .bloco{
      margin-top:28px; border:1px solid var(--borda); border-radius:12px; padding:18px 18px 8px
    }
    .bloco h3{
      margin:0 0 10px; font-size:18px; color:var(--azul); font-weight:800; letter-spacing:.3px;
      display:flex; align-items:center; justify-content:space-between; gap:10px;
    }

    .bloco ol, .bloco ul{ margin:0 0 12px 18px; padding-left:16px }
    .bloco li{ margin:8px 0; line-height:1.5 }

    /* TAGS */
    .tags{
      display:flex; gap:8px; flex-wrap:wrap; margin-top:8px
    }
    .tag{
      background:#eef3f8; color:#19344f; border:1px solid #d7e2ec; border-radius:999px; padding:6px 12px; font-weight:600
    }
    .tag-add{
      display:flex; gap:6px; align-items:center; margin-top:10px
    }
    .tag-add input{
      border:1px solid var(--borda); border-radius:8px; padding:8px 10px
    }

    /* --------- Questões (duas colunas) --------- */
    .questoes{
      margin-top:28px; border:2px dashed #cfd8e3; border-radius:12px;
      padding:18px; background:linear-gradient(0deg,#fafcff,#ffffff); position:relative;
    }
    .questoes .top-acoes{
      display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin:10px 0 18px
    }

    /* GRID DAS QUESTÕES */
#lista-questoes{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:14px;
  position:relative;   /* necessário para posicionar a divisória */
  min-height:1px;
}

/* DIVISÓRIA central – usa BORDA (imprime mesmo sem “background graphics”) */
#lista-questoes::after{
  content:"";
  position:absolute;
  top:0;
  bottom:0;            /* acompanha a altura real das questões */
  left:50%;
  width:0;             /* linha vem da borda, não da largura */
  border-left:1px solid #e6e6e6;  /* cor forte p/ impressão */
  display:none;        /* começa escondido */
  pointer-events:none;
}

/* mostra a linha quando houver questões */
#lista-questoes.tem-questoes::after{
  display:block;
}


    .questao{
      border:1px solid #dfe7f0; border-radius:12px; padding:14px 16px; background:#fff;
      overflow:hidden;
    }
    .questao-head{
      display:flex; gap:10px; align-items:center; justify-content:space-between; flex-wrap:wrap; margin-bottom:10px
    }
    .questao-info{ display:flex; align-items:center; gap:10px; flex-wrap:wrap }
    .badge-num{
      background:var(--realce); border:1px solid #d7e2ec; color:#19344f; font-weight:800; border-radius:8px;
      padding:6px 10px; min-width:64px; text-align:center
    }
    .titulo-wrap{ display:flex; gap:6px; align-items:center }
    .titulo-wrap label{ font-weight:700; color:#20364f }
    .titulo-questao{
      border:1px solid var(--borda); border-radius:8px; padding:8px 10px; min-width:200px; font-weight:600;
      overflow-wrap:break-word;
    }
    .enunciado{
      width:100%; border:1px solid var(--borda); border-radius:10px; padding:10px 12px; margin:8px 0;
      font-size:15px; overflow-wrap:break-word; resize:vertical;
    }

    .alternativas{ margin-top:8px }
    .alternativas .alt{
      display:grid; grid-template-columns:auto 1fr; align-items:center; gap:8px; margin:8px 0
    }
    .alternativas .alt .letra{
      font-weight:800; background:#f7fafc; border:1px solid #e6eef6; padding:6px 10px; border-radius:8px; min-width:40px; text-align:center
    }
    .alternativas .alt input[type="text"]{
      flex:1; border:1px solid var(--borda); border-radius:8px; padding:8px 10px; overflow-wrap:break-word;
    }
    .alternativas .alt input[type="radio"]{ display:none }

    /* Botões */
    .btn{ appearance:none; border:1px solid #23405a; background:#23405a; color:#fff; padding:10px 14px; border-radius:10px; cursor:pointer; font-weight:700; transition:transform .15s ease, box-shadow .15s ease, opacity .2s ease }
    .btn:hover{ transform:translateY(-2px); box-shadow:0 10px 18px rgba(0,0,0,.08) }
    .btn-add{ background:var(--azul-btn); border-color:var(--azul-btn) }
    .btn-remove{ background:var(--vermelho); border-color:var(--vermelho) }
    .btn-ghost{ background:transparent; border-color:#d7e2ec; color:#23405a }
    .controles{ display:flex; gap:8px; flex-wrap:wrap }

    footer{ margin-top:26px; text-align:center; color:#6b7785; font-size:13px }

    /* --------- Gabarito --------- */
    .gabarito{
      margin-top:28px; border:1px solid var(--borda); border-radius:12px; padding:18px;
    }
    .gabarito h3{
      margin:0 0 12px; font-size:18px; color:var(--azul); font-weight:800;
    }
    .gabarito-grid{
      display:grid; grid-template-columns:repeat(2, 1fr); gap:12px;
    }
    .gab-item{
      border:1px solid #e6eaf0; border-radius:10px; padding:10px 12px; display:flex; align-items:center; justify-content:space-between;
    }
    .gab-num{ font-weight:800; color:#19344f }
    .gab-bubbles{ display:flex; gap:8px; }
    .bubble{
      width:28px; height:28px; border:2px solid #9fb4ca; border-radius:50%;
      display:flex; align-items:center; justify-content:center;
      user-select:none; font-weight:800;
      cursor: default;         /* não mostra mais a “mãozinha” */
      pointer-events: none;    /* ignora cliques/hover */
}

  @media print{
  body{ background:#fff }
  .wrapper{ box-shadow:none; margin:0; padding:0; border-radius:0 }
  .questoes{ border:0; padding:0; background:#fff }
  .questoes .top-acoes,
  .btn, .btn-ghost, .btn-add, .btn-remove, .controles, .controles *,
  .editar-btn, .tag-add, .titulo-label { display:none !important }
  .titulo-input, .sub input, .campo input, .campo-nome input, .titulo-questao, .enunciado, .alternativas input[type="text"]{ border:0 !important }
  .top-cantos{ display:block !important; }
  .top-cantos img{ width:100px !important; } /* define tamanho na impressão */
  #lista-questoes::after{
    display:block !important;
    border-left-color:#e6e6e6 !important;
    border-left-width:1px !important;
    border-left-style:solid !important;
  }
}
  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Imagens nos cantos esquerdo e direito -->
    <div class="top-cantos">
      <img class="esq" src="../assets/etec.png" alt="Logo esquerda">
      <img class="dir" src="../assets/avalia.png" alt="Imagem direita">
    </div>

    <!-- Cabeçalho da prova -->
    <header class="prova">
      <div class="titulo-linha">
        <span class="titulo-label">Título:</span>
        <input id="tituloProva" class="titulo-input" type="text" value="<?= htmlspecialchars($formulario_titulo) ?>">
        <button class="editar-btn" type="button" onclick="focusOn('tituloProva')">Editar</button>
      </div>

      <div class="sub" style="margin-top:12px">
        <div class="campo-inline">
          <label>CURSO:</label>
          <input id="cursoInput" type="text" value="<?= htmlspecialchars($curso_padrao) ?>">
          <button class="editar-btn" type="button" onclick="focusOn('cursoInput')">Editar</button>
        </div>

        <div class="campo-inline">
          <label>Turma:</label>
          <input id="turmaInline" type="text" value="<?= htmlspecialchars($turma_padrao) ?>">
          <button class="editar-btn" type="button" onclick="focusOn('turmaInline')">Editar</button>
        </div>
      </div>

      <div class="linha-info">
        <div class="campo"><label for="turma">Turma:</label><input id="turma" name="turma" type="text" placeholder="<?= htmlspecialchars($turma_padrao) ?>" /></div>
        <div class="campo"><label for="data">Data:</label><input id="data" name="data" type="text" placeholder="__/__/____" /></div>
        <div class="campo"><label for="sala">Sala:</label><input id="sala" name="sala" type="text" placeholder="____" /></div>
      </div>

      <div class="linha-nome">
        <div class="campo-nome">
          <label for="aluno">Nome Completo do (a) Aluno (a):</label>
          <!-- Removido placeholder “Digite seu nome completo” -->
          <input id="aluno" name="aluno" type="text" placeholder="">
        </div>
      </div>
    </header>

    <!-- Instruções gerais (editável) -->
    <section class="bloco" id="bloco-instrucoes">
      <h3>
        INSTRUÇÕES GERAIS PARA A REALIZAÇÃO DA PROVA
        <button class="editar-btn" type="button" onclick="toggleEditInstrucao()">Editar</button>
      </h3>
      <ol id="lista-instrucoes" contenteditable="false">
        <li>Identificação: preencha seu nome completo na primeira folha de questões e no gabarito.</li>
        <li>A prova é composta por: folhas de questões (total de 70) e folha de gabarito; confira tudo antes de iniciar.</li>
        <li>Leia cuidadosamente cada pergunta e alternativas antes de responder. Não será permitida revisão em caso de uso de corretivo ou rasuras.</li>
        <li>Utilize <strong>caneta azul ou preta</strong> para marcar as respostas no gabarito. Respostas a lápis não serão aceitas.</li>
        <li>É proibido o uso de dispositivos eletrônicos durante a prova (smartwatches, celulares etc.).</li>
        <li>Cada questão objetiva possui 4 alternativas, sendo apenas uma correta.</li>
        <li>Mantenha silêncio durante toda a prova.</li>
        <li>Em caso de dúvida, chame o professor.</li>
        <li><strong>Saída permitida somente após 2 horas</strong> do início.</li>
        <li>Se terminar antes do horário mínimo, permaneça em silêncio até a liberação.</li>
        <li>Reserve tempo suficiente para preencher a folha de gabarito.</li>
        <li>Ao terminar, entregue todas as folhas (questões e gabarito) ao professor.</li>
      </ol>
    </section>

    <!-- Sobre a avaliação (TAGS no lugar do texto) -->
    <section class="bloco">
      <h3> Matérias </h3>
      <div id="tags" class="tags">
        <span class="tag">Português</span>
        <span class="tag">Gramática</span>
      </div>
      <div class="tag-add">
        <input id="tagInput" type="text" placeholder="Adicionar tag">
        <button class="editar-btn" type="button" onclick="addTag()">Adicionar</button>
      </div>
    </section>

    <!-- Área dinâmica de questões -->
    <section class="questoes" id="area-questoes">
      <h4 style="text-align:center;margin:0;color:#2a3a4a;font-size:18px">Questões</h4>
      <div class="top-acoes">
        <button type="button" class="btn btn-add" onclick="adicionarQuestao()">Adicionar questão</button>
        <!-- Botão de exemplo removido -->
        <button type="button" class="btn btn-ghost" onclick="window.print()">Imprimir</button>
      </div>
      <div id="lista-questoes"></div>
    </section>

    <!-- Gabarito (sem respostas) -->
    <section class="gabarito" id="gabarito">
      <h3>GABARITO (estilo ENEM)</h3>
      <div class="gabarito-grid" id="gabaritoGrid">
        <!-- itens gerados via JS conforme nº de questões -->
      </div>
    </section>
  </div>

  <script>
    const lista = document.getElementById('lista-questoes');
    const gabGrid = document.getElementById('gabaritoGrid');

    function focusOn(id){
      const el = document.getElementById(id);
      if (!el) return;
      el.removeAttribute('disabled');
      el.focus();
      if (el.select) el.select();
    }

    function toggleEditInstrucao(){
      const ol = document.getElementById('lista-instrucoes');
      const editable = ol.getAttribute('contenteditable') === 'true';
      ol.setAttribute('contenteditable', editable ? 'false' : 'true');
    }

    function addTag(){
      const inp = document.getElementById('tagInput');
      const val = (inp.value||'').trim();
      if (!val) return;
      const span = document.createElement('span');
      span.className = 'tag';
      span.textContent = val;
      document.getElementById('tags').appendChild(span);
      inp.value = '';
    }

    function criarBlocoQuestao(
      titulo='Sem título',
      enunciado='Descreva o enunciado aqui...',
      alternativas=['Alternativa A','Alternativa B','Alternativa C','Alternativa D']
    ) {
      const bloco = document.createElement('div');
      bloco.className = 'questao';
      bloco.innerHTML = `
        <div class="questao-head">
          <div class="questao-info">
            <span class="badge-num">Questão <span class="numero">1</span></span>
            <div class="titulo-wrap">
              <label class="titulo-label">Título:</label>
              <input type="text" class="titulo-questao" value="${escapeHtml(titulo)}" />
            </div>
          </div>
          <div class="controles">
            <button type="button" class="btn btn-ghost" onclick="adicionarQuestaoDepois(this)">Adicionar questão</button>
            <button type="button" class="btn btn-remove" onclick="removerQuestao(this)">Remover</button>
          </div>
        </div>
        <textarea class="enunciado" rows="3" placeholder="Enunciado da questão...">${escapeHtml(enunciado)}</textarea>
        <div class="alternativas">
          ${['A','B','C','D'].map((letra, i)=>`
            <div class="alt" data-alt="${letra}">
              <span class="letra">${letra}</span>
              <input type="radio" class="alt-radio" name="tmpName" value="${letra}" aria-label="${letra}">
              <input type="text" class="alt-texto" value="${escapeHtml(alternativas[i] || '')}" placeholder="Texto da alternativa">
            </div>
          `).join('')}
        </div>
      `;
      // vincula mudança de resposta ao gabarito
      bloco.addEventListener('change', (ev)=>{
        const tgt = ev.target;
        if (tgt.classList.contains('alt-radio')){
          sincronizarComGabarito();
        }
      });
      return bloco;
    }

    function adicionarQuestao(){
  const bloco = criarBlocoQuestao();
  lista.appendChild(bloco);
  renumerarQuestoes();
  scrollAte(bloco);
  sincronizarComGabarito();
  atualizarDivisoria();                 // <--- importante
}

function adicionarQuestaoDepois(btn){
  const atual = btn.closest('.questao');
  if (!atual) return;
  const nova = criarBlocoQuestao();
  if (atual.nextSibling){ lista.insertBefore(nova, atual.nextSibling); } else { lista.appendChild(nova); }
  renumerarQuestoes();
  scrollAte(nova);
  sincronizarComGabarito();
  atualizarDivisoria();                 // <--- importante
}

function removerQuestao(btn){
  const bloco = btn.closest('.questao');
  if (!bloco) return;
  bloco.remove();
  renumerarQuestoes();
  sincronizarComGabarito();
  atualizarDivisoria();                 // <--- importante
}


    function renumerarQuestoes(){
      const blocos = [...lista.querySelectorAll('.questao')];
      blocos.forEach((b, i) => {
        const n = i + 1;
        const spanNum = b.querySelector('.numero');
        if (spanNum) spanNum.textContent = n;
        // radio name único por questão (para conectar com gabarito)
        const name = 'q'+n;
        b.querySelectorAll('.alt-radio').forEach(r => r.name = name);
      });
    }

    function escapeHtml(s){ return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m])); }
    function scrollAte(el){ el.scrollIntoView({behavior:'smooth', block:'center'}); }

    function atualizarDivisoria(){
  const tem = lista.querySelectorAll('.questao').length > 0;
  document.getElementById('lista-questoes')
          .classList.toggle('tem-questoes', tem);
}


function atualizarDivisoria(){
  const tem = lista.querySelectorAll('.questao').length > 0;
  document.getElementById('lista-questoes')
          .classList.toggle('tem-questoes', tem);
}


    /* --------- Gabarito (conexão bidirecional) --------- */
    function construirGabarito(){
      gabGrid.innerHTML = '';
      const total = lista.querySelectorAll('.questao').length || 0;
      for (let i=1;i<=total;i++){
        const item = document.createElement('div');
        item.className = 'gab-item';
        item.setAttribute('data-q', 'q'+i);
        item.innerHTML = `
          <span class="gab-num">${i}</span>
          <div class="gab-bubbles">
            ${['A','B','C','D'].map(L => `<div class="bubble" data-alt="${L}" role="button" aria-label="${i}-${L}">${L}</div>`).join('')}
          </div>
        `;
        gabGrid.appendChild(item);
        atualizarDivisoria();
      }
    }
    function sincronizarComGabarito(){
      // lê seleções nas questões -> reflete no gabarito
      [...gabGrid.querySelectorAll('.gab-item')].forEach(item=>{
        item.querySelectorAll('.bubble').forEach(b=>b.classList.remove('sel'));
        const qname = item.getAttribute('data-q');
        const marcado = lista.querySelector(`.alt-radio[name="${qname}"]:checked`);
        if (marcado){
          const letra = marcado.value;
          const alvo = item.querySelector(`.bubble[data-alt="${letra}"]`);
          if (alvo){ alvo.classList.add('sel'); }
        }
      });
    }

    // Inicial: cria 2 questões de exemplo “vazias” (sem gabarito marcado)
    adicionarQuestao();
    adicionarQuestao();
    construirGabarito();

    // Reconstrói gabarito quando nº de questões muda
    const obs = new MutationObserver(()=>{ renumerarQuestoes(); construirGabarito(); sincronizarComGabarito(); atualizarDivisoria(); });
    obs.observe(lista, {childList:true, subtree:false});

    /* Ajustes extras pedidos */
    // Evitar overflow de campos mesmo com textos longos
    document.addEventListener('input', (e)=>{
      if (e.target.matches('input[type="text"], textarea')){
        e.target.style.width = '100%';
      }
    });

    // Reconstruir gabarito se redimensionar (opcional)
    window.addEventListener('resize', ()=>{ sincronizarComGabarito(); });
  </script>
</body>
</html>

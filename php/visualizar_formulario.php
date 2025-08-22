<?php
// ---------- BOOTSTRAP PHP ----------
include_once __DIR__ . '/config.php';

$id = $_GET['id'] ?? null;

$formulario_titulo = '1º SEMESTRE DE 2025'; // Sem “PROVÃO”
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

    *{ box-sizing:border-box; max-width:100% }
    html,body{ margin:0; padding:0; font-family:Inter,system-ui,Arial,sans-serif; color:var(--tinta); background:var(--cinza) }

    .wrapper{
      max-width:900px;
      margin:40px auto;
      padding:32px;
      background:#fff;
      border-radius:12px;
      box-shadow:0 4px 16px rgba(0,0,0,.06)
    }

    /* ------- Logos nos cantos ------- */
    .top-cantos{ position:relative; height:0; }
    .top-cantos img{ position:absolute; top:-16px; width:120px; height:auto }
    .top-cantos .esq{ left:-12px }
    .top-cantos .dir{ right:-12px }

    /* (Opcional) logo fixa em todas as páginas */
    .logo-fixa{ position:fixed; left:12px; top:12px; width:64px; height:auto; z-index:9999 }

    /* ------- Cabeçalho ------- */
    header.prova{
      text-align:center;
      margin-bottom:24px;
      padding-bottom:16px;
      border-bottom:2px solid var(--borda)
    }
    .titulo-edit{
      border:1px solid var(--borda);
      border-radius:10px;
      padding:10px 12px;
      font-size:24px;
      font-weight:800;
      color:#111;
      text-align:center;
      width:100%;
      max-width:560px;
      margin:0 auto 8px;
    }

    .sub{
      display:flex;
      flex-wrap:wrap;
      gap:16px;
      justify-content:center;   /* centraliza a “pílula” */
      color:#444;
      font-weight:600
    }

    .campo-inline{
      display:flex;
      align-items:center;
      gap:8px;
      flex-wrap:nowrap;          /* label + input na mesma linha */
      border:1px solid var(--borda);
      border-radius:10px;
      padding:8px 12px;
      background:#fff;
    }
    .campo-inline label{ font-weight:700; color:#20364f }
    .campo-inline input{
      border:0; outline:0; background:transparent;
      font-weight:700;
      width:100%; min-width:0;
    }

    /* CURSO – pílula centralizada e sem largura fixa,
       deixa o conteúdo crescer naturalmente */
    .curso-bloco{
      width:auto;
      max-width:100%;
      margin:0 auto;
    }
    /* Apenas o INPUT do CURSO cresce conforme o texto */
    .campo-inline input#curso{
      width:auto;               /* largura baseada no conteúdo */
      min-width:120px;          /* largura mínima inicial */
      font-size:18px;
    }

    .linha-info{
      display:grid;
      grid-template-columns:1.2fr .8fr .8fr; /* Turma + Data + Sala */
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

    .linha-nome{ margin-top:8px }
    .campo-nome{
      display:flex; gap:8px; align-items:center;
      border:1px solid var(--borda); border-radius:10px; padding:10px 12px
    }
    .campo-nome input{ border:0; outline:0; width:100%; font-size:16px; padding:6px 0 }

    /* ------- Blocos ------- */
    .bloco{ margin-top:28px; border:1px solid var(--borda); border-radius:12px; padding:18px 18px 8px }
    .bloco h3{ margin:0 0 10px; font-size:18px; color:var(--azul); font-weight:800; letter-spacing:.3px }
    .bloco ol, .bloco ul{ margin:0 0 12px 18px; padding-left:16px }
    .bloco li{ margin:8px 0; line-height:1.5 }

    /* TAGS */
    .tags{ display:flex; gap:8px; flex-wrap:wrap; margin-top:8px }
    .tag{ background:#eef3f8; color:#19344f; border:1px solid #d7e2ec; border-radius:999px; padding:6px 12px; font-weight:600 }
    .tag-add{ display:flex; gap:6px; align-items:center; margin-top:10px }
    .tag-add input{ border:1px solid var(--borda); border-radius:8px; padding:8px 10px }

    /* Tags (mantém o seu existente e acrescente o abaixo) */
    .tags{ display:flex; gap:8px; flex-wrap:wrap; margin-top:8px }

    .tag{
      display:inline-flex;           /* alinhamento do texto + botão */
      align-items:center;
      gap:6px;
      background:#eef3f8;
      color:#19344f;
      border:1px solid #d7e2ec;
      border-radius:999px;
      padding:6px 10px;              /* um pouco menor por conta do botão */
      font-weight:600;
    }

    /* Botão de remover da tag */
    .tag .tag-x{
      appearance:none;
      border:0;
      background:#e6edf6;
      color:#1b2f49;
      width:20px; height:20px;
      border-radius:50%;
      font-weight:800;
      cursor:pointer;
      line-height:20px;
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:0;
    }
    .tag .tag-x:hover{ background:#d7e6f5 }

    /* NÃO mostrar o botão na impressão */
    @media print{
      .tag .tag-x{ display:none !important }
    }

    /* ------- Questões (duas colunas) ------- */
    .questoes{
      margin-top:28px; border:2px dashed #cfd8e3; border-radius:12px;
      padding:18px; background:linear-gradient(0deg,#fafcff,#ffffff); position:relative;
    }
    .questoes .top-acoes{
      display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin:10px 0 18px
    }

    #lista-questoes{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:14px;
      position:relative;
      min-height:1px;
    }
    /* Linha fina central (garante impressão) */
    #lista-questoes::after{
      content:""; position:absolute; top:0; bottom:0; left:50%;
      width:0; border-left:1px solid #e6e6e6; display:none;
      pointer-events:none;
    }
    #lista-questoes.tem-questoes::after{ display:block }

    .questao{
      border:1px solid #dfe7f0; border-radius:12px; padding:14px 16px; background:#fff;
      overflow:hidden;
    }
    .questao-head{
      display:flex; gap:10px; align-items:center; justify-content:space-between; flex-wrap:wrap; margin-bottom:10px
    }
    .badge-num{
      background:var(--realce); border:1px solid #d7e2ec; color:#19344f; font-weight:800; border-radius:8px;
      padding:6px 10px; min-width:64px; text-align:center
    }
    .titulo-questao{
      border:1px solid var(--borda); border-radius:8px; padding:8px 10px; min-width:200px; font-weight:600; width:100%;
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

    /* ------- Botões ------- */
    .btn{ appearance:none; border:1px solid #23405a; background:#23405a; color:#fff; padding:10px 14px; border-radius:10px; cursor:pointer; font-weight:700; transition:transform .15s ease, box-shadow .15s ease }
    .btn:hover{ transform:translateY(-2px); box-shadow:0 10px 18px rgba(0,0,0,.08) }
    .btn-add{ background:var(--azul-btn); border-color:var(--azul-btn) }
    .btn-remove{ background:var(--vermelho); border-color:var(--vermelho) }
    .btn-ghost{ background:transparent; border-color:#d7e2ec; color:#23405a }
    .controles{ display:flex; gap:8px; flex-wrap:wrap }

    footer{ margin-top:26px; text-align:center; color:#6b7785; font-size:13px }

    /* ------- Gabarito ------- */
    .gabarito {
      display: grid;
      grid-template-columns: 1fr; /* padrão: uma coluna só */
      gap: 12px;
    }

    .gabarito h3 {
      margin: 0 0 12px;
      font-size: 18px;
      color: var(--azul);
      font-weight: 800;
    }

    .gabarito-grid{
      /* Deixa o grid preencher para BAIXO antes de criar outra coluna */
      display: grid;
      grid-auto-flow: column;                 /* preenche verticalmente */
      grid-template-rows: repeat(var(--gab-rows, 30), auto); /* 30 linhas por coluna */
      row-gap: 12px;                          /* espaço vertical entre itens */
      column-gap: 16px;                       /* espaço entre colunas */
      align-items: start;
    }

    @media print{
      .gabarito-grid{
        --gab-rows: 25;  /* por exemplo: 25 itens por coluna na impressão */
      }
    }

    .gab-item { 
      border: 1px solid #e6eaf0; 
      border-radius: 10px; 
      padding: 10px 12px; 
      display: flex; 
      align-items: center; 
      justify-content: flex-start; 
      gap: 10px;
    }

    .gab-num { 
      font-weight: 800; 
      color: #19344f; 
    }

    .gab-bubbles{
      display:flex;
      gap:8px;
      justify-content:flex-start;
      margin-left:4px;
    }

    .bubble {
      width: 28px; 
      height: 28px; 
      border: 2px solid #9fb4ca; 
      border-radius: 50%;
      display: flex; 
      align-items: center; 
      justify-content: center; 
      user-select: none; 
      font-weight: 800;
      pointer-events: none; /* apenas visual */
    }


    /* ------- Impressão ------- */
    @media print{
      /* Tamanho da página e margens enxutas (ajuste se quiser) */
      @page { size: A4 portrait; margin: 10mm; }

      /* Gabarito sempre começa em nova página (continua valendo) */
      .gabarito{
        break-before: page;
        page-break-before: always;
      }

      /* 🔹 TROCAMOS o grid por MULTI-COLUNAS só na impressão
        Isso faz o conteúdo "descer" e depois ir para a próxima coluna,
        mantendo tudo na MESMA FOLHA, se possível. */
      .gabarito-grid{
        display: block !important;      /* anula as regras de grid na impressão */
        column-count: 4;                 /* 👉 tente 4 colunas para caber bastante */
        column-gap: 8px;                 /* espaço entre colunas */
        /* Se ainda faltar espaço, aumente para 5 colunas */
        /* column-count: 5; */
      }

      /* Cada item do gabarito não pode quebrar entre colunas/páginas */
      .gab-item{
        break-inside: avoid;
        page-break-inside: avoid;
        /* compacto para caber mais */
        padding: 6px 8px;
        gap: 6px;
        margin-bottom: 6px;              /* separação vertical mínima */
      }

      /* Deixa tudo um pouco menor no gabarito impresso */
      .gabarito h3{ font-size: 14px; margin-bottom: 6px; }
      .gab-num{ font-size: 12px; min-width: 18px; }
      .gab-bubbles{ gap: 6px; margin-left: 4px; }
      .bubble{
        width: 22px; height: 22px;       /* eram 28px */
        border-width: 1.5px;
        font-size: 12px;
      }
    }


  </style>
</head>
<body>
  <!-- (Opcional) logo fixa em todas as páginas -->
  <!-- <img class="logo-fixa" src="../assets/sua_logo.png" alt="Logo"> -->

  <div class="wrapper">
    <!-- Logos nos cantos -->
    <div class="top-cantos">
      <img class="esq" src="../assets/etec.png" alt="Logo esquerda">
      <img class="dir" src="../assets/avalia.png" alt="Logo direita">
    </div>

    <!-- ======= CABEÇALHO ======= -->
    <header class="prova">
      <!-- Título -->
      <input id="titulo" class="titulo-edit" type="text" value="<?= htmlspecialchars($formulario_titulo) ?>">

      <!-- CURSO (pílula centralizada; input cresce com o texto) -->
      <div class="sub">
        <div class="campo-inline curso-bloco">
          <label>CURSO:</label>
          <input id="curso" type="text" value="<?= htmlspecialchars($curso_padrao) ?>">
        </div>
      </div>

      <!-- Linha com Turma / Data / Sala -->
      <div class="linha-info">
        <div class="campo"><label for="turma">Turma:</label><input id="turma" name="turma" type="text" placeholder="<?= htmlspecialchars($turma_padrao) ?>" /></div>
        <div class="campo"><label for="data">Data:</label><input id="data" name="data" type="text" placeholder="__/__/____" /></div>
        <div class="campo"><label for="sala">Sala:</label><input id="sala" name="sala" type="text" placeholder="____" /></div>
      </div>

      <!-- Nome do aluno -->
      <div class="linha-nome">
        <div class="campo-nome"><label for="aluno">Nome Completo do (a) Aluno (a):</label><input id="aluno" name="aluno" type="text" placeholder="" /></div>
      </div>
    </header>

    <!-- Instruções gerais -->
    <section class="bloco" id="instrucoes" contenteditable="true">
      <h3>INSTRUÇÕES GERAIS PARA A REALIZAÇÃO DA PROVA</h3>
      <ol>
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

    <!-- TAGS -->
    <section class="bloco">
      <h3>Matérias</h3>
      <div id="tags" class="tags">
        <span class="tag">Português</span>
        <span class="tag">Gramática</span>
      </div>
      <div class="tag-add">
        <input id="tagInput" type="text" placeholder="Adicionar tag">
        <button class="btn btn-ghost" type="button" onclick="addTag()">Adicionar</button>
      </div>
    </section>

    <!-- Área de questões -->
    <section class="questoes" id="area-questoes">
      <h4 style="text-align:center;margin:0;color:#2a3a4a;font-size:18px">Questões</h4>
      <div class="top-acoes">
        <button type="button" class="btn btn-add" onclick="adicionarQuestao()">Adicionar questão</button>
        <button type="button" class="btn btn-ghost" onclick="window.print()">Imprimir</button>
      </div>
      <div id="lista-questoes"></div>
    </section>

    <!-- Gabarito (ENEM, sem respostas) -->
    <section class="gabarito" id="gabarito">
      <h3>GABARITO</h3>
      <div class="gabarito-grid" id="gabaritoGrid"></div>
    </section>

    <footer><small>Entregue: folhas de questões + gabarito...</small></footer>
  </div>

  <script>
    const lista   = document.getElementById('lista-questoes');
    const gabGrid = document.getElementById('gabaritoGrid');

        // Cria uma tag com botão "x"
    function createTag(label){
      const tag = document.createElement('span');
      tag.className = 'tag';
      tag.append(document.createTextNode(label));

      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'tag-x';
      btn.setAttribute('aria-label', 'Remover ' + label);
      btn.textContent = '×';
      tag.appendChild(btn);

      return tag;
    }

    // Converte tags existentes no HTML (Português, Gramática) adicionando o "x"
    function enhanceExistingTags(){
      document.querySelectorAll('#tags .tag').forEach(t=>{
        if(!t.querySelector('.tag-x')){
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'tag-x';
          btn.setAttribute('aria-label', 'Remover ' + t.textContent.trim());
          btn.textContent = '×';
          t.appendChild(btn);
        }
      });
    }

    // Adiciona nova tag a partir do input
    function addTag(){
      const inp = document.getElementById('tagInput');
      const val = (inp.value || '').trim();
      if(!val) return;

      const tag = createTag(val);
      document.getElementById('tags').appendChild(tag);
      inp.value = '';
    }

    // Delegação de clique para remover ao pressionar o "x"
    document.getElementById('tags').addEventListener('click', (e)=>{
      if(e.target.classList.contains('tag-x')){
        e.target.closest('.tag')?.remove();
      }
    });

    // Ao carregar a página, adiciona "x" nas tags já existentes
    enhanceExistingTags();

    function criarBlocoQuestao(
      titulo='Sem título',
      enunciado='Descreva o enunciado aqui...',
      alternativas=['Alternativa A','Alternativa B','Alternativa C','Alternativa D']
    ){
      const el = document.createElement('div');
      el.className = 'questao';
      el.innerHTML = `
        <div class="questao-head">
          <span class="badge-num">Questão <span class="numero">1</span></span>
          <div class="controles">
            <button type="button" class="btn btn-add" onclick="adicionarQuestaoDepois(this)">Adicionar questão</button>
            <button type="button" class="btn btn-remove" onclick="removerQuestao(this)">Remover</button>
          </div>
        </div>
        <input type="text" class="titulo-questao" value="${escapeHtml(titulo)}">
        <textarea class="enunciado" rows="3">${escapeHtml(enunciado)}</textarea>
        <div class="alternativas">
          ${['A','B','C','D'].map((L,i)=>`
            <div class="alt">
              <span class="letra">${L}</span>
              <input type="radio" class="alt-radio" name="tmpName" value="${L}">
              <input type="text" class="alt-texto" value="${escapeHtml(alternativas[i]||'')}" placeholder="Texto da alternativa">
            </div>
          `).join('')}
        </div>
      `;
      return el;
    }

    function adicionarQuestao(){
      const q = criarBlocoQuestao();
      lista.appendChild(q);
      renumerarQuestoes();
      scrollAte(q);
      construirGabarito();
      sincronizarGabarito();
      atualizarDivisoria();
    }
    function adicionarQuestaoDepois(btn){
      const atual = btn.closest('.questao');
      if (!atual) return;
      const nova = criarBlocoQuestao();
      if (atual.nextSibling) lista.insertBefore(nova, atual.nextSibling);
      else lista.appendChild(nova);
      renumerarQuestoes();
      scrollAte(nova);
      construirGabarito();
      sincronizarGabarito();
      atualizarDivisoria();
    }
    function removerQuestao(btn){
      btn.closest('.questao')?.remove();
      renumerarQuestoes();
      construirGabarito();
      sincronizarGabarito();
      atualizarDivisoria();
    }

    function renumerarQuestoes(){
      [...lista.querySelectorAll('.questao')].forEach((q, i)=>{
        q.querySelector('.numero').textContent = i+1;
        q.querySelectorAll('.alt-radio').forEach(r=> r.name = 'q'+(i+1));
      });
    }

    function construirGabarito(){
      gabGrid.innerHTML = '';
      const total = lista.querySelectorAll('.questao').length;
      for(let i=1;i<=total;i++){
        const item = document.createElement('div');
        item.className = 'gab-item';
        item.setAttribute('data-q','q'+i);
        item.innerHTML = `
          <span class="gab-num">${i}</span>
          <div class="gab-bubbles">
            ${['A','B','C','D'].map(L=>`<div class="bubble" data-alt="${L}">${L}</div>`).join('')}
          </div>
        `;
        gabGrid.appendChild(item);
      }
    }

    function sincronizarGabarito(){
      [...gabGrid.querySelectorAll('.gab-item')].forEach(item=>{
        item.querySelectorAll('.bubble').forEach(b=>b.classList.remove('sel'));
        const qname = item.getAttribute('data-q');
        const marcado = lista.querySelector(`.alt-radio[name="${qname}"]:checked`);
        if (marcado){
          const letra = marcado.value;
          const alvo = item.querySelector(`.bubble[data-alt="${letra}"]`);
          if (alvo) alvo.classList.add('sel');
        }
      });
    }

    function atualizarDivisoria(){
      const tem = lista.querySelectorAll('.questao').length>0;
      document.getElementById('lista-questoes').classList.toggle('tem-questoes', tem);
    }

    function escapeHtml(s){ return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m])); }
    function scrollAte(el){ el.scrollIntoView({behavior:'smooth', block:'center'}); }

    /* ---------- AUTO-LARGURA SOMENTE PARA O CAMPO "CURSO" ---------- */
    (function autoWidthCurso(){
      const cursoInput = document.getElementById('curso');
      if (!cursoInput) return;

      function medirTextoLargura(el){
        // cria um espelho invisível com mesma fonte e estilo
        const s = document.createElement('span');
        const cs = getComputedStyle(el);
        s.style.position = 'fixed';
        s.style.visibility = 'hidden';
        s.style.whiteSpace = 'pre';
        s.style.font = cs.font;
        s.style.letterSpacing = cs.letterSpacing;
        s.textContent = el.value || '';
        document.body.appendChild(s);
        const w = s.getBoundingClientRect().width;
        s.remove();
        return w;
      }

      function ajustar(){
        const paddingExtra = 24; // respiro à direita
        const w = medirTextoLargura(cursoInput) + paddingExtra;
        cursoInput.style.width = Math.max(120, Math.ceil(w)) + 'px';
      }

      ajustar();                         // ao carregar
      cursoInput.addEventListener('input', ajustar);
    })();

    // Inicial (exemplos de questões)
    adicionarQuestao();
    adicionarQuestao();

    // Observa mudanças de quantidade
    const obs = new MutationObserver(()=>{ renumerarQuestoes(); construirGabarito(); sincronizarGabarito(); atualizarDivisoria(); });
    obs.observe(lista, {childList:true, subtree:false});

    // Evita “vazar” conteúdo em inputs/textarea das questões
    document.addEventListener('input', e=>{
      if (e.target.matches('#curso')) return; // deixa o curso com lógica própria
      if (e.target.matches('input[type="text"], textarea')) e.target.style.width = '100%';
    });
  </script>
</body>
</html>

<?php
/**
 * -------------------------------------------------------------------------
 * Página de Prova com Questões Dinâmicas
 * -------------------------------------------------------------------------
 * Novidade deste envio:
 * - Botão **“Adicionar questão”** dentro de cada questão (insere logo abaixo).
 * - Mantido o botão global “Adicionar questão” no topo.
 *
 * Funcionalidades:
 * - Adicionar questões (topo e por questão)
 * - Editar título, enunciado e alternativas
 * - Remover alternativas individualmente (garante ao menos 1)
 * - Remover questões inteiras
 * - Autonumeração automática das questões
 * - Botões principais: adicionar questão, inserir exemplo, imprimir
 * - **NOVO:** Salvar e voltar (grava no banco e volta à listagem)
 * -------------------------------------------------------------------------
 */

// Inclui o arquivo de configuração e conexão com o banco (PDO em $pdo)
include_once __DIR__ . '/config.php';

// Lê parâmetro opcional ?id=..., usado para buscar título no banco
$id = $_GET['id'] ?? null;

// Títulos e valores padrão (caso não exista registro no banco)
$formulario_titulo = 'PROVÃO 1º SEMESTRE DE 2025';
$curso_padrao      = 'Técnico em SECRETARIADO';
$turma_padrao      = '2º ANO';

// Se o ID foi informado, tenta buscar o título no banco
if ($id) {
    $stmt = $pdo->prepare("SELECT titulo FROM formularios WHERE id = :id");
    $stmt->execute([':id' => $id]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Se encontrou título no banco, usa ele
        $formulario_titulo = $row['titulo'] ?: $formulario_titulo;
    }
}

// URL da tela de listagem (ajuste se a sua rota for diferente)
$URL_FORMULARIOS = 'formularios.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($formulario_titulo) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Fonte Google Inter para visual mais moderno -->
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />

  <style>
    /* ---------- Variáveis de cor ---------- */
    :root{
      --azul:#1c3b5a;
      --cinza:#f2f2f2;
      --tinta:#333;
      --borda:#e6e6e6;
      --realce:#eef3f8;
    }

    /* ---------- Reset básico ---------- */
    *{box-sizing:border-box}
    html,body{
      margin:0;padding:0;
      font-family:Inter,system-ui,Arial,sans-serif;
      color:var(--tinta);
      background:var(--cinza)
    }

    /* ---------- Container principal ---------- */
    .wrapper{
      max-width:900px;
      margin:40px auto;
      padding:32px;
      background:#fff;
      border-radius:12px;
      box-shadow:0 4px 16px rgba(0,0,0,.06)
    }

    /* ---------- Cabeçalho da prova ---------- */
    header.prova{
      text-align:center;
      margin-bottom:24px;
      padding-bottom:16px;
      border-bottom:2px solid var(--borda)
    }
    .marca{
      font-weight:800;
      letter-spacing:.4px;
      color:var(--azul);
      font-size:22px;
      margin-bottom:8px
    }
    .titulo{
      font-size:26px;
      font-weight:800;
      margin:6px 0;
      color:#111
    }
    .sub{
      display:flex;
      gap:16px;
      flex-wrap:wrap;
      justify-content:center;
      color:#444;
      font-weight:600
    }

    /* ---------- Linha com turma, data, sala ---------- */
    .linha-info{
      display:grid;
      grid-template-columns:1.2fr .8fr .8fr;
      gap:12px;
      margin:18px 0 6px;
    }
    .campo{
      display:flex;
      gap:8px;
      align-items:center;
      border:1px solid var(--borda);
      border-radius:10px;
      padding:10px 12px
    }
    .campo label{
      white-space:nowrap;
      font-weight:600;
      color:#222
    }
    .campo input{
      border:0;
      outline:0;
      width:100%;
      font-size:15px;
      padding:6px 0;
      background:transparent
    }

    /* ---------- Campo nome do aluno ---------- */
    .linha-nome{margin-top:8px}
    .campo-nome{
      display:flex;
      gap:8px;
      align-items:center;
      border:1px solid var(--borda);
      border-radius:10px;
      padding:10px 12px
    }
    .campo-nome input{
      border:0;
      outline:0;
      width:100%;
      font-size:16px;
      padding:6px 0
    }

    /* ---------- Blocos de instruções e informações ---------- */
    .bloco{
      margin-top:28px;
      border:1px solid var(--borda);
      border-radius:12px;
      padding:18px 18px 8px
    }
    .bloco h3{
      margin:0 0 10px;
      font-size:18px;
      color:var(--azul);
      font-weight:800;
      letter-spacing:.3px
    }
    .bloco ol, .bloco ul{
      margin:0 0 12px 18px;
      padding-left:16px
    }
    .bloco li{margin:8px 0;line-height:1.5}

    .observacoes{
      display:grid;
      gap:10px;
      margin-top:10px
    }
    .observacoes .linha{
      display:flex;
      gap:10px;
      flex-wrap:wrap
    }
    .observacoes .pill{
      background:#eef3f8;
      color:#19344f;
      border:1px solid #d7e2ec;
      border-radius:999px;
      padding:8px 14px;
      font-weight:600
    }

    /* ---------- Área de questões ---------- */
    .questoes{
      margin-top:28px;
      border:2px dashed #cfd8e3;
      border-radius:12px;
      padding:18px;
      background:linear-gradient(0deg,#fafcff,#ffffff)
    }
    .questoes .top-acoes{
      display:flex;
      gap:10px;
      flex-wrap:wrap;
      justify-content:center;
      margin:10px 0 18px
    }

    /* ---------- Questão individual ---------- */
    .questao{
      border:1px solid #dfe7f0;
      border-radius:12px;
      padding:14px 16px;
      margin:14px 0;
      background:#fff
    }
    .questao-head{
      display:flex;
      gap:10px;
      align-items:center;
      justify-content:space-between;
      flex-wrap:wrap;
      margin-bottom:10px
    }
    .questao-info{
      display:flex;
      align-items:center;
      gap:10px;
      flex-wrap:wrap
    }
    .badge-num{
      background:var(--realce);
      border:1px solid #d7e2ec;
      color:#19344f;
      font-weight:800;
      border-radius:8px;
      padding:6px 10px;
      min-width:64px;
      text-align:center
    }
    .titulo-wrap{
      display:flex;
      gap:6px;
      align-items:center
    }
    .titulo-wrap label{
      font-weight:700;
      color:#20364f
    }
    .titulo-questao{
      border:1px solid var(--borda);
      border-radius:8px;
      padding:8px 10px;
      min-width:240px;
      font-weight:600
    }

    /* ---------- Enunciado e alternativas ---------- */
    .enunciado{
      width:100%;
      border:1px solid var(--borda);
      border-radius:10px;
      padding:10px 12px;
      margin:8px 0;
      font-size:15px
    }
    .alternativas{margin-top:8px}
    .alternativas .alt{
      display:flex;
      align-items:center;
      gap:8px;
      margin:8px 0
    }
    .alternativas .alt input[type="text"]{
      flex:1;
      border:1px solid var(--borda);
      border-radius:8px;
      padding:8px 10px
    }

    /* ---------- Botões ---------- */
    button, .btn{
      appearance:none;
      border:1px solid #23405a;
      background:#23405a;
      color:#fff;
      padding:10px 14px;
      border-radius:10px;
      cursor:pointer;
      font-weight:700;
      transition:transform .15s ease, box-shadow .15s ease, opacity .2s ease
    }
    button:hover, .btn:hover{
      transform:translateY(-2px);
      box-shadow:0 10px 18px rgba(0,0,0,.08)
    }
    .btn-outline{background:#fff;color:#23405a}
    .btn-ghost{background:transparent;border-color:#d7e2ec;color:#23405a}

    /* ---------- Rodapé ---------- */
    footer{
      margin-top:26px;
      text-align:center;
      color:#6b7785;
      font-size:13px
    }

    /* ---------- Impressão: esconde botões ---------- */
    @media print{
      body{background:#fff}
      .wrapper{box-shadow:none;margin:0;padding:0;border-radius:0}
      .questoes{border:0;padding:0;background:#fff}
      .questoes .top-acoes,
      .btn,
      .btn-outline,
      .btn-ghost,
      .controles,
      .controles *{display:none!important}
      input, textarea{border:0!important}
      a[href]:after{content:""}
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Cabeçalho da prova -->
    <header class="prova">
      <div class="marca">PROVÃO</div>
      <div class="titulo"><?= htmlspecialchars($formulario_titulo) ?></div>
      <div class="sub"><div>CURSO: <strong><?= htmlspecialchars($curso_padrao) ?></strong></div></div>
      <div class="linha-info">
        <div class="campo"><label for="turma">Turma:</label><input id="turma" name="turma" type="text" placeholder="<?= htmlspecialchars($turma_padrao) ?>" /></div>
        <div class="campo"><label for="data">Data:</label><input id="data" name="data" type="text" placeholder="__/__/____" /></div>
        <div class="campo"><label for="sala">Sala:</label><input id="sala" name="sala" type="text" placeholder="____" /></div>
      </div>
      <div class="linha-nome">
        <div class="campo-nome"><label for="aluno">Nome Completo do (a) Aluno (a):</label><input id="aluno" name="aluno" type="text" placeholder="Digite seu nome completo" /></div>
      </div>
    </header>

    <!-- Instruções gerais -->
    <section class="bloco">
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

    <!-- Sobre a avaliação -->
    <section class="bloco">
      <h3>SOBRE ESTA AVALIAÇÃO</h3>
      <p>Esta prova abrange conteúdos...</p>
      <div class="observacoes">
        <div class="linha">
          <span class="pill">Folhas de Questões</span>
          <span class="pill">Folha de Gabarito</span>
          <span class="pill">Caneta Azul/Preta</span>
          <span class="pill">Silêncio</span>
        </div>
      </div>
    </section>

    <!-- Área dinâmica de questões -->
    <section class="questoes" id="area-questoes">
      <h4 style="text-align:center;margin:0;color:#2a3a4a;font-size:18px">Questões</h4>
      <div class="top-acoes">
        <button type="button" class="btn" onclick="adicionarQuestao()">Adicionar questão</button>
        <button type="button" class="btn btn-outline" onclick="inserirExemplo()">Inserir questão de EXEMPLO</button>
        <button type="button" class="btn btn-ghost" onclick="window.print()">Imprimir</button>
        <!-- NOVO: Salvar e voltar -->
        <button type="button" class="btn" style="background:#1b7f47;border-color:#1b7f47" onclick="salvarEVoltar()">Salvar e voltar</button>
      </div>
      <div id="lista-questoes"></div>
    </section>

    <footer><small>Entregue: folhas de questões + gabarito...</small></footer>
  </div>

  <script>
    // ---------------- Variáveis vindas do PHP ----------------
    const FORM_ID   = <?= $id ? (int)$id : 'null' ?>;
    const LISTA_URL = <?= json_encode($URL_FORMULARIOS) ?>;

    // Container onde as questões serão inseridas
    const lista = document.getElementById('lista-questoes');

    /**
     * Cria o HTML de uma questão.
     * Agora com dois botões no cabeçalho da questão:
     *  - “Adicionar questão” (insere nova questão logo ABAIXO)
     *  - “Remover” (remove a questão atual)
     */
    function criarBlocoQuestao(
      titulo='Sem título',
      enunciado='Descreva o enunciado aqui...',
      alternativas=['Alternativa A','Alternativa B','Alternativa C','Alternativa D']
    ) {
      const idxRadio = gerarIdUnico();
      const bloco = document.createElement('div');
      bloco.className = 'questao';
      bloco.innerHTML = `
        <div class="questao-head">
          <div class="questao-info">
            <span class="badge-num">Questão <span class="numero">1</span></span>
            <div class="titulo-wrap">
              <label>Título:</label>
              <input type="text" class="titulo-questao" value="${escapeHtml(titulo)}" />
            </div>
          </div>
          <div class="controles">
            <button type="button" class="btn-ghost" onclick="adicionarQuestaoDepois(this)">Adicionar questão</button>
            <button type="button" class="btn-ghost" onclick="removerQuestao(this)">Remover</button>
          </div>
        </div>
        <textarea class="enunciado" rows="3" placeholder="Enunciado da questão...">${escapeHtml(enunciado)}</textarea>
        <div class="alternativas">
          ${alternativas.map((txt)=>`
            <div class="alt">
              <input type="radio" name="${idxRadio}">
              <input type="text" value="${escapeHtml(txt)}" placeholder="Texto da alternativa">
              <button type="button" class="btn-ghost" onclick="removerAlternativa(this)">x</button>
            </div>
          `).join('')}
        </div>
      `;
      return bloco;
    }

    // Adiciona questão vazia ao final (botão do topo)
    function adicionarQuestao(){
      const bloco = criarBlocoQuestao();
      lista.appendChild(bloco);
      renumerarQuestoes();
      scrollAte(bloco);
    }

    // Adiciona questão de exemplo (botão do topo)
    function inserirExemplo(){
      const bloco = criarBlocoQuestao('Exemplo', 'Enunciado da questão...', ['Alternativa A','Alternativa B','Alternativa C','Alternativa D']);
      lista.appendChild(bloco);
      renumerarQuestoes();
      scrollAte(bloco);
    }

    // NOVO: Adiciona questão vazia logo ABAIXO da questão atual
    function adicionarQuestaoDepois(btn){
      const atual = btn.closest('.questao');
      if (!atual) return;
      const nova = criarBlocoQuestao();
      // insere depois do bloco atual
      if (atual.nextSibling){
        lista.insertBefore(nova, atual.nextSibling);
      } else {
        lista.appendChild(nova);
      }
      renumerarQuestoes();
      scrollAte(nova);
    }

    // Remove a questão inteira
    function removerQuestao(btn){
      const bloco = btn.closest('.questao');
      if (!bloco) return;
      bloco.remove();
      renumerarQuestoes();
    }

    // Remove alternativa, garantindo pelo menos 1
    function removerAlternativa(btn){
      const alt = btn.closest('.alt');
      if (!alt) return;
      const grupo = alt.parentElement;
      alt.remove();
      if (grupo.children.length === 0){
        const radioName = gerarIdUnico();
        const nova = document.createElement('div');
        nova.className = 'alt';
        nova.innerHTML = `
          <input type="radio" name="${radioName}">
          <input type="text" value="" placeholder="Texto da alternativa">
          <button type="button" class="btn-ghost" onclick="removerAlternativa(this)">x</button>
        `;
        grupo.appendChild(nova);
      }
    }

    // Renumera as questões após qualquer modificação estrutural
    function renumerarQuestoes(){
      lista.querySelectorAll('.questao').forEach((b, i) => {
        const spanNum = b.querySelector('.numero');
        if (spanNum) spanNum.textContent = i + 1;
      });
    }

    // ---------------- NOVO: Coleta e salva no backend ----------------
    function coletarQuestoes(){
      const questoes = [];
      const blocos = lista.querySelectorAll('.questao');
      blocos.forEach((b, i) => {
        const titulo = (b.querySelector('.titulo-questao')?.value ?? '').trim();
        const enun   = (b.querySelector('.enunciado')?.value ?? '').trim();
        const altsEls = b.querySelectorAll('.alternativas .alt');

        // Descobrir qual radio está marcado
        let corretaIdx = -1;
        altsEls.forEach((row, idx) => {
          const radio = row.querySelector('input[type="radio"]');
          if (radio && radio.checked) corretaIdx = idx;
        });

        const alternativas = [];
        altsEls.forEach((row, idx) => {
          const texto = (row.querySelector('input[type="text"]')?.value ?? '').trim();
          if (texto === '') return; // ignora vazias
          alternativas.push({
            ordem: idx + 1,
            texto: texto,
            correta: (idx === corretaIdx)
          });
        });

        questoes.push({
          ordem: i + 1,
          titulo: titulo,
          enunciado: enun,
          alternativas: alternativas
        });
      });
      return questoes;
    }

    async function salvarEVoltar(){
      const payload = {
        form_id: FORM_ID,
        titulo: document.querySelector('.titulo')?.textContent?.trim() || 'Formulário',
        turma:  document.getElementById('turma')?.value || null,
        data:   document.getElementById('data')?.value || null,
        sala:   document.getElementById('sala')?.value || null,
        aluno:  document.getElementById('aluno')?.value || null,
        questoes: coletarQuestoes()
      };

      try {
        const resp = await fetch('salvar_questoes.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify(payload)
        });
        const json = await resp.json();
        if (!resp.ok || !json.ok) {
          throw new Error(json.error || 'Falha ao salvar.');
        }
        const destino = json.redirect || LISTA_URL || 'formularios.php';
        window.location.href = destino;
      } catch (err) {
        alert('Erro ao salvar: ' + (err.message || err));
        console.error(err);
      }
    }

    // Utilitários
    function gerarIdUnico(){
      return 'q' + Math.random().toString(36).slice(2);
    }
    function escapeHtml(s){
      return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
    }
    function scrollAte(el){
      el.scrollIntoView({behavior:'smooth', block:'center'});
    }
  </script>
</body>
</html>

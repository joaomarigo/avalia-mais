<?php
// php/coordenador/painel.php
session_start();
require_once __DIR__ . '/../config.php';

if (strtolower($_SESSION['cargo'] ?? '') !== 'coordenador') {
  http_response_code(403);
  exit('Acesso restrito a coordenadores.');
}

if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
$csrf = $_SESSION['csrf'];

// nome (cache igual padrão do teu projeto)
$nomeUsuario = $_SESSION['nome'] ?? null;
if (!$nomeUsuario) {
  $usuarioId    = $_SESSION['usuario_id'] ?? null;
  $usuarioEmail = $_SESSION['email']      ?? null;
  if ($usuarioId) {
    $st = $pdo->prepare('SELECT nome FROM usuarios WHERE id=:id LIMIT 1');
    $st->execute([':id'=>$usuarioId]);
  } elseif ($usuarioEmail) {
    $st = $pdo->prepare('SELECT nome FROM usuarios WHERE email=:e LIMIT 1');
    $st->execute([':e'=>$usuarioEmail]);
  }
  if (!empty($st) && ($r=$st->fetch(PDO::FETCH_ASSOC))) {
    $nomeUsuario = $r['nome'] ?? null;
    if ($nomeUsuario) $_SESSION['nome'] = $nomeUsuario;
  }
}

$primeiroNome = $nomeUsuario ? explode(' ', trim($nomeUsuario))[0] : 'Coordenador';
$cargoSessao  = strtolower($_SESSION['cargo'] ?? 'coordenador');
$cargoLabel   = ucfirst($cargoSessao);

?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Painel do Coordenador - Avalia+</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body{background:#f6f9ff}
    .chip{background:#e9f1ff;border-radius:14px;padding:2px 10px;font-size:.9rem}

    :root{--azul:#1a3a7c;}
    .modal-addprof{display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); align-items:center; justify-content:center; z-index:1500}
    .modal-addprof .box{background:#fff; border-radius:16px; width:980px; max-width:95vw; max-height:85vh; overflow:auto; padding:28px; box-shadow:0 10px 26px rgba(0,0,0,.18)}
    .modal-addprof h2{color:var(--azul); font-size:26px; margin-bottom:16px; border-bottom:2px solid #cfd6e4; padding-bottom:8px}
    .grid-add{display:grid; grid-template-columns: repeat(2, minmax(240px, 1fr)); gap:22px 28px}
    @media (max-width:860px){.grid-add{grid-template-columns:1fr}}
    .input_line,.select_line,.materias-line{width:100%; border:none; outline:none; background:transparent; border-bottom:2px solid var(--azul); padding:10px 6px; font-size:16px; color:var(--azul)}
    .input_line::placeholder{color:var(--azul); opacity:.65}
    .select_line{appearance:none; background-image:url("data:image/svg+xml,%3Csvg fill='gray' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; background-size:20px}
    .materias-wrap{grid-column:1/-1}
    .mchips{display:flex; flex-wrap:wrap; gap:8px; min-height:44px; align-items:center; color:var(--azul)}
    .chip{background:#e6edf5; color:var(--azul); padding:6px 12px; border-radius:20px; display:flex; align-items:center; font-size:14px}
    .chip span{margin-left:8px; cursor:pointer; font-weight:bold}
    .btn-cadastrar{grid-column:1/-1; justify-self:start; margin-top:6px; height:42px; width:180px; font-size:18px; background:transparent; color:var(--azul); border:2px solid var(--azul); border-radius:25px; cursor:pointer; transition:.25s}
    .btn-cadastrar:hover{background:var(--azul); color:#fff}
    .btn-fechar{position:absolute; top:10px; right:14px; font-size:22px; color:#777; background:transparent; border:none}
    .alert-inline{grid-column:1/-1; padding:10px 12px; border-radius:10px; font-size:14px; display:none}
    .alert-ok{background:#e8f7ef; border:1px solid #b5e2c5; color:#1d6b3a}
    .alert-err{background:#fdeaea; border:1px solid #f1b5b5; color:#7a1f1f}

    .modal-materias { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45);
      align-items:center; justify-content:center; z-index:2000; }
    .modal-materias .modal-content { background:#fff; border-radius:16px; width:720px; max-width:92vw;
      max-height:80vh; overflow:auto; padding:26px 24px; box-shadow:0 10px 26px rgba(0,0,0,.18); }
    .modal-materias h3 { text-align:center; color:#1a3a7c; margin-bottom:14px; }
    .materia-btn { display:inline-block; background:#e9eef7; color:#1a3a7c; padding:10px 18px;
      border-radius:20px; margin:8px; cursor:pointer; font-size:15px; user-select:none; }
    .materia-btn.ativo { background:#bcd0ff; }
    .materias-line.clickable { cursor:pointer; }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 25px 60px;
      width: 100%;
      z-index: 10;
      position: relative;
    }

    .header-left { display: flex; align-items: center; gap: 15px; }
    .hamburger { font-size: 28px; cursor: pointer; color: #1a3a7c; }
    .capelo { width: 60px; }
    .foto-perfil { width: 45px; cursor: pointer; border-radius: 50%; }

    .sidebar {
      position: fixed;
      top: 0;
      left: -250px;
      width: 250px;
      height: 100%;
      background-color: #1a3a7c;
      color: white;
      padding: 20px;
      display: flex;
      flex-direction: column;
      transition: left 0.3s ease;
      z-index: 1000;
      box-shadow: 4px 0 15px rgba(0, 0, 0, 0.3);
    }
    .sidebar.active { left: 0; }
    .sidebar-header { display: flex; align-items: center; gap: 10px; margin-bottom: 40px; }
    .sidebar-header .close-btn { font-size: 28px; cursor: pointer; }
    .sidebar-header img { height: 28px; object-fit: contain; }
    .sidebar a { color: white; text-decoration: none; font-size: 18px; margin: 5px 0; padding: 10px; border-radius: 8px; transition: background 0.2s; }
    .sidebar a:hover { background-color: rgba(255, 255, 255, 0.2); }
    .sidebar .logout { margin-top: auto; display: flex; align-items: center; gap: 10px; font-size: 18px; cursor: pointer; color: white; padding: 10px; border-radius: 8px; transition: background 0.2s; }
    .sidebar .logout i { font-size: 20px; }
    .sidebar .logout:hover { background-color: rgba(255,255,255,0.2); }


    .menu-perfil {
      position: fixed;
      top: 0;
      right: -400px;
      width: 350px;
      height: 100vh;
      background: #f8f9fb;
      box-shadow: -4px 0 15px rgba(0,0,0,0.2);
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 30px 20px;
      transition: right 0.3s ease;
      z-index: 2000;
    }
    .menu-perfil.active { right: 0; }
    .menu-perfil .close-btn {
      position: absolute;
      top: 15px;
      left: 15px;
      font-size: 22px;
      color: gray;
      cursor: pointer;
    }
    .menu-perfil .foto-grande {
      width: 120px; height: 120px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 15px;
      background: #1a3a7c;
      margin-top: 40px;
    }
    .menu-perfil .cargo {
      font-size: 16px;
      color: #444;
      margin-bottom: 10px;
    }
    .menu-perfil .btn-editar {
      border: 2px solid #1a3a7c;
      border-radius: 20px;
      padding: 6px 20px;
      background-color: transparent;
      color: #1a3a7c;
      cursor: pointer;
      margin-bottom: 30px;
      transition: 0.2s;
    }
    .menu-perfil .btn-editar:hover { background: #1a3a7c; color: white; }
    .menu-perfil .close-btn { font-size: 30px;}
    .menu-perfil .calendario { width: 100%; margin: 20px 0; text-align: center; }
    .menu-perfil .calendario table { width: 100%; border-collapse: collapse; color: #7a8ba0; }
    .menu-perfil .calendario th, .menu-perfil .calendario td { padding: 6px; font-size: 14px; }
    .menu-perfil .calendario h3 { color: #7a8ba0; margin-bottom: 10px; text-align: center; }
    .menu-perfil .logout {
      margin-top: auto;
      margin-bottom: 10px;
      color: #d33;
      font-size: 16px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .menu-perfil .nome-usuario { font-size: 18px; color: #223; margin-bottom: 4px; }
    .menu-perfil .cargo        { font-size: 13px; color: #7a8ba0; margin-bottom: 10px; }

.tabela-container {
  margin: 20px 10% 0 10%;
  background: transparent;
  border-radius: 12px;
  padding: 20px;
}

.tabela-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}
.busca-wrap {
  display: flex;
  align-items: center;
  background: #e6efff;
  border-radius: 8px;
  padding: 6px 10px;
  width: 320px;
}
.busca-wrap i {
  color: #1a3a7c;
  margin-right: 8px;
}
.busca-wrap input {
  border: none;
  outline: none;
  flex: 1;
  background: transparent;
  font-size: 15px;
  color: #1a3a7c;
}
.acoes-wrap {
  display: flex;
  gap: 10px;
}
.btn-acao {
  background: #e6efff;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  color: #1a3a7c;
  cursor: pointer;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 5px;
}
.btn-acao:hover { background:#d2e0ff; }

.tabs-custom {
  display: flex;
  gap: 20px;
  border-bottom: 2px solid #ccc;
  margin-bottom: 10px;
}
.tab-link {
  background: none;
  border: none;
  font-size: 16px;
  padding: 8px 0;
  cursor: pointer;
  color: #7a8ba0;
  position: relative;
}
.tab-link.active {
  color: #1a3a7c;
  font-weight: 600;
}
.tab-link.active::after {
  content: '';
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 100%;
  height: 2px;
  background: #1a3a7c;
}
.tab-content { display: none; }
.tab-content.active { display: block; }

.tabela-dados {
  width: 100%;
  border-collapse: collapse;
}
.tabela-dados th {
  text-align: left;
  padding: 12px;
  color: #1a3a7c;
  font-weight: 500;
}
.tabela-dados td {
  padding: 12px;
  color: #333;
  border-top: 1px solid #eee;
}
.tabela-dados tr:hover {
  background: #e6efff;
}
.tabela-dados .avatar {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  background: #1a3a7c;
  display: inline-block;
  margin-right: 8px;
  vertical-align: middle;
}
.text-end { text-align: right; }

.tabs-header {
  margin-bottom: 20px;
}

.tabela-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  margin-top: 10px;
}

.tabela-actions .busca-wrap {
  flex-shrink: 0;
}

.tabela-actions .acoes-wrap {
  display: flex;
  gap: 10px;
}
.btn-adicionar-prof {
    background-color: #12337b;
    color: #fff;
    border: 2px solid #12337b;
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: background 0.25s;
}

.btn-adicionar-prof:hover {
    background-color: #0f275b;
}
  </style>
</head>
<body>

  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <i class="fas fa-bars close-btn" id="closeSidebar"></i>
      <img src="../../assets/logo_azulclaro.png" alt="Avalia+">
    </div>
    <a href="../inicio.php">Inicio</a>
    <?php if ($cargoSessao !== 'professor'): ?>
      <a href="../../php/coordenador/painel.php">Painel Coordenador</a>
    <?php endif; ?>
    <a href="../comousar.php">Como Usar</a>
    <a href="../logout.php" class="logout" id="logoutBtn"><i class="fas fa-door-open"></i> Logout</a>
  </div>

  <div class="header">
    <div class="header-left">
      <i class="fas fa-bars hamburger" id="hamburger"></i>
      <img class="capelo" src="../../assets/capelo_azul.png" alt="Ícone formatura">
    </div>

    <img class="foto-perfil" id="perfilBtn" src="../../assets/perfil_padrao.png" alt="Perfil">
  </div>

  <div class="menu-perfil" id="menuPerfil">
    <i class="fas fa-times close-btn" id="closePerfil"></i>
    <img src="../../assets/perfil_padrao.png" alt="Perfil" class="foto-grande">
    <p class="nome-usuario"><?= htmlspecialchars($primeiroNome) ?></p>
    <p class="cargo"><?= htmlspecialchars($cargoLabel) ?></p>

    <button class="btn-editar" onclick="window.location.href='../editar_perfil.php'">Editar</button>

    <div class="calendario" id="calendario"></div>
  </div>



<div class="tabela-container">

<div class="tabs-header">
  <div class="tabs-custom">
    <button class="tab-link active" data-tab="prof">Professor</button>
    <button class="tab-link" data-tab="mat">Matéria</button>
  </div>
</div>

<div class="tabela-actions" style="display:flex; justify-content:space-between; align-items:center; margin:10px 0; width:100%;">
  <div class="busca-wrap" style="width:320px;">
    <i class="fas fa-search"></i>
    <input type="text" id="searchInputTab" placeholder="Pesquise por professor ou matéria">
  </div>
  <div class="acoes-wrap" style="display:flex; gap:10px;">
    <button class="btn-acao"><i class="fas fa-sort"></i> Ordenar</button>
    <button class="btn-acao"><i class="fas fa-filter"></i> Filtrar</button>
    <button class="btn-adicionar-prof" id="btnAbrirAddProf" type="button">Adicionar Professor</button>

  </div>
</div>



  <div id="tab-prof" class="tab-content active">
    <table class="tabela-dados">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Email</th>
          <th>Escola</th>
          <th class="text-end">Ações</th>
        </tr>
      </thead>
      <tbody id="tbodyGestaoProf">
        <tr><td colspan="4">Carregando...</td></tr>
      </tbody>
    </table>
  </div>

  <div id="tab-mat" class="tab-content">
    <table class="tabela-dados">
      <thead>
        <tr>
          <th>Matéria</th>
          <th>Qtd de provas</th>
        </tr>
      </thead>
      <tbody id="tbodyMat">
        <tr><td colspan="2">Carregando...</td></tr>
      </tbody>
    </table>
  </div>
</div>

<div class="modal-addprof" id="modalAddProf">
  <div class="box position-relative">
    <button class="btn-fechar" id="btnFecharAddProf" aria-label="Fechar">&times;</button>
    <h2>Cadastrar Professor</h2>

    <form id="formAddProf" class="grid-add">
      <input type="hidden" name="csrf" value="<?=$csrf?>">
      <input type="hidden" name="materias" id="ap_materiasHidden" value="">

      <div><input class="input_line" type="text" name="nome" placeholder="Nome Completo" required></div>
      <div><input class="input_line" type="email" name="email" placeholder="Email" required></div>
      <div><input class="input_line" type="text" name="escola" placeholder="Escola" required></div>
      <div><input class="input_line" type="password" name="senha" placeholder="Senha (mín. 6)" minlength="6" required></div>

      <div>
        <select class="select_line" name="cargo" id="ap_cargo" required>
          <option value="Professor" selected>Professor</option>
          <option value="Coordenador" disabled>Coordenador</option>
        </select>
      </div>
      <div></div>

      <div class="materias-wrap">
        <div class="materias-line mchips clickable" id="ap_materiasSelecionadas">
          Matérias (clique para selecionar)
        </div>
      </div>

      <div class="alert-inline alert-err" id="ap_alertErr"></div>
      <div class="alert-inline alert-ok"  id="ap_alertOk"></div>

      <button class="btn-cadastrar" type="submit">Cadastrar</button>
    </form>
  </div>
</div>

<div class="modal-materias" id="ap_modalMaterias">
  <div class="modal-content">
    <h3>Matérias</h3>
    <div id="ap_listaMaterias">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
const API_BASE = "<?= dirname($_SERVER['SCRIPT_NAME']) ?>"; 

const URL_GET_PROF  = `${API_BASE}/get_professores.php`;
const URL_ADD_PROF  = `${API_BASE}/add_professor.php`;
const URL_DEL_PROF  = `${API_BASE}/del_professor.php`;
const URL_GET_MATS  = `${API_BASE}/get_contagem_materias.php`;

document.addEventListener('DOMContentLoaded', () => {
  const csrf = "<?= $csrf ?>";
  async function q(url, opt){ const r = await fetch(url, opt); return r.json(); }

  async function loadGestaoProf(){
    const data = await q('get_professores.php');
    const tb = document.getElementById('tbodyGestaoProf');
    if(!Array.isArray(data) || !data.length){ tb.innerHTML = '<tr><td colspan="4">Nenhum professor.</td></tr>'; return; }
    tb.innerHTML = data.map(p=>`
      <tr>
        <td>${p.nome ?? ''}</td>
        <td>${p.email ?? ''}</td>
        <td>${p.escola ?? ''}</td>
        <td class="text-end">
          <button class="btn btn-outline-danger btn-sm" onclick="delProf(${p.id})">Excluir</button>
        </td>
      </tr>`).join('');
  }
  async function loadMat(){
    const data = await q('get_contagem_materias.php');
    const tb = document.getElementById('tbodyMat');
    if(!Array.isArray(data) || !data.length){ tb.innerHTML = '<tr><td colspan="2">Sem dados.</td></tr>'; return; }
    tb.innerHTML = data.map(m=>`
      <tr>
        <td>${m.materia}</td>
        <td class="text-center fw-semibold">${m.qtd}</td>
      </tr>`).join('');
  }

async function jsonOrText(res) {
  const text = await res.text();
  try { return JSON.parse(text); } catch { return { ok:false, msg:'Resposta não-JSON do servidor', raw:text, status:res.status }; }
}

window.delProf = async function(id){
  if(!confirm('Confirmar exclusão do professor?')) return;

  const fd = new FormData();
  fd.append('id', id);
  fd.append('csrf', "<?= $csrf ?>");

  try {
    const res = await fetch('del_professor.php', { method:'POST', body: fd });
    const j = await jsonOrText(res);

    if (j.ok) {
      loadGestaoProf();
      return;
    }

    if (j.raw) {
      console.error('Resposta não-JSON de del_professor.php:', j.raw);
      alert(`Falha ao excluir (HTTP ${j.status}). Verifique se a sessão está ativa e se você tem permissão.`);
      return;
    }

    alert(j.msg || 'Não foi possível excluir.');
  } catch (err) {
    alert('Falha na requisição: ' + err.message);
  }
};

  const modalAddProf = document.getElementById('modalAddProf');
  const btnAbrir     = document.getElementById('btnAbrirAddProf');
  const btnFechar    = document.getElementById('btnFecharAddProf');

  btnAbrir?.addEventListener('click', (e)=>{ e.preventDefault(); modalAddProf.style.display='flex'; });
  btnFechar?.addEventListener('click', (e)=>{ e.preventDefault(); modalAddProf.style.display='none'; });
  modalAddProf?.addEventListener('click', (e)=>{ if(e.target===modalAddProf) modalAddProf.style.display='none'; });

  const ap_mSel   = document.getElementById('ap_materiasSelecionadas');
  const ap_hidden = document.getElementById('ap_materiasHidden');
  const ap_modal  = document.getElementById('ap_modalMaterias');
  const ap_lista  = document.getElementById('ap_listaMaterias');
  let ap_escolhidas = [];

  function renderChips(){
    ap_mSel.innerHTML='';
    ap_escolhidas.forEach(m=>{
      const chip=document.createElement('div');
      chip.className='chip';
      chip.innerHTML=`${m} <span>&times;</span>`;
      chip.querySelector('span').addEventListener('click',(ev)=>{
        ev.stopPropagation();
        ap_escolhidas = ap_escolhidas.filter(x=>x!==m);
        ap_lista.querySelectorAll('.materia-btn').forEach(btn=>{
          if(btn.textContent.trim()===m) btn.classList.remove('ativo');
        });
        renderChips();
      });
      ap_mSel.appendChild(chip);
    });
    if(ap_escolhidas.length===0) ap_mSel.textContent='Matérias (clique para selecionar)';
    ap_hidden.value = ap_escolhidas.join(', ');
  }

  ap_mSel.addEventListener('click', ()=>{ ap_modal.style.display='flex'; });

  ap_lista.addEventListener('click', (e)=>{
    if(!e.target.classList.contains('materia-btn')) return;
    const materia = e.target.textContent.trim();
    e.target.classList.toggle('ativo');
    if(ap_escolhidas.includes(materia)) ap_escolhidas = ap_escolhidas.filter(m => m !== materia);
    else ap_escolhidas.push(materia);
    renderChips();
  });

  ap_modal.addEventListener('click', (e)=>{ if(e.target===ap_modal) ap_modal.style.display='none'; });

  const ap_form     = document.getElementById('formAddProf');
  const ap_alertErr = document.getElementById('ap_alertErr');
  const ap_alertOk  = document.getElementById('ap_alertOk');

  ap_form.addEventListener('submit', async (e)=>{
    e.preventDefault();
    ap_alertErr.style.display='none'; ap_alertOk.style.display='none';

    const fd = new FormData(ap_form);
    const nome  = (fd.get('nome')||'').trim();
    const email = (fd.get('email')||'').trim();
    const escola= (fd.get('escola')||'').trim();
    const senha = (fd.get('senha')||'');
    const materias = (ap_hidden.value||'').trim();

    let erros=[];
    if(!nome) erros.push('Informe o nome.');
    if(!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) erros.push('E-mail inválido.');
    if(!escola) erros.push('Informe a escola.');
    if(!senha || senha.length<6) erros.push('Senha deve ter ao menos 6 caracteres.');
    if(!materias) erros.push('Selecione ao menos uma matéria.');

    if(erros.length){
      ap_alertErr.textContent = erros.join(' ');
      ap_alertErr.style.display='block';
      return;
    }

    fd.set('cargo','Professor');
    fd.set('materias', materias);

    const r = await fetch('add_professor.php',{method:'POST', body:fd});
    let j; try{ j = await r.json(); }catch{ j={ok:false,msg:'Resposta inválida do servidor.'}; }

    if(j.ok){
      ap_alertOk.textContent='Professor cadastrado com sucesso!';
      ap_alertOk.style.display='block';
      ap_form.reset();
      ap_escolhidas=[]; renderChips();
      setTimeout(()=>{ modalAddProf.style.display='none'; }, 700);
      loadGestaoProf();
    }else{
      ap_alertErr.textContent = j.msg || 'Erro ao salvar.';
      ap_alertErr.style.display='block';
    }
  });

  loadGestaoProf();
  loadMat();
});
</script>

  <script>
    const hamburger = document.getElementById('hamburger');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebar = document.getElementById('sidebar');
    hamburger.addEventListener('click', () => sidebar.classList.add('active'));
    closeSidebar.addEventListener('click', () => sidebar.classList.remove('active'));
    document.addEventListener('click', (e) => {
      if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
        sidebar.classList.remove('active');
      }
    });

    const perfilBtn = document.getElementById('perfilBtn');
    const menuPerfil = document.getElementById('menuPerfil');
    const closePerfil = document.getElementById('closePerfil');
    perfilBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      menuPerfil.classList.toggle('active');
    });
    closePerfil.addEventListener('click', () => {
      menuPerfil.classList.remove('active');
    });
    document.addEventListener('click', (e) => {
      if (!menuPerfil.contains(e.target) && !perfilBtn.contains(e.target)) {
        menuPerfil.classList.remove('active');
      }
    });

    function gerarCalendario() {
      const hoje = new Date();
      const mes = hoje.getMonth();
      const ano = hoje.getFullYear();
      const primeiroDia = new Date(ano, mes, 1).getDay();
      const ultimoDia = new Date(ano, mes + 1, 0).getDate();

      const nomesMeses = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho",
                          "Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];

      let html = `<h3>${nomesMeses[mes]} ${ano}</h3>`;
      html += "<table><thead><tr>";
      const diasSemana = ["Dom","Seg","Ter","Qua","Qui","Sex","Sab"];
      diasSemana.forEach(d => html += `<th>${d}</th>`);
      html += "</tr></thead><tbody><tr>";

      for (let i = 0; i < primeiroDia; i++) html += "<td></td>";
      for (let dia = 1; dia <= ultimoDia; dia++) {
        if ((primeiroDia + dia - 1) % 7 === 0 && dia > 1) html += "</tr><tr>";
        html += `<td>${dia}</td>`;
      }
      html += "</tr></tbody></table>";
      document.getElementById("calendario").innerHTML = html;
    }
    gerarCalendario();
  </script>

  <script>
document.querySelectorAll(".tab-link").forEach(btn=>{
  btn.addEventListener("click",()=>{
    document.querySelectorAll(".tab-link").forEach(b=>b.classList.remove("active"));
    document.querySelectorAll(".tab-content").forEach(c=>c.classList.remove("active"));
    btn.classList.add("active");
    document.getElementById("tab-"+btn.dataset.tab).classList.add("active");
  });
});
</script>

</body>
</html>

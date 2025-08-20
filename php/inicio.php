<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendário Escolar 2025</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    :root{
      --bg:#f0f0f0;
      --sidebar:#a0c1e8;
      --text:#2d3e67;
      --muted:#6b7280;
      --card:#ffffff;
      --shadow:0 10px 30px rgba(0,0,0,.08);
      --radius:16px;
    }

    *{box-sizing:border-box}

    body{
      margin:0;
      font-family: Arial, Helvetica, sans-serif;
      background:var(--bg);
      color:#111827;
    }

    /* Sidebar */
    .sidebar{
      width:200px;
      height:100vh;
      background:var(--sidebar);
      position:fixed;
      left:0; top:0;
      padding:24px 14px;
      box-shadow:2px 0 5px rgba(0,0,0,.08);
      display:flex;
      flex-direction:column;
      gap:12px;
    }
    .sidebar .brand{
      width:100%;
      height:54px;
      border-radius:12px;
      background:rgba(255,255,255,.35); /* placeholder no lugar da imagem */
      display:flex; align-items:center; justify-content:center;
      font-weight:700; color:#113c7a;
      letter-spacing:.5px;
      margin-bottom:10px;
    }
    .sidebar ul{list-style:none; padding:0; margin:0}
    .sidebar li{margin:10px 0}
    .sidebar a{
      text-decoration:none;
      color:#fff;
      font-size:15px;
      padding:10px 12px;
      display:block;
      border-radius:10px;
    }
    .sidebar a:hover{background:rgba(255,255,255,.15)}
    .logout{
      color:#fff;
      margin-top:auto;
      text-decoration:none;
      padding:10px 12px;
      border-radius:10px;
      background:rgba(255,255,255,.12);
    }
    .logout:hover{background:rgba(255,255,255,.2)}

    /* Conteúdo */
    .content{
      margin-left:200px;
      padding:28px;
    }

    /* Hero card */
    .hero{
      background:var(--card);
      border-radius:var(--radius);
      box-shadow:var(--shadow);
      padding:28px;
      display:grid;
      grid-template-columns:1fr 260px;
      gap:20px;
      align-items:center;
      margin:6px auto 28px;
      max-width:980px;
    }
    /* bloco da “ilustração” apenas como placeholder */
    .hero-art{
      width:100%; height:180px;
      border-radius:12px;
      background:linear-gradient(180deg, #e6ecff, #d5e3ff);
    }

    .hero h1{
      margin:0 0 10px;
      color:var(--text);
      font-size:28px;
      line-height:1.2;
      font-weight:800;
    }
    .hero p{
      margin:0;
      color:var(--muted);
      font-size:18px;
      line-height:1.5;
      max-width:48ch;
    }

    /* Botões */
    .actions{
      max-width:980px;
      margin:0 auto;
      display:grid;
      grid-template-columns: repeat(4, minmax(140px, 1fr));
      gap:18px;
    }

    .btn{
      border:none;
      padding:18px 20px;
      font-size:22px;
      font-weight:700;
      border-radius:16px;
      cursor:pointer;
      transition:transform .05s ease, box-shadow .2s ease, filter .2s ease;
      box-shadow:var(--shadow);
      text-align:center;
      color:#fff;
    }
    .btn:active{transform:translateY(1px)}
    .btn:hover{filter:brightness(1.03)}

    .btn-light{ background:linear-gradient(180deg,#a8c7ff,#8fb6ff); color:#0b2a62}
    .btn-mid  { background:linear-gradient(180deg,#7ea4ff,#5f89ff) }
    .btn-dark { background:linear-gradient(180deg,#4a68b8,#2f4fa7) }
    .btn-navy { background:linear-gradient(180deg,#0c2a66,#071c49) }

    /* Responsivo */
    @media (max-width: 960px){
      .hero{grid-template-columns:1fr; padding:22px}
      .hero-art{height:120px; order:-1}
      .actions{grid-template-columns: repeat(2, 1fr)}
    }
    @media (max-width: 560px){
      .sidebar{position:static; width:100%; height:auto; flex-direction:row; flex-wrap:wrap; gap:8px}
      .content{margin-left:0; padding:18px}
      .actions{grid-template-columns:1fr}
    }
  </style>
</head>
<body>

  <aside class="sidebar">
    <div class="brand">AVALIA+</div>
    <ul>
      <li><a href="cadastrar.php">Cadastrar</a></li>
      <li><a href="formularios.php">Formulários</a></li>
      <li><a href="calendario.php">Calendário</a></li>
      <li><a href="comousar.php">Como usar</a></li>
    </ul>
    <a class="logout" href="logout.php">Logout</a>
  </aside>

  <main class="content">
    <!-- Card principal -->
    <section class="hero">
      <div class="hero-text">
        <h1>Avalia+: praticidade e eficiência na avaliação escolar.</h1>
        <p>É uma plataforma que facilita a criação e organização de avaliações escolares.</p>
      </div>
      <!-- “Ilustração” substituída por um bloco neutro (sem imagens) -->
      <div class="hero-art" aria-hidden="true"></div>
    </section>

    <!-- Botões de ação -->
    <nav class="actions">
      <button class="btn btn-light">Provas</button>
      <button class="btn btn-mid">Redações</button>
      <button class="btn btn-dark">Gabaritos</button>
      <button class="btn btn-navy">Cadastro</button>
    </nav>
  </main>

</body>
</html>

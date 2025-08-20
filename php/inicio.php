<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Avalia+</title>
  <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Questrial', sans-serif; background-color: #f2f4f8; color: #333; }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 25px 60px;
      width: 100%;
      z-index: 10;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .hamburger { font-size: 28px; cursor: pointer; color: #1a3a7c; }
    .capelo { width: 60px; }

    .foto-perfil { width: 45px; cursor: pointer; }

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

    .sidebar-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 40px;
    }
    .sidebar-header .close-btn { font-size: 28px; cursor: pointer; }
    .sidebar-header img { height: 28px; object-fit: contain; }

    .sidebar a { color: white; text-decoration: none; font-size: 18px; margin: 5px 0; padding: 10px; border-radius: 8px; transition: background 0.2s; }
    .sidebar a:hover { background-color: rgba(255, 255, 255, 0.2); }

    .sidebar .logout { margin-top: auto; display: flex; align-items: center; gap: 10px; font-size: 18px; cursor: pointer; color: white; padding: 10px; border-radius: 8px; transition: background 0.2s; }
    .sidebar .logout i { font-size: 20px; }
    .sidebar .logout:hover { background-color: rgba(255,255,255,0.2); }

    .content { max-width: 1000px; margin: 40px auto; padding: 0 20px; display: flex; flex-direction: column; align-items: center; transition: margin-left 0.3s ease; }

    .retangulo { background-color: white; border-radius: 16px; padding: 40px; display: flex; align-items: center; justify-content: space-between; gap: 40px; width: 100%; box-shadow: 0px 6px 18px rgba(0,0,0,0.08); }
    .retangulo .text { flex: 1; }
    .retangulo .text h1 { font-size: 35px; color: #1a3a7c; margin-bottom: 15px; line-height: 1.3; }
    .retangulo .text p { font-size: 30px; color: #444; }
    .retangulo .image { width: 280px; flex-shrink: 0; }
    .retangulo .image img { width: 100%; }

    .buttons { display: flex; justify-content: space-between; align-items: center; margin: 40px 0; width: 100%; max-width: 1000px; }

    .btn {
       flex: 0 0 300px;
       height: 125px;
       padding: 22px;
       font-size: 20px;
       color: white;
       font-weight: 500;
       border: none;
       border-radius: 12px;
       cursor: pointer;
       transition: transform 0.25s ease, box-shadow 0.25s ease;
       text-align: center;
       background-size: cover;
       background-position: center;
       background-repeat: no-repeat;
    }

    .btn.provas { background-image: url('../assets/botao1.png'); }
    .btn.redacoes { background-image: url('../assets/botao3.png'); }
    .btn.gabaritos { background-image: url('../assets/botao4.png'); }

    .btn:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 14px rgba(0,0,0,0.12);
      filter: brightness(1.05);
    }
  </style>
</head>
<body>

  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <i class="fas fa-bars close-btn" id="closeSidebar"></i>
      <img src="../assets/logo_azulclaro.png" alt="Avalia+">
    </div>

    <a href="inicio.php">Inicio</a>
    <a href="cadastrar.php">Cadastro</a>
    <a href="formularios.php">Provas</a>
    <a href="#">Gabaritos</a>
    <a href="comousar.php">Como Usar</a>

    <div class="logout" id="logoutBtn">
      <i class="fas fa-door-open"></i> Logout
    </div>
  </div>

  <div class="header">
    <div class="header-left">
      <i class="fas fa-bars hamburger" id="hamburger"></i>
      <img class="capelo" src="../assets/capelo_azul.png" alt="Ícone formatura">
    </div>
    <img class="foto-perfil" src="../assets/perfil_padrao.png" alt="Perfil">
  </div>

  <div class="content" id="mainContent">
    <div class="retangulo">
      <div class="text">
        <h1>Avalia+: praticidade e eficiência na avaliação escolar.</h1>
        <p>É uma plataforma que facilita a criação e organização de avaliações escolares.</p>
      </div>
      <div class="image">
        <img src="../assets/livros.png" alt="Ícones de livros e caderno">
      </div>
    </div>

    <div class="buttons">
      <button class="btn provas">Provas</button>
      <button class="btn redacoes">Redações</button>
      <button class="btn gabaritos">Gabaritos</button>
    </div>
  </div>

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
  </script>

</body>
</html>

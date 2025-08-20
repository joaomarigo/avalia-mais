<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil - Avalia+</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      font-family: 'Questrial', sans-serif;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      width: 100%;
    }

    .container {
      display: flex;
      height: 100vh;
    }

    .left {
      flex: 1.1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 40px;
      background: white;
      position: relative;
      overflow: hidden;
    }

    .profile-pic {
      width: 125px;
      height: 125px;
      background-color: #12337b;
      border-radius: 50%;
      margin-bottom: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
      cursor: pointer;
    }

    .profile-pic i {
      font-size: 60px;
      color: #a9c4eb;
    }

    .input-container {
      width: 100%;
      max-width: 350px;
      margin-bottom: 25px;
    }

    .senha-container {
      position: relative;
    }

    .senha-container i {
      position: absolute;
      right: 10px;
      top: 10px;
      color: #12337b;
      cursor: pointer;
    }

    .alterar-senha {
      font-size: 18px;
      color: #4e4e4e;
      text-decoration: underline;
      cursor: pointer;
      text-align: left;
    }

    .right {
      flex: 0.9;
      background-image: url('../assets/fundo_login.png');
      background-repeat: no-repeat;
      background-position: center;
      background-size: 100% 100%;
      background-attachment: fixed;
      
      display: flex;
      flex-direction: column;
      justify-content: center;

      color: white;
      padding: 40px;
      position: relative;
    }

    .right .capelo {
      position: absolute;
      top: 30px;
      right: 40px;
      width: 110px;
    }

    .right h1 {
      font-size: 80px;
      padding: 0 11%;
    }

    .input_perfil {
      width: 100%;
      padding: 10px 35px 10px 5px;
      border: none;
      border-bottom: 2px solid #12337b;
      background: transparent;
      outline: none;
      font-size: 22px;
      color: #12337b;
      font-family: 'Poppins', sans-serif;
    }

    .input_perfil::placeholder {
      color: #12337b;
      opacity: 0.7;
      font-weight: 300;
    }

    .foto-opcoes {
      position: absolute;
      bottom: -200px;
      left: 0;
      width: 100%;
      background-color: #f8f8f8;
      box-shadow: 0 -4px 10px rgba(0,0,0,0.2);
      transition: bottom 0.3s ease;
      z-index: 10;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
      overflow: hidden;
    }

    .foto-opcoes.show {
      bottom: 0;
    }

    .foto-opcoes .opcao {
      padding: 20px;
      font-size: 20px;
      color: #5b6b7c;
      text-align: center;
      border-bottom: 1px solid #ddd;
      background-color: white;
      cursor: pointer;
    }

    .foto-opcoes .opcao:last-child {
      border-bottom: none;
    }

    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.3);
      display: none;
      z-index: 5;
    }

    .overlay.show {
      display: block;
    }
  </style>
</head>
<body>

  <div class="container">

    <div class="left">
      <div class="overlay" id="overlay"></div>

      <div class="profile-pic" id="iconePerfil">
        <i class="fa-solid fa-user"></i>
      </div>

      <div class="input-container">
        <input class="input_perfil" type="text" name="nome" placeholder="Nome" required>
      </div>

      <div class="input-container">
        <input class="input_perfil" type="text" name="escola" placeholder="Escola" required>
      </div>

      <div class="input-container">
        <input class="input_perfil" type="email" name="email" placeholder="Email" required>
      </div>

      <div class="input-container senha-container">
        <input id="senha" class="input_perfil" type="password" name="senha" placeholder="Senha" required>
        <i id="toggleSenha" class="fa-solid fa-eye toggle-olho"></i>
      </div>

      <div class="input-container">
        <div class="alterar-senha">Alterar senha</div>
      </div>

      <div class="foto-opcoes" id="fotoOpcoes">
        <div class="opcao" onclick="tirarFoto()">Tirar foto</div>
        <div class="opcao" onclick="escolherGaleria()">Escolher da galeria</div>
      </div>
    </div>

    <div class="right">
      <img class="capelo" src="../assets/capelo_branco.png" alt="Capelo">
      <h1>Bem - Vindo(a),<br>Usuario</h1>
    </div>

  </div>

  <script>
    const senhaInput = document.getElementById('senha');
    const toggleSenha = document.getElementById('toggleSenha');

    toggleSenha.addEventListener('click', () => {
      senhaInput.type = senhaInput.type === 'password' ? 'text' : 'password';
      toggleSenha.classList.toggle('fa-eye');
      toggleSenha.classList.toggle('fa-eye-slash');
    });

    const iconePerfil = document.getElementById('iconePerfil');
    const fotoOpcoes = document.getElementById('fotoOpcoes');
    const overlay = document.getElementById('overlay');

    iconePerfil.addEventListener('click', () => {
      fotoOpcoes.classList.add('show');
      overlay.classList.add('show');
    });

    overlay.addEventListener('click', () => {
      fecharMenu();
    });

    function tirarFoto() {
      alert("Abrindo câmera...");
      fecharMenu();
    }

    function escolherGaleria() {
      alert("Abrindo galeria...");
      fecharMenu();
    }

    function fecharMenu() {
      fotoOpcoes.classList.remove('show');
      overlay.classList.remove('show');
    }
  </script>

</body>
</html>

<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Avalia+</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Questrial&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
        height: 100vh;
        margin: 0;
        padding: 0;
    }

    .container {
        display: flex;
        height: 100vh;
    }

    .left {
        flex: 1;
        background-image: url('../assets/fundo_login.png');
        background-repeat: no-repeat;
        background-position: center;
        background-size: 100% 100%;
        background-attachment: fixed;
        color: white;

        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;

        box-shadow: -10px 0px 50px rgba(0, 0, 0, 0.4);
        z-index: 2;
    }

    .left .logo {
        width: 70%;
        margin-bottom: 20px;
    }

    .right {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
        background-color: #ffffffff;
    }

    .right form {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      width: 100%;
      max-width: 300px;
    }

    .titulo-login {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 50px;
    }

    .titulo-login img {
      height: 60px;
      width: auto;
    }

    .titulo-login h2 {
      font-size: 45px;
      font-family: 'Questrial', sans-serif;
      color: #12337b;
      margin: 0;
    }

    .input_login {
      width: 100%;
      padding: 8px 35px 8px 5px;
      border: none;
      border-bottom: 2px solid #12337b;
      background: transparent;
      outline: none;
      font-size: 20px;
      color: #12337b;
      font-family: 'Poppins', sans-serif;
      box-sizing: border-box;
    }

    .input_login::placeholder {
        color: #12337b;
        opacity: 0.7;
        font-weight: 300;
    }

    .senha-container {
        position: relative;
        width: 100%;
        margin-top: 25px;
        margin-bottom: 25px;
    }

    .toggle-olho {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 20px;
        color: #12337b;
    }

    .login-btn {
      margin-top: 20px;
      height: 40px;
      width: 125px;
      text-align: center;
      font-size: 18px;
      background-color: transparent;
      color: #12337b;
      border: 2px solid #12337b;
      border-radius: 25px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-btn:hover {
      background-color: #12337b;
      color: white;
    }

  </style>
</head>
<body>

    <div class="container">

        <div class="left">
            <img src="../assets/logo_branco.png" alt="Logo" class="logo">
        </div>

        <div class="right">
            <form class="login-box" action="processa_login.php" method="post">

              <div class="titulo-login">
                <img src="../assets/capelo_azul.png" alt="Capelo">
                <h2>Login</h2>
              </div>

              <?php if (!empty($_SESSION['erro_login'])): ?>
                <p style="color:red; text-align: center; margin-bottom: 1rem;">
                  <?php echo $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?>
                </p>
              <?php endif; ?>

              <input class="input_login" type="email" name="email" placeholder="Email" required>

              <div class="senha-container">
                <input id="senha" class="input_login" type="password" name="senha" placeholder="Senha" required>
                <i id="toggleSenha" class="fa-solid fa-eye toggle-olho"></i>
              </div>

              <button type="submit" class="login-btn">Entrar</button>
            </form>
        </div>
    </div>

    <script>
      const senhaInput = document.getElementById('senha');
      const toggleSenha = document.getElementById('toggleSenha');

      toggleSenha.addEventListener('click', () => {
        if (senhaInput.type === 'password') {
          senhaInput.type = 'text';
          toggleSenha.classList.remove('fa-eye');
          toggleSenha.classList.add('fa-eye-slash');
        } else {
          senhaInput.type = 'password';
          toggleSenha.classList.remove('fa-eye-slash');
          toggleSenha.classList.add('fa-eye');
        }
      });
    </script>

</body>
</html>

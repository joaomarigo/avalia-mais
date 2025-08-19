<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Avalia+</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
    height: 100vh;
    }

    .container {
        display: flex;
        height: 100vh;
    }


    .left {
        flex: 1;
        background-color:rgb(255, 255, 255);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
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
        padding: 40px;
        background-color: #f2f2f2;
        box-shadow: -10px 0 30px rgba(0, 0, 0, 0.1);
    }

    .right form {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      width: 100%;
      max-width: 300px;
    }

    .right h2 {
      font-family: Arial, sans-serif;
      color: #23405a;
      margin-bottom: 10%;
    }

    .input_login {
      width: 100%;
      padding: 8px 5px;
      margin-bottom: 20px;
      border: none;
      border-bottom: 2px solid #23405a;
      background: transparent;
      outline: none;
      font-size: 16px;
      color: #23405a;
      font-family: 'Poppins', sans-serif;
    }

    
    .input_login::placeholder {
        color: #23405a;
        opacity: 0.7;
        font-weight: 300;
    }

    .login-btn {
      margin-top: 5%;
      width:55%;
      padding: 7px;
      font-size: 18px;
      background-color: #f2f2f2;
      color: #23405a;
      border: 2px solid #23405a;
      border-radius: 25px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-btn:hover {
      background-color: #23405a;
      color: white;
    }

  </style>
</head>
<body>

    <div class="container">

        <div class="left">
            <img src="../assets/logologin.png" alt="Logo" class="logo">
        </div>


        <div class="right">
            <form class="login-box" action="processa_login.php" method="post">
              <h2>Login</h2>

              <?php if (!empty($_SESSION['erro_login'])): ?>
                <p style="color:red; text-align: center; margin-bottom: 1rem;">
                  <?php echo $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?>
                </p>
              <?php endif; ?>

              <input class="input_login" type="email" name="email" placeholder="Email" required>
              <input class="input_login" type="password" name="senha" placeholder="Senha" required>
              <button type="submit" class="login-btn">Entrar</button>
            </form>
        </div>
    </div>

</body>
</html>

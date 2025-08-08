
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário Escolar 2025</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .sidebar {
            width: 200px;
            height: 100vh;
            background-color: #a0c1e8;
            position: fixed;
            padding: 20px 10px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar img {
            width: 130px;
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 15px 0;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
        }
        .content {
            margin-left: 200px;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        iframe {
            border: none;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="../assets/nw.png" alt="Logo Avalia+">
        <ul>
            <li><a href="cadastrar.php">Cadastrar</a></li>
            <li><a href="formularios.php">Formulários</a></li>
            <li><a href="calendario.php">Calendário</a></li>
            <li><a href="comousar.php">Como usar</a></li>
        </ul>
        <a class="logout" href="logout.php" style="color: white; margin-top: 30px; display: inline-block;">
            <img src="../assets/logout.png" alt="Logout" style="width: 20px; vertical-align: middle; margin-right: 5px;">
            Logout
        </a>
    </div>

    <header>
    <img class="logo" src="../assets/nw.png" alt="Logo Avalia+">
    <nav>
      <a href="../php/inicio.php">Voltar</a>
    </nav>
  </header>

  <main class="login-box">
  <h2>Cadastrar Professor</h2>

  <?php if (!empty($mensagemErro)): ?>
    <div class="notificacao"><?php echo $mensagemErro; ?></div>
  <?php endif; ?>

  <form id="formCadastro" method="POST" action="cadastrar.php" enctype="multipart/form-data">
    <input type="text" name="nome" placeholder="Nome Completo" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <input type="text" name="escola" placeholder="Escola" required>
    <input type="email" name="email" placeholder="Email" required>

    <label for="materias">Selecione a matéria:</label>
    <select id="materias" name="materia" required>
      <option value="" disabled selected>Escolha uma matéria</option>
      <option value="Matemática">Biologia</option>
      <option value="Português">Estudos Avançados em Ciências da Natureza e suas Tecnologias</option>
      <option value="Português">Estudos Avançados em Matemática e suas Tecnologias</option>
      <option value="Português">Geografia</option>
      <option value="Português">Internet, Protocolos e Segurança de Sistemas da Informação</option>
      <option value="Português">Língua Espanhola</option>
      <option value="Português">Língua Inglesa</option>
      <option value="Português">Língua Portuguesa</option>
      <option value="Português">Matemática</option>
      <option value="Português">Programação de Aplicativos Mobile II</option>
      <option value="Português">Planejamento e Desenvolvimento do Trabalho de Conclusão de Curso (TCC) em Desenvolvimento de Sistemas</option>
      <option value="Português">Programação Web III</option>
      <option value="Português">Qualidade e Teste de Software</option>
      <option value="Português">Sociologia</option>
      <option value="Português">Sistemas Embarcados</option>
    </select>
    <button type="submit">Salvar</button>
  </form>
  </main>

  <script>
    const capturarFotoBtn = document.getElementById('capturarFoto');
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const preview = document.getElementById('preview');
    const fotoBase64Input = document.getElementById('fotoBase64');

    let stream;

    capturarFotoBtn.addEventListener('click', () => {
      const context = canvas.getContext('2d');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      const dataURL = canvas.toDataURL('image/png');
      preview.src = dataURL;
      preview.style.display = 'block';
      fotoBase64Input.value = dataURL;

      video.style.display = 'none';
      capturarFotoBtn.style.display = 'none';

      stream.getTracks().forEach(track => track.stop());
    });

    const fotoInput = document.getElementById('foto');
    const preview2 = document.getElementById('preview2');

    fotoInput.addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview2.src = e.target.result;
          preview2.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>

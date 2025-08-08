<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AVALIA+</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: sans-serif;
    }

    body {
      scroll-behavior: smooth;
      background: #f9f9f9;
    }

    header {
      background: #fff;
      padding: 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
        width: 200px;
    }

    .entrar-btn {
      border: 1px solid #1f3c88;
      background: transparent;
      padding: 0.5rem 1rem;
      color: #1f3c88;
      cursor: pointer;
      border-radius: 4px;
    }

    section.hero {
      display: flex;
      justify-content: right;
      padding: 2rem;
      gap: 1rem;
    }

    .person {
  width: 140px;
  height: 250px;
  border-radius: 70px;
  overflow: hidden;
  background-color: #dcdcdc;
  display: flex;
  align-items: center;
  justify-content: center;
}

.person img {
  height: 350px;
  width: auto;
  object-fit: contain;
  object-position: center;
}
    section.features {
      padding: 4rem 2rem;
      background: #fff;
      text-align: center;
    }
    section.video-section {
      background: #f9f9f9;
      padding: 4rem 2rem;
      text-align: center;
    }

    video {
      width: 100%;
      max-width: 600px;
      border-radius: 12px;
      margin-bottom: 2rem;
    }

    details {
  border-bottom: 1px solid #c8d4e3;
  padding: 1rem 0;
  font-family: 'Segoe UI', sans-serif;
  font-size: 20px;
  cursor: pointer;
  color: #092c44;
  position: relative;
}

details[open] {
  background-color: #f5f9fc;
}

details summary {
  list-style: none;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: 500;
}

details summary::-webkit-details-marker {
  display: none;
}

details summary::after {
  content: "+"; /* Ícone fechado */
  font-size: 22px;
  color: #23405a;
  transition: transform 0.3s ease;
}

details[open] summary::after {
  content: "×"; /* Ícone aberto */
  transform: rotate(0deg);
}
.phones {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 600px;
    height: 600px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    margin: 5rem 0 10rem 0;
    padding: 2rem;
}

.barra-separadora {
    width: 100%;
    height: 273px;
    background: linear-gradient(to right, #7a93b5, #b3cdea);
}

.boas-vindas {
    position: relative;
    left: 500px;           /* Ajuste horizontal */
    top: 250px;            /* Ajuste vertical */
    font-size: 50px;
    font-family: 'Segoe UI', sans-serif;
    font-weight: 100;
    color: #092c44;
}
body {
  overflow-x: hidden;
}
footer {
  background-color: #00243c;
  color: white;
  text-align: center;
  padding: 10px;
}
/* Estilização que te enviei antes */
    .landing-conteudo {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 60px 10%;
    }

    .landing-conteudo .texto {
      max-width: 100%;
    }

    .landing-conteudo .texto h1 {
      font-size: 50px;
      font-weight: bold;
      color: #23405a;
      line-height: 1.2;
    }

    .landing-conteudo .texto h1 span {
      color: #4058a1;
    }

    .landing-conteudo .texto p {
      font-size: 30px;
      color: #4a4a4a;
      margin-top: 20px;
      line-height: 1.2;
    }
    .recursos {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 60px 10%;
}

.beneficios h2 {
  font-size: 22px;
  font-weight: bold;
  color: #4058a1;
  margin-bottom: 30px;
}

.beneficios h2 span {
  color: #4058a1;
  font-weight: bold;
}

.item {
  display: flex;
  align-items: flex-start;
  margin-bottom: 25px;
  gap: 15px;
}

.item img {
  width: 40px;
  height: 40px;
}

.item h3 {
  font-size: 16px;
  font-weight: bold;
  color: #2e3c70;
  margin-bottom: 5px;
}

.item p {
  font-size: 14px;
  color: #444;
  line-height: 1.5;
}
.container-features {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 60px;
  padding: 60px 10%;
  flex-wrap: wrap;
}
.beneficios {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  max-width: 500px;
}

.beneficios h2 {
  font-size: 22px;
  font-weight: bold;
  color: #4058a1;
  margin-bottom: 30px;
  line-height: 1.4;
}

.beneficios h2 span {
  color: #4058a1;
  font-weight: bold;
}

.item {
  display: flex;
  align-items: flex-start;
  margin-bottom: 25px;
  gap: 15px;
}

.item img {
  width: 120px;
  height: 120px;
}

.item h1 {
  font-size: 30px;
  font-weight: bold;
  color: #2e3c70;
  margin-bottom: 5px;
}

.item p {
  font-size: 20px;
  color: #444;
  line-height: 1.5;
}
.feature-cards {
  display: flex;
  justify-content: center;
  gap: 30px;
  margin-top: 60px;
  padding: 0 2rem;
  flex-wrap: wrap;
}

.feature-card {
  background-color: #e8edf3;
  padding: 3.5rem;
  border-radius: 15px;
  text-align: center;
  width: 500px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
  transition: transform 0.3s ease;
}

.feature-card img {
  width: 150px;
  margin-bottom: 1rem;
}

.feature-card h3 {
  font-size: 30px;
  font-weight: bold;
  color: #23405a;
  margin-bottom: 0.8rem;
}

.feature-card p {
  font-size: 20px;
  color: #4a4a4a;
  line-height: 1.4;
}

.feature-card.destaque {
  background-color: #f2f5f9;
}

.video-section-custom {
  background-color: #3e588d;
  padding: 15rem 2rem;
  display: flex;
  justify-content: center;
  align-items: center;
}

.video-container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 60px;
  flex-wrap: wrap;
  max-width: 1200px;
  width: 100%;
}

.video-mockup {
  width: 480px;
  height: auto;
}

.video-mockup img {
  width: 100%;
  border: 2px dashed #fff;
  border-radius: 8px;
  background-color: #e6e6e6;
}

.video-text {
  max-width: 500px;
  color: white;
}

.video-text h2 {
  font-size: 32px;
  font-weight: bold;
  line-height: 1.4;
}

.video-text h2 span {
  color: #b3cdea;
}

.video-text p {
  font-size: 18px;
  margin-top: 1rem;
  line-height: 1.6;
}
.cta-bloco {
  background-color: #b3cdea;
  text-align: center;
  padding: 80px 20px;
}

.cta-bloco h2 {
  font-size: 28px;
  font-weight: bold;
  color: #23405a;
  max-width: 800px;
  margin: 0 auto;
  line-height: 1.5;
}

.cta-bloco h2 span {
  display: block;
  font-size: 34px;
  margin-top: 10px;
  color: #ffffff;
}

.cta-bloco button {
  margin-top: 30px;
  padding: 12px 30px;
  font-size: 18px;
  background-color: #ffffff;
  color: #1f3c88;
  border: 2px solid #1f3c88;
  border-radius: 25px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.cta-bloco button:hover {
  background-color: #1f3c88;
  color: white;
}

.rodape-logo {
  background-color: #f0f4f8;
  padding: 40px 20px;
  text-align: center;
  color: #23405a;
}

.rodape-logo img {
  width: 400px;
  margin-bottom: 20px;
}

.rodape-logo p {
  font-size: 20px;
  max-width: 600px;
  margin: 0 auto;
  line-height: 1.6;
}
.faq {
  background-color: #ffffff;
  padding: 80px 20px;
  max-width: 900px;
  margin: 0 auto;
}

.faq h2 {
  font-size: 32px;
  color: #1f3c88;
  margin-bottom: 40px;
  text-align: left;
  font-weight: 700;
}

.faq h2 span {
  color: #23405a;
}

.faq details {
  border-bottom: 1px solid #c8d4e3;
  padding: 1.2rem 0;
  font-size: 20px;
  cursor: pointer;
  color: #092c44;
  transition: background 0.3s;
}

.faq details[open] {
  background-color: #f5f9fc;
}

.faq summary {
  list-style: none;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: 600;
  font-size: 20px;
}

.faq summary::after {
  content: "+";
  font-size: 28px;
  color: #23405a;
  margin-left: 10px;
  transition: transform 0.3s ease;
}

.faq details[open] summary::after {
  content: "×";
  font-size: 28px;
  color: #23405a;
}

.faq p {
  font-size: 18px;
  color: #333;
  margin-top: 10px;
  line-height: 1.6;
}

</style>
</head>
<body>
  <header>
    <img class="logo" src="../assets/nw.png" alt="Logo Avalia+">
    <button class="entrar-btn" onclick="window.location.href='login.php'">ENTRAR</button>
  </header>

  <section class="hero">
    
<section class="landing-conteudo">
  <div class="texto">
    <h1>TRANSFORME O JEITO DE <br>CRIAR PROVAS COM O <span>AVALIA+</span></h1>
    <p>
      Professores e coordenadores têm um ambiente centralizado para elaborar,<br>
      organizar e imprimir avaliações para diversas disciplinas, economizando<br>
      tempo e aumentando a eficiência
    </p>
  </div>
</section>

  <div class="col">
    <div class="person"><img src="../assets/pessoa1.png" alt="Pessoa 1"></div>
    <div class="person"><img src="../assets/pessoa4.png" alt="Pessoa 4"></div>
  </div>
  <div class="col">
    <div class="person"><img src="../assets/pessoa2.png" alt="Pessoa 2"></div>
    <div class="person"><img src="../assets/pessoa5.png" alt="Pessoa 5"></div>
  </div>
  <div class="col">
    <div class="person"><img src="../assets/pessoa3.png" alt="Pessoa 3"></div>
        <div class="person"><img src="../assets/pessoa6.png" alt="Pessoa 6"></div>
    </div>
  </section>

<div class="barra-separadora"></div>

<section class="features">
  <div class="container-features">
    <div class="phones">
      <img src="../assets/celularrs.png" alt="App mobile" style="width: 100%;">
    </div>

    <div class="beneficios">
      <div class="item">
        <img src="../assets/icone-questoes.png" alt="Ícone Questões">
        <div>
          <h1>QUESTÕES PERSONALIZADAS</h1>
          <p>Crie avaliações com perguntas de múltipla escolha ou dissertativas de forma rápida e prática.</p>
        </div>
      </div>

      <div class="item">
        <img src="../assets/icone-respostas.png" alt="Ícone Respostas">
        <div>
          <h1>RESPOSTAS CORRETAS</h1>
          <p>Defina a alternativa certa com facilidade e agilidade.</p>
        </div>
      </div>

      <div class="item">
        <img src="../assets/icone-calendario.png" alt="Ícone Organização">
        <div>
          <h1>ORGANIZAÇÃO POR DISCIPLINA E DATA</h1>
          <p>Classifique provas por matéria e data para manter tudo organizado.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="feature-cards">
  <div class="feature-card">
    <img src="../assets/monitor.png" alt="Questões com alternativas">
    <h3>QUESTÕES COM ALTERNATIVAS</h3>
    <p>Crie, edite e gerencie suas avaliações com facilidade.</p>
  </div>
  <div class="feature-card destaque">
    <img src="../assets/impressora.png" alt="Impressão fácil">
    <h3>IMPRESSÃO FÁCIL</h3>
    <p>Layout limpo e organizado para uso imediato.<br>Gere provas e gabaritos prontos para impressão em poucos cliques.</p>
  </div>
  <div class="feature-card">
    <img src="../assets/doc.png" alt="Correção automática">
    <h3>CORREÇÃO AUTOMÁTICA</h3>
    <p>Corrija provas com agilidade e precisão.</p>
  </div>
</div>

</section>
  <section class="video-section-custom">
  <div class="video-container">
    <div class="video-mockup">
      <img src="../assets/video-mockup.png" alt="Vídeo explicativo" />
    </div>
    <div class="video-text">
      <h2>VEJA COMO É FÁCIL<br>USAR O <span>AVALIA+</span></h2>
      <p>Assista ao nosso vídeo explicativo e descubra como otimizar a criação de suas avaliações escolares.</p>
    </div>
  </div>
</section>

<section class="faq">
  <h2>FAQ: <span>Perguntas Frequentes</span></h2>

  <details open>
    <summary>QUEM PODE USAR?</summary>
    <p>O Avalia+ é voltado para professores e coordenadores pedagógicos que buscam praticidade e organização na criação de provas. É necessário criar uma conta gratuita para começar a usar os recursos da plataforma.</p>
  </details>

  <details>
    <summary>QUAIS DISCIPLINAS POSSO ADICIONAR?</summary>
    <p>Você pode adicionar qualquer disciplina de sua grade curricular. O sistema é flexível e permite personalização de acordo com a sua necessidade.</p>
  </details>

  <details>
    <summary>POSSO IMPRIMIR AS PROVAS?</summary>
    <p>Sim! As provas podem ser geradas em PDF prontas para impressão com layout limpo e organizado.</p>
  </details>

  <details>
    <summary>É SEGURO?</summary>
    <p>Sim. A plataforma utiliza criptografia e práticas de segurança da informação para proteger seus dados.</p>
  </details>

  <details>
    <summary>O AVALIA+ É GRATUITO?</summary>
    <p>Sim! O Avalia+ oferece uma versão gratuita com todas as funcionalidades básicas disponíveis.</p>
  </details>
</section>

<section class="cta-bloco">
  <h2>
    CRIE COM PROPÓSITO, ORGANIZE COM ESTRATÉGIA E AVALIE COM VISÃO.
    <span>É ASSIM QUE SE CONSTRÓI O SUCESSO!</span>
  </h2>
  <button onclick="scrollToTop()">Comece já</button>

<script>
  function scrollToTop() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth' // Efeito suave
    });
  }
</script>
</section>

<section class="rodape-logo">
  <img src="../assets/nw.png" alt="Logo Avalia+">
  <p>
    O Avalia+ é uma aplicação web que oferece solução de organização e criação de avaliações escolares. 
    Com interface intuitiva e ferramentas práticas, professores e coordenadores podem elaborar questões, 
    organizar provas e gabaritos, tudo em um único lugar.
  </p>
</section>

  <footer>
    <p></p>
  </footer>
</body>
</html>

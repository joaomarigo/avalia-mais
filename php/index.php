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
      background: #f2f2f2;
      padding: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative; 
      z-index: 2;
      box-shadow: 0 8px 24px rgba(0,0,0,.12);
    }

    .logo {
        width: 300px;
    }

    .entrar-btn {
      border: 3px solid #1f3c88;
      padding: 0.9rem 1rem;
      color: #1f3c88;
      cursor: pointer;
      border-radius: 100px;
      width: 140px;
      margin-right: 40px;
      font-size: 18px;
      font-weight: 700;
      transition: background-color 0.3s ease;
    }

.entrar-btn:hover {
  background-color: #1f3c88;
  color: white;
}

    .hero {
  padding: 0;
  margin: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: auto;
  background-color: #f9f9f9;
}

.hero-banner {
  width: 100%;
  height: auto;
  object-fit: cover;
  display: block;
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
  content: "+"; 
  font-size: 22px;
  color: #23405a;
  transition: transform 0.3s ease;
}

details[open] summary::after {
  content: "×"; 
  transform: rotate(0deg);
}


.barra-separadora {
    width: 100%;
    height: 50px;
    background: linear-gradient(to right, #7a93b5, #b3cdea);
}

.boas-vindas {
    position: relative;
    left: 500px;           
    top: 250px;         
    font-size: 50px;
    font-family: 'Segoe UI', sans-serif;
    font-weight: 100;
    color: #092c44;
}
body {
  overflow-x: hidden;
}

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
  gap: 60px;
  padding: 0px 10%;
  
}
.beneficios{
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  max-width: 500px;
  text-align: left;
  align-items: flex-start;
}

.phones {
    display: flex;
    width: 600px;
    margin: 0rem 3rem;
    padding: 0rem;
}

.beneficios h2{           /* título acima dos três textos */
  font-size: 20px;        /* ajuste se quiser maior */
  font-weight: 800;
  color: #23405a;
  margin-bottom: 18px;
  line-height: 1.3;
}
.beneficios h2 span{
  color: #4058a1;
  font-weight: 800;
}

.item {
  display: flex;
  align-items: flex-start;
  margin-bottom: 15px;
  gap: 0px;
}

.item img {
  width: 110px;
  height: 110px;
}

.item h1 {
  font-size: 20px;
  font-weight: bold;
  color: #2e3c70;
  margin-bottom: 5px;
}

.item p {
  font-size: 18px;
  color: #444;
  line-height: 1.0;
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
  background-color: rgba(223, 227, 230, 0.45);
 
  padding: 3.5rem;
  border-radius: 15px;
  text-align: center;
  width: 500px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
  transition: transform .25s ease, box-shadow .25s ease;
  cursor: pointer;
  will-change: transform;
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
  color: #A3BEDE;
  line-height: 1.4;
}

.feature-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 28px rgba(0,0,0,.12);
}

.extra-text {
 color: #6d6e71;
  max-height: 0;
  overflow: hidden;
  opacity: 0;
  transform: translateY(8px);
  transition: opacity .35s ease, transform .35s ease, max-height .45s ease;
}

/* para quando abrir */
.feature-card.active .extra-text {
  /* manteremos o max-height via JS pra caber qualquer altura */
  opacity: 1;
  transform: translateY(0);
}


/* acessibilidade: reduz animação se necessário */
@media (prefers-reduced-motion: reduce) {
  .feature-card,
  .extra-text {
    transition: none !important;
  }
}

.video-section-custom {
  background-color: #3e588d;
  padding: 5rem 2rem;
  display: flex;
  justify-content: center;
  align-items: center;
}

.video-container {
  display: flex;
  align-items: center;      /* alinha no meio verticalmente */
  justify-content: space-between; /* dá espaço entre vídeo e texto */
  gap: 100px;                /* espaçamento entre vídeo e texto */
}

.video-mockup {
  width: 680px;      
  aspect-ratio: 16 / 9;     /* mantém proporção */
  border: 3px dashed #fff;  /* linha tracejada */
  border-radius: 12px;      /* cantos arredondados (opcional) */
  padding: 6px;             /* espaço entre borda e vídeo */
  box-sizing: border-box;   /* garante que a borda não deforme o tamanho */       /* tamanho do vídeo */
  flex-shrink: 0;           /* impede encolher */
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
  padding: 90px 110px;
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
  margin-top: 70px;
  padding: 12px 30px;
  font-size: 25px;
  background-color: #b3cdea;
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
  padding: 40px 20px;
  color: #23405a;
  max-width: 600px;   
  margin-left: auto;  
  margin-right: 0;
}

.rodape-logo img {
  width: 400px;
  margin-bottom: 20px;
  display: block;
}

.rodape-logo p {
  font-size: 20px;
  line-height: 1.6;
  text-align: left;
}


.faq {
  padding: 130px 20px;
  max-width: 1800px;
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


/* Introdução suave do CTA ao rolar (sem alterar seu HTML) */
.cta-bloco {
  position: relative;
  will-change: transform, opacity, clip-path, filter;
}

/* estado inicial (oculto + levemente abaixo + recortado no topo) */
.cta-bloco.cta-intro {
  opacity: 0;
  transform: translateY(48px);
  filter: blur(2px);
  /* recorte do topo pra parecer que está sob o FAQ */
  clip-path: inset(14% 0 0 0 round 28px);
}

/* estado revelado */
.cta-bloco.cta-in {
  opacity: 1;
  transform: translateY(0);
  filter: blur(0);
  clip-path: inset(0 0 0 0);
  transition:
    opacity .55s ease,
    transform .55s cubic-bezier(.2,.7,.2,1),
    clip-path .65s cubic-bezier(.2,.7,.2,1),
    filter .4s ease;
}

/* sombra no encontro do FAQ com o CTA (profundidade) */
.faq {
  position: relative;
  z-index: 2;
  background: #f9f9f9;
}
.faq::after {
  content:"";
  position:absolute;
  left:0; right:0; bottom:-1px;
  height:42px;
  pointer-events:none;
  background: linear-gradient(to bottom, rgba(0,0,0,.20), rgba(0,0,0,0));
  filter: blur(10px);
  opacity: .9; /* ajuste se quiser mais/menos forte */
}

/* micro-pop no botão quando o CTA fica bem visível (opcional) */
.cta-bloco.cta-in button {
  animation: ctaPop .6s cubic-bezier(.2,.7,.2,1) .1s both;
}
@keyframes ctaPop {
  0%   { transform: translateY(10px) scale(.98); opacity: 0; }
  60%  { transform: translateY(-4px) scale(1.02); opacity: 1; }
  100% { transform: translateY(0) scale(1); }
}

/* Fallback (caso o navegador não suporte clip-path): mantém só fade+slide */
@supports not (clip-path: inset(10%)) {
  .cta-bloco.cta-intro { clip-path: none; }
  .cta-bloco.cta-in    { clip-path: none; }
}
/* Remove a sombra entre FAQ e CTA */
.faq::after,
.cta-bloco::after {
  content: none !important;  /* não gera o pseudo-elemento */
  opacity: 0 !important;
  height: 0 !important;
}

/* 1) Grid para os cards ficarem com a mesma altura */
.feature-cards{
  display: grid;
  grid-template-columns: repeat(3, minmax(280px, 1fr)); /* ajusta se quiser 2 colunas no mobile */
  gap: 30px;
  align-items: stretch; /* estica os cards igualmente */
}

/* 2) Card em coluna e ocupando toda a altura */
.feature-card{
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;   /* se quiser tudo à esquerda: troque para left */
  height: 100%;
}

/* 3) Tamanhos mínimos iguais para alinhar os textos */
:root{
  /* ajuste fino conforme seu conteúdo */
  --altura-titulo: 76px;  /* altura mínima do h3 (1–2 linhas) */
  --altura-desc:   60px;  /* altura mínima do parágrafo */
}

.feature-card img{
  width: 150px;
  margin-bottom: 1rem;
  flex: 0 0 auto;
}

.feature-card h3{
  font-size: 30px;
  line-height: 1.2;
  margin: .6rem 0 .4rem;
  min-height: var(--altura-titulo);
  display: flex;               /* centraliza verticalmente dentro da altura fixa */
  align-items: center;
  justify-content: center;
}

.feature-card p{
  font-size: 20px;
  color: #A3BEDE;
  line-height: 1.4;
  min-height: var(--altura-desc);
  display: flex;
  align-items: center;
  justify-content: center;
}

/* mantém o “extra-text” no fim do card sem quebrar o alinhamento */
.feature-card .extra-text{
  margin-top: auto; /* empurra para a base do card */
}

/* ===== Rodapé AVALIA+ ===== */
.footer {
  background: #f9fbfd;
  color: #23405a;
  font-family: Arial, Helvetica, sans-serif;
}

.footer-wrap {
  width: 100%;
  margin: 0;
  padding: 0 100px; /* adiciona espaçamento lateral */
  display: grid;
  grid-template-columns: 1.1fr 1fr;
  gap: 48px;
}

.footer h3 {
  font-size: 18px;
  letter-spacing: 0.6px;
  color: #23405a;
  margin-bottom: 22px;
  font-weight: 800;
}

.footer-list {
  display: grid;
  gap: 22px;
}

.footer-item {
  display: flex;
  align-items: center;
  gap: 14px;
  color: #23405a;
  font-size: 18px;
  line-height: 1.4;
}

.footer-item svg {
  width: 22px;
  height: 22px;
  flex: 0 0 22px;
}

.footer-brand {
  max-width: 560px;
  margin-left: auto;  /* encosta à direita */
}

.footer-brand img {
  width: 210px;   /* ajuste se quiser maior/menor */
  height: auto;
  display: block;
  margin-bottom: 16px;
}

.footer-brand p {
  font-size: 16px;
  line-height: 1.8;
  color: #23405a;
}

/* faixa inferior */
.footer-bottom {
  width: 100%;
  background: #23405a;  /* barra azul-escuro */
  color: #ffffff;
  margin-top: 50px;
}

.footer-bottom .inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 10px 5px;
  font-size: 18px;
  letter-spacing: .2px;
}

/* responsivo */
@media (max-width: 980px) {
  .footer-wrap {
    grid-template-columns: 1fr;
    gap: 32px;
    padding: 40px 20px 22px;
  }
  .footer-brand {
    margin-left: 0;
  }
}
.cta-bloco {
  margin-bottom: 60px; /* aumenta o espaçamento */
}

.video-section-custom {
  position: relative; /* para permitir posicionar elementos dentro dela */
}
</style>
</head>
<body>
  <header>
    <img class="logo" src="../assets/logologin.png" alt="Logo Avalia+">
    <button class="entrar-btn" onclick="window.location.href='login.php'">ENTRAR</button>
  </header>

  <section class="hero">
    <img src="../assets/bkk.jpg" alt="Banner Avalia+" class="hero-banner">
  </section>

<div class="barra-separadora"></div>

<section class="features">
  <div class="container-features">
    <div class="phones">
      <img src="../assets/imglading.png" alt="App mobile" style="width: 100%;">
    </div>

    <div class="beneficios">
       <h2>SUA <span>FERRAMENTA</span> PARA A CRIAÇÃO DE AVALIAÇÕES ESCOLARES</h2>
      <div class="item">
        <img src="../assets/icone-questoes.png" alt="Ícone Questões">
        <div>
          <h1>QUESTÕES PERSONALIZADAS</h1>
          <p>Crie avaliações com perguntas de múltipla escolha de forma rápida e prática.</p>
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
    <h3>REDAÇÃO NAS AVALIAÇÕES</h3>
    <p>Desenvolva, corrija e organize redações de forma prática e eficiente.</p>
    <div class="extra-text">No Avalia+ é possível gerenciar correções de redações de maneira centralizada, garantindo praticidade e qualidade na avaliação.</div>
  </div>

  <div class="feature-card destaque">
    <img src="../assets/impressora.png" alt="Impressão fácil">
    <h3>IMPRESSÃO FÁCIL</h3>
    <p>Layout limpo e organizado para uso imediato.</p>
    <div class="extra-text">Gere provas e gabaritos prontos para impressão em poucos cliques.</div>
  </div>

  <div class="feature-card">
    <img src="../assets/doc.png" alt="Correção automática">
    <h3>CORREÇÃO AUTOMÁTICA</h3>
    <p>Corrija provas com agilidade e precisão.</p>
    <div class="extra-text">O sistema identifica respostas corretas e erradas instantaneamente.</div>
  </div>
</div>


</section>
  <section class="video-section-custom">
  <div class="video-container">
    <div class="video-mockup">
      
      <iframe width="665" height="450" src="https://www.youtube.com/embed/3zCufR_7Kqs?si=5h0oK9dDmXz4KPjY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
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
    <p>O Avalia+ é voltado exclusivamente para professores e coordenadores que
estejam devidamente cadastrados na plataforma. Para acessar, é necessário utilizar um
login e uma senha individual, garantindo assim a segurança e o controle de acesso. Isso
permite que apenas profissionais autorizados utilizem as funcionalidades da ferramenta
em suas atividades educacionais.</p>
  </details>

  <details>
    <summary>QUAIS DISCIPLINAS POSSO ADICIONAR?</summary>
    <p>Não há limitações quanto às disciplinas que podem ser incluídas no Avalia+. Você tem total liberdade para criar questões e montar avaliações personalizadas para qualquer área do conhecimento, seja matemática, língua portuguesa, ciências, história, geografia, artes, educação física ou qualquer outra disciplina que faça parte do currículo escolar. A plataforma foi pensada para se adaptar às necessidades de cada escola e de cada docente.</p>
  </details>

  <details>
    <summary>POSSO IMPRIMIR AS PROVAS?</summary>
    <p>Sim, a plataforma Avalia+ permite que todas as provas sejam exportadas em formato pronto para impressão. Isso facilita muito o trabalho dos professores e coordenadores, que podem aplicar avaliações presenciais de forma prática, com documentos organizados e padronizados.</p>
  </details>

  <details>
    <summary>É SEGURO?</summary>
    <p>Sim, o Avalia+ foi desenvolvido com foco na segurança dos dados e no controle de acesso. Apenas professores e coordenadores previamente cadastrados e autorizados podem utilizar a plataforma. Além disso, existem diferentes níveis de permissão, o que significa que professores, coordenadores têm acessos distintos, conforme suas responsabilidades. Isso garante que as informações estejam protegidas e acessíveis apenas por quem realmente precisa delas.</p>
  </details>
</section>

<section class="cta-bloco">
  <h2>
    UM SISTEMA QUE TRANSFORMA AVALIAÇÕES EM PRATICIDADE:
    <span>MAIS TEMPO, ORGANIZAÇÃO E CLAREZA PARA O DOCENTE FOCAR NO QUE REALMENTE IMPORTA, O APRENDIZADO DOS ALUNOS</span>
  </h2>
  <button onclick="window.location.href='login.php'">Começe Já</button>

</section>
  <footer class="footer">
  <div class="footer-wrap">
    <!-- Coluna esquerda: Contato -->
    <div>
      <h3>ENTRE EM CONTATO</h3>

      <div class="footer-list">
        <div class="footer-item">
          <!-- Ícone telefone (SVG inline) -->
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M22 16.92v2a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07A19.5 19.5 0 0 1 3.15 9.81 19.8 19.8 0 0 1 .08 1.18 2 2 0 0 1 2.06 0h2a2 2 0 0 1 2 1.72c.13.98.36 1.94.68 2.86a2 2 0 0 1-.45 2.11L5.1 8.86a16 16 0 0 0 6.04 6.04l2.17-1.19a2 2 0 0 1 2.11.45c.92.32 1.88.55 2.86.68A2 2 0 0 1 22 16.92Z" fill="#23405a"/>
          </svg>
          <span>+55 (11) 00000-0000</span>
        </div>

        <div class="footer-item">
          <!-- Ícone e-mail (SVG inline) -->
          <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M20 4H4a2 2 0 0 0-2 2v.4l10 6.4 10-6.4V6a2 2 0 0 0-2-2Zm0 4.8-9.12 5.83a1 1 0 0 1-1.08 0L4 8.8V18a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.8Z" fill="#23405a"/>
          </svg>
          <span>AvaliaMais@gmail.com</span>
        </div>
      </div>
    </div>

    <!-- Coluna direita: Logo + texto -->
    <div class="footer-brand">
      <img src="../assets/logologin.png" alt="AVALIA+">
      <p>
        O Avalia+ é uma aplicação web que oferece uma solução completa para a criação de
        avaliações escolares. Com interface intuitiva e ferramentas práticas, professores e
        coordenadores podem elaborar questões, organizar provas e gerar relatórios prontos
        para impressão, tudo em um único lugar.
      </p>
    </div>
  </div>

  <!-- Faixa inferior (copyright) -->
  <div class="footer-bottom">
    <div class="inner">
      Copyright AVALIA+ - 0000000000 - 2025. Todos os direitos reservados
    </div>
  </div>
</footer>


<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.feature-card').forEach(card => {
    const extra = card.querySelector('.extra-text');
    if (!extra) return;

    extra.style.maxHeight = '0px';
    extra.style.overflow = 'hidden';

    card.addEventListener('click', () => {
      const isOpen = card.classList.toggle('active');
      if (isOpen) {
        extra.style.maxHeight = extra.scrollHeight + 'px';
      } else {
        extra.style.maxHeight = '0px';
      }
    });
  });
});

(function() {
  const cta = document.querySelector('.cta-bloco');
  if (!cta) return;

  // Aplica estado inicial sem precisar mexer no HTML
  cta.classList.add('cta-intro');

  // Revela quando ~30% do CTA entra na tela
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        cta.classList.add('cta-in');
        io.unobserve(cta);
      }
    });
  }, { threshold: 0.3, rootMargin: '0px 0px -10% 0px' });

  io.observe(cta);
})();
</script>
</body>
</html>
<?php
include_once __DIR__ . '/config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID do formulário não informado.");
}

// Buscar título
$stmt = $pdo->prepare("SELECT titulo FROM formularios WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$formulario = $stmt->fetch(PDO::FETCH_ASSOC);
$titulo = $formulario['titulo'] ?? 'Formulário sem título';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titulo) ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .topbar {
            background-color: #5f7a99;
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .form-box {
            background: white;
            margin: 30px auto;
            padding: 20px 30px;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 0 6px rgba(0,0,0,0.1);
        }
        input[type="text"], textarea {
            border: none;
            border-bottom: 1px solid #aaa;
            font-size: 16px;
            margin: 8px 0;
            width: 100%;
            outline: none;
        }
        .question-box {
            margin-top: 30px;
            border-left: 5px solid #5f7a99;
            padding-left: 15px;
            padding-bottom: 15px;
        }
        .question-box h3 {
            margin: 0 0 10px;
            color: #1c3b5a;
        }
        .option {
            margin: 8px 0;
            display: flex;
            align-items: center;
        }
        .option input[type="radio"] {
            margin-right: 10px;
        }
        .option input[type="text"] {
            width: 100%;
            border: none;
            border-bottom: 1px solid #ccc;
        }
        button {
            padding: 10px 20px;
            background-color: #5f7a99;
            border: none;
            color: white;
            border-radius: 4px;
            margin-top: 15px;
            cursor: pointer;
        }
        button:hover {
            background-color: #415a76;
        }
    </style>
</head>
<body>

    <div class="topbar">
        <h2><?= htmlspecialchars($titulo) ?></h2>
        <button onclick="document.querySelector('form').submit();">Salvar</button>
    </div>

    <div class="form-box">
        <form method="POST" action="salvar_perguntas.php?id=<?= $id ?>">
            <div id="questoes-container">
                <div class="question-box">
                    <h3><input type="text" name="questao[]" placeholder="Digite sua questão" required></h3>
                    <div class="opcoes-container">
                        <div class="option">
                            <input type="radio" name="correta_0" value="0">
                            <input type="text" name="opcoes_0[]" placeholder="Opção 1" required>
                        </div>
                        <div class="option">
                            <input type="radio" name="correta_0" value="1">
                            <input type="text" name="opcoes_0[]" placeholder="Opção 2">
                        </div>
                    </div>
                    <button type="button" onclick="adicionarOpcao(this)">+ Adicionar opção</button>
                </div>
            </div>

            <button type="button" onclick="adicionarQuestao()">+ Adicionar Questão Objetiva</button>
            <button type="button" onclick="adicionarDissertativa()">+ Adicionar Questão Dissertativa</button>
        </form>
    </div>

<script>
let questaoIndex = 1;

function adicionarOpcao(botao) {
    const container = botao.previousElementSibling;
    const opcoes = container.querySelectorAll('.option').length;

    const div = document.createElement('div');
    div.classList.add('option');

    const radio = document.createElement('input');
    radio.type = 'radio';
    radio.name = botao.closest('.question-box').querySelector('input[type="radio"]').name;
    radio.value = opcoes;

    const input = document.createElement('input');
    input.type = 'text';
    input.name = botao.closest('.question-box').querySelectorAll('input[type="text"]')[0].name.replace('questao', 'opcoes_' + (questaoIndex - 1)) + '[]';
    input.placeholder = `Opção ${opcoes + 1}`;

    div.appendChild(radio);
    div.appendChild(input);

    container.appendChild(div);
}

function adicionarQuestao() {
    const container = document.getElementById('questoes-container');

    const box = document.createElement('div');
    box.classList.add('question-box');

    box.innerHTML = `
        <h3><input type="text" name="questao[]" placeholder="Digite sua questão" required></h3>
        <div class="opcoes-container">
            <div class="option">
                <input type="radio" name="correta_${questaoIndex}" value="0">
                <input type="text" name="opcoes_${questaoIndex}[]" placeholder="Opção 1" required>
            </div>
            <div class="option">
                <input type="radio" name="correta_${questaoIndex}" value="1">
                <input type="text" name="opcoes_${questaoIndex}[]" placeholder="Opção 2">
            </div>
        </div>
        <button type="button" onclick="adicionarOpcao(this)">+ Adicionar opção</button>
    `;

    container.appendChild(box);
    questaoIndex++;
}

function adicionarDissertativa() {
    const container = document.getElementById('questoes-container');

    const box = document.createElement('div');
    box.classList.add('question-box');

    box.innerHTML = `
        <h3><input type="text" name="questao_dissertativa[]" placeholder="Digite sua questão dissertativa" required></h3>
        <textarea name="resposta_dissertativa[]" rows="4" style="width: 100%; border: 1px solid #ccc; border-radius: 4px; padding: 8px;" placeholder="Campo de resposta para o aluno..."></textarea>
    `;

    container.appendChild(box);
}
</script>

<div id="toast" style="
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    z-index: 1000;
">
    Formulário salvo com sucesso!
</div>

<script>
    const toast = document.getElementById('toast');
    const form = document.querySelector('form');

    document.querySelector('.topbar button').addEventListener('click', function (e) {
        e.preventDefault();

        toast.style.opacity = 1;

        setTimeout(() => {
            toast.style.opacity = 0;
            form.submit();
        }, 1500);
    });

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('sucesso') === '1') {
        toast.style.opacity = 1;
        setTimeout(() => {
            toast.style.opacity = 0;
            window.location.href = "formularios.php";
        }, 2000);
    }
</script>

<div id="toast" style="
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    z-index: 1000;
">
    Formulário salvo com sucesso!
</div>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('sucesso') === '1') {
        const toast = document.getElementById('toast');
        toast.style.opacity = 1;
        setTimeout(() => {
            toast.style.opacity = 0;
            // Remove o parâmetro da URL para não exibir novamente ao recarregar
            window.history.replaceState({}, document.title, "formularios.php");
        }, 3000);
    }
</script>


</body>
</html>

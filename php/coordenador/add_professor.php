<?php
// php/coordenador/add_professor.php
session_start();
require_once __DIR__ . '/../config.php';

// Definir cabeçalho JSON
header('Content-Type: application/json; charset=utf-8');

try {
    // Verificar se é coordenador
    if (strtolower($_SESSION['cargo'] ?? '') !== 'coordenador') {
        http_response_code(403);
        echo json_encode(['ok' => false, 'msg' => 'Acesso restrito a coordenadores.']);
        exit;
    }

    // Verificar se é POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['ok' => false, 'msg' => 'Método não permitido.']);
        exit;
    }

    // Verificar CSRF token
    $csrf_enviado = $_POST['csrf'] ?? '';
    $csrf_sessao = $_SESSION['csrf'] ?? '';
    
    if (empty($csrf_enviado) || empty($csrf_sessao) || !hash_equals($csrf_sessao, $csrf_enviado)) {
        http_response_code(403);
        echo json_encode(['ok' => false, 'msg' => 'Token CSRF inválido.']);
        exit;
    }

    // Obter e validar dados do formulário
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $escola = trim($_POST['escola'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $cargo = trim($_POST['cargo'] ?? 'professor');
    $materias = trim($_POST['materias'] ?? '');

    // Validações
    $erros = [];

    if (empty($nome)) {
        $erros[] = 'Nome é obrigatório.';
    }

    if (empty($email)) {
        $erros[] = 'E-mail é obrigatório.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'E-mail inválido.';
    }

    if (empty($escola)) {
        $erros[] = 'Escola é obrigatória.';
    }

    if (empty($senha)) {
        $erros[] = 'Senha é obrigatória.';
    } elseif (strlen($senha) < 6) {
        $erros[] = 'Senha deve ter no mínimo 6 caracteres.';
    }

    if (empty($materias)) {
        $erros[] = 'Pelo menos uma matéria deve ser selecionada.';
    }

    if (!empty($erros)) {
        echo json_encode(['ok' => false, 'msg' => implode(' ', $erros)]);
        exit;
    }

    // Verificar se o email já existe
    $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email');
    $stmt->execute([':email' => $email]);
    
    if ($stmt->fetch()) {
        echo json_encode(['ok' => false, 'msg' => 'Este e-mail já está cadastrado.']);
        exit;
    }

    // Criptografar senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir novo professor
    $stmt = $pdo->prepare('
        INSERT INTO usuarios (nome, email, escola, senha_hash, cargo, materias, criado_em)
        VALUES (:nome, :email, :escola, :senha_hash, :cargo, :materias, NOW())
    ');

    $resultado = $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':escola' => $escola,
        ':senha_hash' => $senha_hash,
        ':cargo' => strtolower($cargo),
        ':materias' => $materias
    ]);

    if ($resultado) {
        $professor_id = $pdo->lastInsertId();
        
        echo json_encode([
            'ok' => true,
            'msg' => 'Professor cadastrado com sucesso!',
            'professor_id' => $professor_id
        ]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Erro ao cadastrar professor.']);
    }

} catch (PDOException $e) {
    error_log("Erro ao cadastrar professor: " . $e->getMessage());
    
    // Verificar se é erro de email duplicado
    if ($e->getCode() == 23000) {
        echo json_encode(['ok' => false, 'msg' => 'Este e-mail já está cadastrado.']);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Erro interno do servidor ao cadastrar professor.']);
    }
    
} catch (Exception $e) {
    error_log("Erro geral ao cadastrar professor: " . $e->getMessage());
    echo json_encode(['ok' => false, 'msg' => 'Erro interno do servidor.']);
}
?>
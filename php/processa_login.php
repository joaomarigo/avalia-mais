<?php
session_start();
require_once __DIR__ . '/config.php';

try {
    // Confere recepção do POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['erro_login'] = 'Método inválido.';
        header('Location: login.php'); exit;
    }

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $_SESSION['erro_login'] = 'Informe e-mail e senha.';
        header('Location: login.php'); exit;
    }

    // Busca usuário
    $sql = "SELECT id, nome, email, escola, senha_hash, cargo, materias
            FROM usuarios WHERE email = :email LIMIT 1";
    $st = $pdo->prepare($sql);
    $st->execute([':email' => $email]);
    $u = $st->fetch(PDO::FETCH_ASSOC);

    if (!$u) {
        $_SESSION['erro_login'] = 'E-mail não encontrado.';
        header('Location: login.php'); exit;
    }

    // Verifica hash
    if (!(strlen($u['senha_hash']) >= 20 && password_verify($senha, $u['senha_hash']))) {
        $_SESSION['erro_login'] = 'Senha incorreta.';
        header('Location: login.php'); exit;
    }

    // OK, loga
    session_regenerate_id(true);
    $_SESSION['usuario_id'] = $u['id'];
    $_SESSION['cargo']      = $u['cargo'];
    $_SESSION['nome']       = $u['nome'];
    $_SESSION['email']      = $u['email'];

    header('Location: ' . ($u['cargo'] === 'coordenador' ? 'painelusuarios.php' : 'painelusuarios.php'));
    exit;

} catch (Throwable $e) {
    // error_log($e->getMessage());
    $_SESSION['erro_login'] = 'Erro ao processar login.';
    header('Location: login.php'); exit;
}

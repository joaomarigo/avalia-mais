<?php
// php/reset_senha.php  (TEMPORÁRIO — APAGUE DEPOIS)
session_start();
require __DIR__ . '/config.php'; // deve definir $pdo

// === CONFIGURE AQUI ===
$email  = 'coordenador@teste.com';  // o e-mail que você está tentando usar
$senha  = 'SenhaForte123';          // a senha que você quer usar

try {
    // Gera o hash correto no servidor
    $hash = password_hash($senha, PASSWORD_DEFAULT);

    // Atualiza no banco
    $sql = "UPDATE usuarios SET senha_hash = :h WHERE email = :e";
    $st  = $pdo->prepare($sql);
    $st->execute([':h' => $hash, ':e' => $email]);

    // Confere
    $u = $pdo->prepare("SELECT email, LENGTH(senha_hash) AS len FROM usuarios WHERE email = :e LIMIT 1");
    $u->execute([':e' => $email]);
    $row = $u->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: text/plain; charset=utf-8');
    echo "Atualizado!\n";
    echo "email: {$row['email']}\n";
    echo "len(senha_hash): {$row['len']}\n"; // deve ser ~60
    echo "Tente logar agora com a senha: {$senha}\n";
} catch (Throwable $e) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Erro: " . $e->getMessage();
}

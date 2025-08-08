<?php
require_once "config.php";

$email = "admin@avalia.com";
$senha = password_hash("1234", PASSWORD_DEFAULT);
$cargo = "coordenador"; 

$sql = "SELECT COUNT(*) FROM usuarios WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$existe = $stmt->fetchColumn();

if ($existe) {
    echo "O usuário já existe no sistema.";
} else {
    $sql = "INSERT INTO usuarios (email, senha, cargo) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $senha, $cargo]);
    echo "Usuário criado com sucesso.";
}
?>

<?php
// Dados de conexão (ajuste conforme seu banco)
$host = '127.0.0.1';
$dbname = 'avaliamais';
$user = 'root';
$pass = ''; // senha do usuário root (verifique no HeidiSQL)

try {
    // Inicia a conexão com PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit;
}
?>
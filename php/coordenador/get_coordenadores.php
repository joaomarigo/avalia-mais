<?php
session_start();
require_once __DIR__ . '/../config.php';
if (strtolower($_SESSION['cargo'] ?? '') !== 'coordenador') { http_response_code(403); exit; }

$stmt = $pdo->query("SELECT id, nome, email FROM usuarios WHERE LOWER(cargo)='coordenador' ORDER BY nome");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

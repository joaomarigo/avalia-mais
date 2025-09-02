<?php
// php/coordenador/get_professores.php
session_start();
require_once __DIR__ . '/../config.php';

// Definir cabeçalho JSON
header('Content-Type: application/json; charset=utf-8');

try {
    // Verificar se é coordenador
    if (strtolower($_SESSION['cargo'] ?? '') !== 'coordenador') {
        http_response_code(403);
        echo json_encode(['error' => 'Acesso restrito a coordenadores.']);
        exit;
    }

    // Buscar todos os professores
    $stmt = $pdo->prepare('
        SELECT 
            id,
            nome,
            email,
            escola,
            materias,
            criado_em
        FROM usuarios 
        WHERE cargo = :cargo 
        ORDER BY nome ASC
    ');
    
    $stmt->execute([':cargo' => 'professor']);
    $professores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formatar os dados para a resposta
    $professores_formatados = array_map(function($prof) {
        return [
            'id' => (int)$prof['id'],
            'nome' => $prof['nome'] ?? '',
            'email' => $prof['email'] ?? '',
            'escola' => $prof['escola'] ?? '',
            'materias' => $prof['materias'] ?? '',
            'criado_em' => $prof['criado_em'] ?? ''
        ];
    }, $professores);

    echo json_encode($professores_formatados);

} catch (PDOException $e) {
    error_log("Erro ao buscar professores: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor ao buscar professores.']);
    
} catch (Exception $e) {
    error_log("Erro geral ao buscar professores: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor.']);
}
?>
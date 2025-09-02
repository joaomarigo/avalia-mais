<?php
// php/coordenador/get_contagem_materias.php
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

    // Buscar contagem de matérias dos formulários
    $stmt = $pdo->prepare('
        SELECT 
            materia,
            COUNT(*) as qtd
        FROM formularios 
        WHERE materia IS NOT NULL AND materia != ""
        GROUP BY materia 
        ORDER BY qtd DESC, materia ASC
    ');
    
    $stmt->execute();
    $materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Se não houver formulários, mostrar matérias básicas com contagem 0
    if (empty($materias)) {
        $materias_basicas = [
            'Biologia', 'EACNT', 'EAMST', 'Geografia', 'IPSS',
            'Língua Espanhola', 'Língua Inglesa', 'Língua Portuguesa',
            'Matemática', 'PAM', 'TCC', 'Programação Web III',
            'QTS', 'Sociologia', 'Sistemas Embarcados'
        ];
        
        $materias = [];
        foreach ($materias_basicas as $materia) {
            $materias[] = [
                'materia' => $materia,
                'qtd' => 0
            ];
        }
    }

    // Formatar os dados para a resposta
    $materias_formatadas = array_map(function($mat) {
        return [
            'materia' => $mat['materia'] ?? '',
            'qtd' => (int)($mat['qtd'] ?? 0)
        ];
    }, $materias);

    echo json_encode($materias_formatadas);

} catch (PDOException $e) {
    error_log("Erro ao buscar contagem de matérias: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor ao buscar matérias.']);
    
} catch (Exception $e) {
    error_log("Erro geral ao buscar contagem de matérias: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor.']);
}
?>
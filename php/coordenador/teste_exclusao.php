<?php
// teste_exclusao.php - Teste específico para exclusão
session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    // Verificar config.php
    $config_path = __DIR__ . '/../config.php';
    if (!file_exists($config_path)) {
        throw new Exception("Config.php não encontrado em: " . $config_path);
    }
    
    require_once $config_path;
    
    if (!isset($pdo)) {
        throw new Exception("Variável \$pdo não definida");
    }
    
    // Testar conexão com banco
    $pdo->query("SELECT 1");
    
    // Listar professores disponíveis para teste
    $stmt = $pdo->prepare('SELECT id, nome, email FROM usuarios WHERE cargo = :cargo LIMIT 5');
    $stmt->execute([':cargo' => 'professor']);
    $professores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Verificar estrutura da tabela usuarios
    $stmt = $pdo->query("DESCRIBE usuarios");
    $estrutura_usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Verificar se existe tabela formularios
    $stmt = $pdo->query("SHOW TABLES LIKE 'formularios'");
    $tabela_formularios_existe = $stmt->rowCount() > 0;
    
    echo json_encode([
        'ok' => true,
        'msg' => 'Teste de exclusão - ambiente verificado',
        'config_path' => $config_path,
        'config_exists' => file_exists($config_path),
        'pdo_exists' => isset($pdo),
        'professores_cadastrados' => count($professores),
        'professores' => $professores,
        'estrutura_tabela_usuarios' => $estrutura_usuarios,
        'tabela_formularios_existe' => $tabela_formularios_existe,
        'session' => [
            'cargo' => $_SESSION['cargo'] ?? null,
            'csrf' => $_SESSION['csrf'] ?? null
        ]
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo json_encode([
        'ok' => false,
        'msg' => 'Erro no teste: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ], JSON_PRETTY_PRINT);
}
?>
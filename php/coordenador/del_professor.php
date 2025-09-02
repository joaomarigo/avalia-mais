<?php
// php/coordenador/del_professor.php
session_start();
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // Verificar se é coordenador
    if (strtolower($_SESSION['cargo'] ?? '') !== 'coordenador') {
        echo json_encode(['ok' => false, 'msg' => 'Acesso restrito a coordenadores.']);
        exit;
    }

    // Verificar método POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['ok' => false, 'msg' => 'Método não permitido.']);
        exit;
    }

    // Verificar CSRF
    $csrf_enviado = $_POST['csrf'] ?? '';
    $csrf_sessao = $_SESSION['csrf'] ?? '';
    
    if (empty($csrf_enviado) || empty($csrf_sessao) || !hash_equals($csrf_sessao, $csrf_enviado)) {
        echo json_encode(['ok' => false, 'msg' => 'Token CSRF inválido.']);
        exit;
    }

    // Verificar ID
    $id = $_POST['id'] ?? null;
    if (!$id || !is_numeric($id)) {
        echo json_encode(['ok' => false, 'msg' => 'ID inválido.']);
        exit;
    }

    $id = (int)$id;

    // Verificar se o professor existe
    $stmt = $pdo->prepare('SELECT id, nome, email FROM usuarios WHERE id = ? AND cargo = ?');
    $stmt->execute([$id, 'professor']);
    $professor = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$professor) {
        echo json_encode(['ok' => false, 'msg' => 'Professor não encontrado.']);
        exit;
    }

    // Excluir apenas da tabela usuarios (sem mexer em formularios por enquanto)
    $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id = ? AND cargo = ?');
    $stmt->execute([$id, 'professor']);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'ok' => true,
            'msg' => 'Professor excluído com sucesso!',
            'professor' => [
                'id' => $professor['id'],
                'nome' => $professor['nome'],
                'email' => $professor['email']
            ]
        ]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Nenhum registro foi excluído.']);
    }

} catch (PDOException $e) {
    echo json_encode([
        'ok' => false,
        'msg' => 'Erro de banco de dados: ' . $e->getMessage(),
        'code' => $e->getCode()
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'ok' => false,
        'msg' => 'Erro: ' . $e->getMessage()
    ]);
}
?>
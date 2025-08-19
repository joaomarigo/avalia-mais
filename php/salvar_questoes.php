<?php
/**
 * salvar_questoes.php
 * Recebe JSON com o formulário e suas questões/alternativas e grava no banco.
 *
 * Espera JSON:
 * {
 *   "form_id": (int|null),
 *   "titulo": "string",
 *   "turma": "string|null",
 *   "data": "string|null",
 *   "sala": "string|null",
 *   "aluno": "string|null",
 *   "questoes": [
 *     {
 *       "ordem": 1,
 *       "titulo": "string",
 *       "enunciado": "string",
 *       "alternativas": [
 *         {"ordem":1,"texto":"...","correta":true|false},
 *         ...
 *       ]
 *     },
 *     ...
 *   ]
 * }
 *
 * ASSUMINDO tabelas (ajuste conforme seu schema real):
 * - formularios(id INT AI PK, titulo VARCHAR(255), criado_em DATETIME DEFAULT CURRENT_TIMESTAMP)
 * - perguntas(id INT AI PK, formulario_id INT, titulo VARCHAR(255), enunciado TEXT, ordem INT)
 * - alternativas(id INT AI PK, pergunta_id INT, texto TEXT, correta TINYINT(1), ordem INT)
 */

header('Content-Type: application/json; charset=utf-8');

try {
    include_once __DIR__ . '/config.php'; // $pdo (PDO)

    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        throw new Exception('JSON inválido.');
    }

    $formId = isset($data['form_id']) && $data['form_id'] !== '' ? (int)$data['form_id'] : null;
    $titulo = trim($data['titulo'] ?? 'Formulário sem título');
    $questoes = $data['questoes'] ?? [];

    // URL da tela de listagem/de "formularios"
    // Ajuste conforme sua rota real (ex.: '../pages/formularios.php')
    $urlLista = 'formularios.php';

    $pdo->beginTransaction();

    // Se não tiver formId, cria um novo formulário
    if (!$formId) {
        $st = $pdo->prepare("INSERT INTO formularios (titulo) VALUES (:t)");
        $st->execute([':t' => $titulo]);
        $formId = (int)$pdo->lastInsertId();
    } else {
        // Se tiver, atualiza o título
        $st = $pdo->prepare("UPDATE formularios SET titulo = :t WHERE id = :id");
        $st->execute([':t' => $titulo, ':id' => $formId]);

        // Limpa perguntas/alternativas anteriores para regravar (sincronização simples)
        // Ajuste os nomes das tabelas/colunas conforme seu schema real.
        $pdo->prepare("
            DELETE a FROM alternativas a
            JOIN perguntas p ON p.id = a.pergunta_id
            WHERE p.formulario_id = :fid
        ")->execute([':fid' => $formId]);

        $pdo->prepare("DELETE FROM perguntas WHERE formulario_id = :fid")
            ->execute([':fid' => $formId]);
    }

    // Insere perguntas e alternativas
    $stPerg = $pdo->prepare("
        INSERT INTO perguntas (formulario_id, titulo, enunciado, ordem)
        VALUES (:fid, :tit, :enu, :ord)
    ");
    $stAlt = $pdo->prepare("
        INSERT INTO alternativas (pergunta_id, texto, correta, ordem)
        VALUES (:pid, :txt, :cor, :ord)
    ");

    foreach ($questoes as $q) {
        $qTitulo = trim($q['titulo'] ?? '');
        $qEnun   = trim($q['enunciado'] ?? '');
        $qOrdem  = (int)($q['ordem'] ?? 0);
        $stPerg->execute([
            ':fid' => $formId,
            ':tit' => $qTitulo,
            ':enu' => $qEnun,
            ':ord' => $qOrdem
        ]);
        $perguntaId = (int)$pdo->lastInsertId();

        $alternativas = is_array($q['alternativas'] ?? null) ? $q['alternativas'] : [];
        foreach ($alternativas as $a) {
            $aTexto = trim($a['texto'] ?? '');
            if ($aTexto === '') continue; // ignora alternativa vazia
            $aCor   = !empty($a['correta']) ? 1 : 0;
            $aOrdem = (int)($a['ordem'] ?? 0);
            $stAlt->execute([
                ':pid' => $perguntaId,
                ':txt' => $aTexto,
                ':cor' => $aCor,
                ':ord' => $aOrdem
            ]);
        }
    }

    $pdo->commit();

    echo json_encode([
        'ok' => true,
        'form_id' => $formId,
        'redirect' => $urlLista
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => $e->getMessage()
    ]);
}

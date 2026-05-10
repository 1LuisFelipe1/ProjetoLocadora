<?php
require_once __DIR__ . '/../../includes/app.php';

header('Content-Type: application/json; charset=utf-8');


if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit(json_encode(['error' => 'Método não permitido.']));
}

$acao  = $_GET['action'] ?? '';
$tmdb  = new TmdbService(TMDB_API_KEY);

try {
    if ($acao === 'search') {
        $query = trim($_GET['q'] ?? '');
        if (strlen($query) < 2) {
            exit(json_encode(['results' => []]));
        }
        echo json_encode(['results' => $tmdb->search($query)]);

    } elseif ($acao === 'details') {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            exit(json_encode(['error' => 'ID inválido.']));
        }
        $details = $tmdb->getDetails($id);
        if (!$details) {
            http_response_code(404);
            exit(json_encode(['error' => 'Filme não encontrado.']));
        }
        echo json_encode($details);

    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Ação desconhecida.']);
    }

} catch (RuntimeException $e) {
    http_response_code(502);
    echo json_encode(['error' => $e->getMessage()]);
}
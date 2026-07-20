<?php
/** Point d'upload d'une image unique (AJAX), utilisé par l'assistant de promotion. */
require_once __DIR__ . '/bootstrap.php';

header('Content-Type: application/json; charset=UTF-8');

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Session expirée. Reconnectez-vous.']);
    exit;
}
csrf_check();

$r = handle_upload('file');
if (!empty($r['ok'])) {
    echo json_encode(['ok' => true, 'path' => $r['path']]);
} else {
    echo json_encode(['ok' => false, 'error' => $r['error'] ?? 'Aucun fichier reçu.']);
}

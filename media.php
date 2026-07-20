<?php
/**
 * Sert une image téléversée depuis le répertoire inscriptible.
 * Utile en environnement serverless (Vercel), où les uploads vivent hors du
 * dossier statique. Sur un hébergement classique, les fichiers sont servis
 * directement et ce script n'est pas sollicité.
 */
require_once __DIR__ . '/inc/data.php';

$f = basename((string)($_GET['f'] ?? ''));
$path = DIF_UPLOADS . '/' . $f;

if ($f === '' || !is_file($path)) {
    http_response_code(404);
    exit;
}

$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$types = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'gif' => 'image/gif'];
header('Content-Type: ' . ($types[$ext] ?? 'application/octet-stream'));
header('Cache-Control: public, max-age=86400');
header('Content-Length: ' . filesize($path));
readfile($path);

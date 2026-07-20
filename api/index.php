<?php
/**
 * Contrôleur frontal pour Vercel (runtime vercel-php).
 * Les fichiers statiques (CSS, JS, images du dépôt) sont servis par Vercel via
 * le handler "filesystem" ; seules les routes .php et les images téléversées
 * arrivent ici.
 *
 * ⚠️ Sur Vercel, le système de fichiers est en lecture seule et /tmp est
 * éphémère : les modifications de l'admin ne sont PAS conservées entre deux
 * démarrages à froid ou redéploiements. Voir HEBERGEMENT.md pour une option
 * persistante (Docker / Render / Railway).
 */
$root = dirname(__DIR__);
$uri  = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');

// Images téléversées : servies par le handler (fichiers hors dossier statique).
if (preg_match('#^/assets/uploads/(.+)$#', $uri, $m)) {
    $_GET['f'] = $m[1];
    require $root . '/media.php';
    exit;
}

if ($uri === '/' || $uri === '') $uri = '/index.php';

// N'exécuter que les pages .php du site (racine ou /admin), jamais les includes
// ni les fichiers d'infrastructure du back-office.
$rel  = ltrim($uri, '/');
$file = realpath($root . '/' . $rel);
$dir  = $file ? dirname($file) : '';
$deny = ['config.php', 'bootstrap.php', 'layout.php', 'auth.local.php', 'data.php', 'head.php', 'footer.php', 'promo-carousel.php'];
$ok = $file
    && substr($file, -4) === '.php'
    && ($dir === $root || $dir === $root . '/admin')
    && strpos($rel, 'inc/') !== 0
    && !in_array(basename($file), $deny, true);

if (!$ok) {
    http_response_code(404);
    require $root . '/404.php';
    exit;
}

require $file;

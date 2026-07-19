<?php
/**
 * Master DIF — couche de données (contenu en JSON, sans base de données).
 * Chargement / sauvegarde atomique + helpers d'affichage.
 */
declare(strict_types=1);

define('DIF_ROOT', dirname(__DIR__));
define('DIF_CONTENT', DIF_ROOT . '/content');

/** Échappement HTML sûr (UTF-8). */
function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Charge une collection/document JSON. Renvoie $default en cas d'absence/erreur. */
function load_json(string $name, $default = []) {
    $file = DIF_CONTENT . '/' . basename($name) . '.json';
    if (!is_file($file)) return $default;
    $raw = file_get_contents($file);
    if ($raw === false || $raw === '') return $default;
    $data = json_decode($raw, true);
    return (json_last_error() === JSON_ERROR_NONE && $data !== null) ? $data : $default;
}

/** Sauvegarde atomique d'une collection/document JSON. */
function save_json(string $name, $data): bool {
    if (!is_dir(DIF_CONTENT)) @mkdir(DIF_CONTENT, 0775, true);
    $file = DIF_CONTENT . '/' . basename($name) . '.json';
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) return false;
    $tmp = $file . '.tmp' . getmypid();
    if (file_put_contents($tmp, $json, LOCK_EX) === false) return false;
    return rename($tmp, $file); // remplacement atomique
}

/** Formate une date ISO (AAAA-MM-JJ) en français. */
function fr_date(string $iso): string {
    $ts = strtotime($iso);
    if ($ts === false) return $iso;
    $mois = [1=>'janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
    return (int)date('j', $ts) . ' ' . $mois[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

/**
 * Registre des collections éditables par le back-office.
 * Chaque champ : nom => [label, type(text|textarea|date|select), options?].
 */
function collections(): array {
    return [
        'actualites' => [
            'label'    => 'Actualités',
            'singular' => 'article',
            'title'    => 'title',
            'sort'     => 'date',
            'fields'   => [
                'title'    => ['Titre', 'text'],
                'date'     => ['Date', 'date'],
                'category' => ['Catégorie', 'select', ['Conférence','Intervention','Partenariat','Vie associative','Événement','Information']],
                'excerpt'  => ['Résumé (aperçu)', 'textarea'],
                'body'     => ['Contenu', 'textarea'],
            ],
        ],
        'partenaires' => [
            'label'    => 'Partenaires',
            'singular' => 'partenaire',
            'title'    => 'name',
            'fields'   => [
                'name'        => ['Nom', 'text'],
                'tags'        => ['Spécialités (séparées par ·)', 'text'],
                'description' => ['Description', 'textarea'],
                'url'         => ['Site web (https://…)', 'text'],
            ],
        ],
        'promotions' => [
            'label'    => 'Promotions',
            'singular' => 'promotion',
            'title'    => 'years',
            'fields'   => [
                'years'  => ['Années (ex : 2025 — 2026)', 'text'],
                'levels' => ['Niveaux (ex : Master 1 & Master 2)', 'text'],
            ],
        ],
        'temoignages' => [
            'label'    => 'Citations & témoignages',
            'singular' => 'témoignage',
            'title'    => 'name',
            'fields'   => [
                'quote' => ['Citation', 'textarea'],
                'name'  => ['Auteur', 'text'],
                'role'  => ['Fonction / promotion', 'text'],
            ],
        ],
    ];
}

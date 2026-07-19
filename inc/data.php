<?php
/**
 * Master DIF — couche de données (contenu en JSON, sans base de données).
 * Chargement / sauvegarde atomique + helpers d'affichage.
 */
declare(strict_types=1);

define('DIF_ROOT', dirname(__DIR__));
define('DIF_CONTENT', DIF_ROOT . '/content');
define('DIF_UPLOADS', DIF_ROOT . '/assets/uploads');

/** Échappement HTML sûr (UTF-8). */
function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Transforme un texte en slug URL (a-z0-9 et tirets). */
function slugify(string $s): string {
    $s = (string)@iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s);
    $s = strtolower($s);
    $s = preg_replace('/[^a-z0-9]+/', '-', $s);
    $s = trim($s, '-');
    return $s !== '' ? $s : 'article';
}

/** Slug stable d'un article (champ slug, sinon dérivé du titre). */
function article_slug(array $item): string {
    return !empty($item['slug']) ? $item['slug'] : slugify((string)($item['title'] ?? ''));
}

/** Un article est publié sauf s'il est explicitement en brouillon. */
function is_published(array $item): bool {
    return ($item['published'] ?? 'Publié') !== 'Brouillon';
}

/** URL absolue du site (schéma + hôte), pour SEO / sitemap / e-mails. */
function site_base_url(): string {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') === '443');
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'droit-ingenieriefinanciere.fr';
    return $scheme . '://' . $host;
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
 * Traite un upload d'image depuis $_FILES[$field].
 * Renvoie ['ok'=>true,'path'=>'assets/uploads/…'] ou ['ok'=>false,'error'=>'…'].
 * 'empty' => aucun fichier envoyé (à ignorer).
 */
function handle_upload(string $field): array {
    if (empty($_FILES[$field]) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['ok' => false, 'empty' => true];
    }
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'Échec du téléversement (code ' . (int)$f['error'] . ').'];
    }
    if ($f['size'] > 4 * 1024 * 1024) {
        return ['ok' => false, 'error' => 'Image trop lourde (max 4 Mo).'];
    }
    $info = @getimagesize($f['tmp_name']);
    $allowed = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp', IMAGETYPE_GIF => 'gif'];
    if ($info === false || !isset($allowed[$info[2]])) {
        return ['ok' => false, 'error' => 'Format non pris en charge (JPG, PNG, WEBP ou GIF).'];
    }
    if (!is_dir(DIF_UPLOADS)) @mkdir(DIF_UPLOADS, 0775, true);
    $name = date('Y') . '-' . bin2hex(random_bytes(6)) . '.' . $allowed[$info[2]];
    $dest = DIF_UPLOADS . '/' . $name;
    if (!move_uploaded_file($f['tmp_name'], $dest)) {
        return ['ok' => false, 'error' => 'Impossible d\'enregistrer l\'image (droits du dossier assets/uploads ?).'];
    }
    return ['ok' => true, 'path' => 'assets/uploads/' . $name];
}

/* ---- Messages de contact (stockés dans logs/, dossier protégé) ---- */
define('DIF_LOGS', DIF_ROOT . '/logs');
function messages_file(): string { return DIF_LOGS . '/messages.json'; }

function load_messages(): array {
    $f = messages_file();
    if (!is_file($f)) return [];
    $a = json_decode((string)file_get_contents($f), true);
    return is_array($a) ? $a : [];
}

/** Ajout concurrent-safe d'un message (verrou fichier). */
function add_message(array $m): bool {
    if (!is_dir(DIF_LOGS)) @mkdir(DIF_LOGS, 0775, true);
    $fp = @fopen(messages_file(), 'c+');
    if (!$fp) return false;
    if (!flock($fp, LOCK_EX)) { fclose($fp); return false; }
    $raw = stream_get_contents($fp);
    $arr = json_decode($raw ?: '[]', true);
    if (!is_array($arr)) $arr = [];
    array_unshift($arr, $m);
    ftruncate($fp, 0); rewind($fp);
    fwrite($fp, json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    fflush($fp); flock($fp, LOCK_UN); fclose($fp);
    return true;
}

/** Réécriture atomique (mutations depuis le back-office). */
function save_messages(array $arr): bool {
    if (!is_dir(DIF_LOGS)) @mkdir(DIF_LOGS, 0775, true);
    $json = json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $tmp = messages_file() . '.tmp' . getmypid();
    if (file_put_contents($tmp, $json, LOCK_EX) === false) return false;
    return rename($tmp, messages_file());
}

function unread_messages_count(): int {
    $n = 0;
    foreach (load_messages() as $m) { if (empty($m['read'])) $n++; }
    return $n;
}

/* ---- Promotions : années disponibles + membres du trombinoscope ---- */
function promotion_years(): array {
    return array_values(array_filter(array_map(
        fn($p) => $p['years'] ?? '', load_json('promotions', [])
    )));
}

/** Nombre de membres (trombinoscope) par promotion, indexé par années. */
function members_count_by_promotion(): array {
    $counts = [];
    foreach (load_json('membres', []) as $m) {
        $p = $m['promotion'] ?? '';
        if ($p !== '') $counts[$p] = ($counts[$p] ?? 0) + 1;
    }
    return $counts;
}

/** Une promotion a une page à montrer si elle a une photo ou des membres. */
function promo_has_content(array $p, array $mcounts): bool {
    if (!empty($p['photo']) || !empty($p['photo_m1']) || !empty($p['photo_m2'])) return true;
    return ($mcounts[$p['years'] ?? ''] ?? 0) > 0;
}

/** Options d'un champ select, en développant le jeton dynamique @promotions. */
function field_options($opts): array {
    if ($opts === '@promotions') return promotion_years();
    return is_array($opts) ? $opts : [];
}

/**
 * Registre des collections éditables par le back-office.
 * Chaque champ : nom => [label, type(text|textarea|date|select|image), options?].
 */
function collections(): array {
    return [
        'actualites' => [
            'label'    => 'Actualités',
            'singular' => 'article',
            'title'    => 'title',
            'sort'     => 'date',
            'fields'   => [
                'title'     => ['Titre', 'text'],
                'published' => ['Statut', 'select', ['Publié','Brouillon']],
                'date'      => ['Date', 'date'],
                'category'  => ['Catégorie', 'select', ['Conférence','Intervention','Partenariat','Vie associative','Événement','Information']],
                'image'     => ['Image de couverture', 'image'],
                'excerpt'   => ['Résumé (aperçu)', 'textarea'],
                'body'      => ['Contenu', 'textarea'],
            ],
        ],
        'partenaires' => [
            'label'    => 'Partenaires',
            'singular' => 'partenaire',
            'title'    => 'name',
            'fields'   => [
                'name'        => ['Nom', 'text'],
                'logo'        => ['Logo', 'image'],
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
                'years'    => ['Années (ex : 2025 — 2026)', 'text'],
                'levels'   => ['Niveaux (ex : Master 1 & Master 2)', 'text'],
                'photo'    => ['Photo de groupe (promotion entière)', 'image'],
                'photo_m2' => ['Photo Master 2', 'image'],
                'photo_m1' => ['Photo Master 1', 'image'],
            ],
        ],
        'dates' => [
            'label'    => 'Dates clés (admissions)',
            'singular' => 'échéance',
            'title'    => 'label',
            'fields'   => [
                'period' => ['Période (ex : Février — Mars)', 'text'],
                'label'  => ['Intitulé', 'text'],
                'note'   => ['Précision (facultatif)', 'textarea'],
            ],
        ],
        'membres' => [
            'label'    => 'Trombinoscope',
            'singular' => 'membre',
            'title'    => 'name',
            'fields'   => [
                'name'      => ['Nom & prénom', 'text'],
                'promotion' => ['Promotion', 'select', '@promotions'],
                'level'     => ['Niveau', 'select', ['Master 1', 'Master 2']],
                'photo'     => ['Photo', 'image'],
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

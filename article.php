<?php
require_once __DIR__ . '/inc/data.php';

$slug  = (string)($_GET['slug'] ?? '');
$actus = load_json('actualites', []);
usort($actus, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));

// Recherche de l'article publié correspondant au slug.
$idx = null;
foreach ($actus as $k => $a) {
    if (is_published($a) && article_slug($a) === $slug) { $idx = $k; break; }
}

if ($idx === null) {
    http_response_code(404);
    $title = 'Article introuvable — Master DIF';
    $desc  = 'La page demandée n\'existe pas ou n\'est plus disponible.';
    $active = 'actualites';
    include __DIR__ . '/inc/head.php';
    ?>
    <section class="page-hero"><div class="container">
      <p class="crumb"><a href="index.php">Accueil</a> / <a href="actualites.php">Actualités</a> / Introuvable</p>
      <h1>Cet article n'existe pas.</h1>
      <p class="lead">Il a peut-être été retiré ou l'adresse est incorrecte.</p>
    </div></section>
    <section class="section"><div class="container">
      <a class="btn btn-brass" href="actualites.php">← Retour aux actualités</a>
    </div></section>
    <?php
    include __DIR__ . '/inc/footer.php';
    exit;
}

$a = $actus[$idx];
$base = site_base_url();
$title = e($a['title'] ?? 'Actualité') . ' — Master DIF';
$desc  = mb_substr(trim((string)($a['excerpt'] ?? $a['title'] ?? '')), 0, 180);
$active = 'actualites';
$og_type = 'article';
if (!empty($a['image'])) $og_image = $base . '/' . ltrim($a['image'], '/');
$canonical = $base . '/article.php?slug=' . rawurlencode(article_slug($a));

// Précédent / suivant (dans la liste publiée, triée du plus récent au plus ancien)
$pub = array_values(array_filter($actus, 'is_published'));
$pos = null;
foreach ($pub as $k => $x) { if (article_slug($x) === article_slug($a)) { $pos = $k; break; } }
$prev = ($pos !== null && $pos > 0) ? $pub[$pos - 1] : null;             // plus récent
$next = ($pos !== null && $pos < count($pub) - 1) ? $pub[$pos + 1] : null; // plus ancien

include __DIR__ . '/inc/head.php';
?>

<section class="page-hero">
  <div class="container container-narrow" style="margin-inline:auto">
    <p class="crumb"><a href="index.php">Accueil</a> / <a href="actualites.php">Actualités</a> / <?= e($a['category'] ?? '') ?></p>
    <h1><?= e($a['title'] ?? '') ?></h1>
    <p class="lead" style="font-family:var(--mono);font-size:.85rem;letter-spacing:.04em;color:var(--brass)">
      <?= e(fr_date($a['date'] ?? '')) ?> · <?= e($a['category'] ?? '') ?>
    </p>
  </div>
</section>

<article class="section">
  <div class="container container-narrow">
    <?php if (!empty($a['image'])): ?>
    <img src="<?= e($a['image']) ?>" alt="<?= e($a['title'] ?? '') ?>" style="width:100%;border-radius:var(--radius);box-shadow:var(--shadow-md);margin-bottom:2.5rem">
    <?php endif; ?>

    <?php if (!empty($a['excerpt'])): ?>
    <p class="lead"><?= e($a['excerpt']) ?></p>
    <?php endif; ?>

    <div class="article-body">
      <?php foreach (preg_split('/\n\s*\n/', trim((string)($a['body'] ?? ''))) as $para): if (trim($para) === '') continue; ?>
      <p><?= nl2br(e($para)) ?></p>
      <?php endforeach; ?>
    </div>

    <hr class="rule" style="margin:3rem 0 2rem">
    <div class="flex-between" style="gap:1rem">
      <?php if ($prev): ?>
        <a class="link-arrow" href="article.php?slug=<?= e(rawurlencode(article_slug($prev))) ?>">← <?= e($prev['title']) ?></a>
      <?php else: ?><span></span><?php endif; ?>
      <?php if ($next): ?>
        <a class="link-arrow" href="article.php?slug=<?= e(rawurlencode(article_slug($next))) ?>" style="text-align:right"><?= e($next['title']) ?> →</a>
      <?php endif; ?>
    </div>
    <div class="mt-4"><a class="btn btn-ghost" href="actualites.php">← Toutes les actualités</a></div>
  </div>
</article>

<?php include __DIR__ . '/inc/footer.php'; ?>

<?php
require_once __DIR__ . '/inc/data.php';
$title = 'Actualités — Master Droit & Ingénierie Financière | Lyon 3';
$desc  = 'Conférences, mises en situation professionnelle, partenariats et vie associative : toute l\'actualité du Master Droit et Ingénierie Financière de Lyon 3.';
$active = 'actualites';

$all = array_values(array_filter(load_json('actualites', []), 'is_published'));
usort($all, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));

// Catégories disponibles
$cats = [];
foreach ($all as $a) { if (!empty($a['category'])) $cats[$a['category']] = true; }
$cats = array_keys($cats);

// Filtre par catégorie (serveur)
$cat = (string)($_GET['cat'] ?? '');
if ($cat !== '' && in_array($cat, $cats, true)) {
    $filtered = array_values(array_filter($all, fn($a) => ($a['category'] ?? '') === $cat));
} else {
    $cat = '';
    $filtered = $all;
}

// Pagination
$perPage = 9;
$total   = count($filtered);
$pages   = max(1, (int)ceil($total / $perPage));
$page    = min($pages, max(1, (int)($_GET['page'] ?? 1)));
$items   = array_slice($filtered, ($page - 1) * $perPage, $perPage);

$qs = fn($p, $c) => 'actualites.php?' . http_build_query(array_filter(['cat' => $c, 'page' => $p > 1 ? $p : null]));

include __DIR__ . '/inc/head.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="crumb"><a href="index.php">Accueil</a> / Actualités</p>
    <h1>La vie du Master, au fil de l'année.</h1>
    <p class="lead">Conférences d'actualité, mises en situation professionnelle, partenariats et temps forts de l'association : suivez le quotidien d'une promotion en mouvement.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="chips reveal" style="margin-bottom:2.5rem">
      <a class="chip filter<?= $cat === '' ? ' on' : '' ?>" href="actualites.php">Tout</a>
      <?php foreach ($cats as $c): ?>
      <a class="chip filter<?= $cat === $c ? ' on' : '' ?>" href="<?= e($qs(1, $c)) ?>"><?= e($c) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if (empty($items)): ?>
      <p class="center" style="color:var(--muted)">Aucune actualité dans cette catégorie.</p>
    <?php else: ?>
    <div class="grid cols-3">
      <?php foreach ($items as $i => $n): ?>
      <a class="news-card reveal<?= !empty($n['image']) ? ' has-img' : '' ?>"<?= ($i % 3) ? ' data-d="'.($i % 3).'"' : '' ?> href="article.php?slug=<?= e(rawurlencode(article_slug($n))) ?>">
        <?php if (!empty($n['image'])): ?><span class="thumb" style="background-image:url('<?= e($n['image']) ?>')"></span><?php endif; ?>
        <div class="flex-between"><span class="cat"><?= e($n['category'] ?? '') ?></span><span class="date"><?= e(fr_date($n['date'] ?? '')) ?></span></div>
        <h3><?= e($n['title'] ?? '') ?></h3>
        <p><?= e($n['excerpt'] ?? '') ?></p>
      </a>
      <?php endforeach; ?>
    </div>

    <?php if ($pages > 1): ?>
    <nav class="pager" aria-label="Pagination">
      <?php if ($page > 1): ?><a class="pager-btn" href="<?= e($qs($page - 1, $cat)) ?>">← Précédent</a><?php else: ?><span class="pager-btn is-off">← Précédent</span><?php endif; ?>
      <span class="pager-num">Page <?= $page ?> / <?= $pages ?></span>
      <?php if ($page < $pages): ?><a class="pager-btn" href="<?= e($qs($page + 1, $cat)) ?>">Suivant →</a><?php else: ?><span class="pager-btn is-off">Suivant →</span><?php endif; ?>
    </nav>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/inc/footer.php'; ?>

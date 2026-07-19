<?php
require_once __DIR__ . '/inc/data.php';
$title = 'Actualités — Master Droit & Ingénierie Financière | Lyon 3';
$desc  = 'Conférences, mises en situation professionnelle, partenariats et vie associative : toute l\'actualité du Master Droit et Ingénierie Financière de Lyon 3.';
$active = 'actualites';

$actus = load_json('actualites', []);
usort($actus, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
$cats = [];
foreach ($actus as $a) { if (!empty($a['category'])) $cats[$a['category']] = true; }
$cats = array_keys($cats);

$extra_head = "<style>.chip.filter{cursor:pointer}.chip.filter[aria-pressed=\"true\"]{background:var(--ink);color:#fff;border-color:var(--ink)}:root[data-theme=\"dark\"] .chip.filter[aria-pressed=\"true\"]{background:var(--brass);color:#1a1406}</style>";

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
    <div class="chips reveal" style="margin-bottom:2.5rem" id="filters">
      <button class="chip filter" data-cat="*" aria-pressed="true">Tout</button>
      <?php foreach ($cats as $c): ?>
      <button class="chip filter" data-cat="<?= e($c) ?>" aria-pressed="false"><?= e($c) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="grid cols-3" id="news-grid">
      <?php foreach ($actus as $i => $n): ?>
      <a class="news-card reveal"<?= ($i % 3) ? ' data-d="'.($i % 3).'"' : '' ?> href="actualites.php" data-cat="<?= e($n['category'] ?? '') ?>">
        <div class="flex-between"><span class="cat"><?= e($n['category'] ?? '') ?></span><span class="date"><?= e(fr_date($n['date'] ?? '')) ?></span></div>
        <h3><?= e($n['title'] ?? '') ?></h3>
        <p><?= e($n['excerpt'] ?? '') ?></p>
      </a>
      <?php endforeach; ?>
    </div>
    <?php if (empty($actus)): ?>
    <p class="center" style="color:var(--muted)">Aucune actualité pour le moment.</p>
    <?php endif; ?>
  </div>
</section>

<script>
(function () {
  var filters = document.getElementById('filters');
  if (!filters) return;
  filters.addEventListener('click', function (e) {
    var b = e.target.closest('.filter'); if (!b) return;
    var cat = b.getAttribute('data-cat');
    filters.querySelectorAll('.filter').forEach(function (x) { x.setAttribute('aria-pressed', x === b ? 'true' : 'false'); });
    document.querySelectorAll('#news-grid .news-card').forEach(function (card) {
      card.style.display = (cat === '*' || card.getAttribute('data-cat') === cat) ? '' : 'none';
    });
  });
})();
</script>

<?php include __DIR__ . '/inc/footer.php'; ?>

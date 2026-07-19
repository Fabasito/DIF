<?php
require_once __DIR__ . '/inc/data.php';

$slug   = (string)($_GET['slug'] ?? '');
$promos = load_json('promotions', []);

$promo = null;
foreach ($promos as $p) {
    if (slugify((string)($p['years'] ?? '')) === $slug) { $promo = $p; break; }
}

if ($promo === null) {
    http_response_code(404);
    $title = 'Promotion introuvable — Master DIF';
    $desc  = 'La promotion demandée n\'existe pas.';
    $active = 'reseau';
    include __DIR__ . '/inc/head.php';
    ?>
    <section class="page-hero"><div class="container">
      <p class="crumb"><a href="index.php">Accueil</a> / <a href="reseau.php">Le Réseau</a> / Introuvable</p>
      <h1>Cette promotion n'existe pas.</h1>
    </div></section>
    <section class="section"><div class="container"><a class="btn btn-brass" href="reseau.php#promotions">← Retour aux promotions</a></div></section>
    <?php include __DIR__ . '/inc/footer.php'; exit;
}

$years   = (string)($promo['years'] ?? '');
$membres = array_values(array_filter(load_json('membres', []), fn($m) => ($m['promotion'] ?? '') === $years));
// Regroupement par niveau
$groups = [];
foreach ($membres as $m) { $groups[$m['level'] ?? 'Membres'][] = $m; }
ksort($groups);

$title = 'Promotion ' . e($years) . ' — Trombinoscope | Master DIF';
$desc  = 'Le trombinoscope de la promotion ' . $years . ' du Master Droit et Ingénierie Financière de Lyon 3.';
$active = 'reseau';
include __DIR__ . '/inc/head.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="crumb"><a href="index.php">Accueil</a> / <a href="reseau.php">Le Réseau</a> / <a href="reseau.php#promotions">Promotions</a> / <?= e($years) ?></p>
    <h1>Promotion <?= e($years) ?></h1>
    <p class="lead"><?= e($promo['levels'] ?? '') ?> — le trombinoscope de la promotion.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if (empty($membres)): ?>
      <div class="sec-head"><p class="eyebrow">Trombinoscope</p><h2>Bientôt disponible.</h2><p style="color:var(--text-2)">Le trombinoscope de cette promotion sera publié prochainement.</p></div>
    <?php else: foreach ($groups as $level => $people): ?>
      <div class="sec-head" style="margin-bottom:1.5rem"><p class="eyebrow"><?= e($level) ?></p><h2><?= (int)count($people) ?> étudiant<?= count($people) > 1 ? 's' : '' ?></h2></div>
      <div class="trombi">
        <?php foreach ($people as $m): ?>
        <figure class="trombi-card">
          <?php if (!empty($m['photo'])): ?>
            <span class="tphoto" style="background-image:url('<?= e($m['photo']) ?>')"></span>
          <?php else: ?>
            <span class="tphoto tphoto-ph"><?= e(mb_strtoupper(mb_substr((string)($m['name'] ?? '?'), 0, 1))) ?></span>
          <?php endif; ?>
          <figcaption><?= e($m['name'] ?? '') ?></figcaption>
        </figure>
        <?php endforeach; ?>
      </div>
    <?php endforeach; endif; ?>

    <div class="mt-6"><a class="btn btn-ghost" href="reseau.php#promotions">← Toutes les promotions</a></div>
  </div>
</section>

<?php include __DIR__ . '/inc/footer.php'; ?>

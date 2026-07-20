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
$byLevel = [];
foreach ($membres as $m) { $byLevel[$m['level'] ?? 'Membres'][] = $m; }

$base = site_base_url();
$title = 'Promotion ' . e($years) . ' — Master Droit & Ingénierie Financière';
$desc  = 'La promotion ' . $years . ' du Master Droit et Ingénierie Financière de Lyon 3 : photos et trombinoscope.';
$active = 'reseau';
if (!empty($promo['photo'])) $og_image = $base . '/' . ltrim($promo['photo'], '/');

// Niveaux à afficher : M2 puis M1, avec photo et/ou membres
$levels = [];
foreach ([['Master 2', 'photo_m2'], ['Master 1', 'photo_m1']] as [$lvl, $key]) {
    $ph = $promo[$key] ?? '';
    $mm = $byLevel[$lvl] ?? [];
    if ($ph !== '' || $mm) $levels[] = [$lvl, $ph, $mm];
}

include __DIR__ . '/inc/head.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="crumb"><a href="index.php">Accueil</a> / <a href="reseau.php">Le Réseau</a> / <a href="reseau.php#promotions">Promotions</a> / <?= e($years) ?></p>
    <h1>Promotion <?= e($years) ?></h1>
    <?php if (!empty($promo['levels'])): ?><p class="lead"><?= e($promo['levels']) ?> — les visages d'une année du Master.</p><?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if (!empty($promo['photo'])): ?>
    <figure class="promo-figure reveal">
      <img src="<?= e($promo['photo']) ?>" alt="Photo de groupe de la promotion <?= e($years) ?>">
      <figcaption>La promotion <?= e($years) ?><?= !empty($promo['levels']) ? ' — ' . e($promo['levels']) : '' ?></figcaption>
    </figure>
    <?php endif; ?>

    <?php foreach ($levels as $li => [$lvl, $ph, $mm]): ?>
    <div class="trombi-sec"<?= $mm ? ' data-marquee data-dir="' . ($li % 2 ? '-1' : '1') . '"' : '' ?>>
      <div class="sec-head" style="margin:2.5rem 0 1.5rem"><p class="eyebrow"><?= e($lvl) ?></p>
        <h2><?= $mm ? count($mm) . ' étudiant' . (count($mm) > 1 ? 's' : '') : 'La promotion ' . e($lvl) ?></h2>
      </div>
      <?php if ($ph): ?>
      <figure class="promo-figure">
        <img src="<?= e($ph) ?>" alt="Photo du <?= e($lvl) ?>, promotion <?= e($years) ?>" loading="lazy">
      </figure>
      <?php endif; ?>
      <?php if ($mm): ?>
      <div class="mq-wrap">
        <button class="mq-arrow prev" data-car-prev aria-label="Faire défiler vers la gauche">←</button>
        <div class="car-track mq-track" tabindex="0" role="group" aria-label="Trombinoscope <?= e($lvl) ?> — bandeau défilant en continu">
          <?php foreach ($mm as $m): ?>
          <figure class="trombi-card">
            <?php if (!empty($m['photo'])): ?>
              <span class="tphoto" style="background-image:url('<?= e($m['photo']) ?>')"></span>
            <?php else: ?>
              <span class="tphoto tphoto-ph"><?= e(mb_strtoupper(mb_substr((string)($m['name'] ?? '?'), 0, 1))) ?></span>
            <?php endif; ?>
            <figcaption><?= e($m['name'] ?? '') ?></figcaption>
            <?php if (!empty($m['email']) || !empty($m['linkedin'])): ?>
            <div class="tcontact">
              <?php if (!empty($m['email'])): ?>
              <a href="mailto:<?= e($m['email']) ?>" aria-label="Envoyer un e-mail à <?= e($m['name'] ?? '') ?>" title="E-mail">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
              </a>
              <?php endif; ?>
              <?php if (!empty($m['linkedin'])): ?>
              <a href="<?= e($m['linkedin']) ?>" target="_blank" rel="noopener" aria-label="Profil LinkedIn de <?= e($m['name'] ?? '') ?>" title="LinkedIn">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.38-1.85 3.61 0 4.28 2.38 4.28 5.47v6.27zM5.34 7.43a2.07 2.07 0 1 1 0-4.14 2.07 2.07 0 0 1 0 4.14zM7.12 20.45H3.55V9h3.57v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.73v20.54C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.73C24 .77 23.2 0 22.22 0z"/></svg>
              </a>
              <?php endif; ?>
            </div>
            <?php endif; ?>
          </figure>
          <?php endforeach; ?>
        </div>
        <button class="mq-arrow next" data-car-next aria-label="Faire défiler vers la droite">→</button>
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <?php if (empty($promo['photo']) && empty($levels)): ?>
    <div class="sec-head"><p class="eyebrow">Trombinoscope</p><h2>Bientôt disponible.</h2><p style="color:var(--text-2)">Les photos de cette promotion seront publiées prochainement.</p></div>
    <?php endif; ?>

    <div class="mt-6"><a class="btn btn-ghost" href="reseau.php#promotions">← Toutes les promotions</a></div>
  </div>
</section>

<?php include __DIR__ . '/inc/footer.php'; ?>

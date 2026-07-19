<?php
/**
 * Carrousel des promotions.
 * Attend : $promos (load_json('promotions')), $mcounts (members_count_by_promotion()).
 */
$promos  = $promos  ?? load_json('promotions', []);
$mcounts = $mcounts ?? members_count_by_promotion();
?>
<div class="carousel" data-carousel>
  <div class="car-track" tabindex="0" role="group" aria-label="Les promotions du Master, de la plus récente à la plus ancienne">
    <?php foreach ($promos as $pi => $p):
        $yrs = (string)($p['years'] ?? '');
        if ($yrs === '') continue;
        $has = ($mcounts[$yrs] ?? 0) > 0;
        $current = $pi === 0; ?>
    <article class="promo-card<?= $current ? ' is-current' : '' ?>">
      <span class="p-eyebrow"><?= $current ? 'Promotion actuelle' : 'Promotion' ?></span>
      <span class="p-years"><?= e($yrs) ?></span>
      <?php if (!empty($p['levels'])): ?><span class="p-levels"><?= e($p['levels']) ?></span><?php endif; ?>
      <?php if ($has): ?>
      <a class="p-link" href="promotion.php?slug=<?= e(rawurlencode(slugify($yrs))) ?>">Voir le trombinoscope <span>→</span></a>
      <?php endif; ?>
    </article>
    <?php endforeach; ?>
  </div>
  <div class="car-progress" aria-hidden="true"><i></i></div>
</div>

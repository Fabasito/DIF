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
        $has = promo_has_content($p, $mcounts);
        $thumb = $p['photo'] ?: ($p['photo_m2'] ?? '') ?: ($p['photo_m1'] ?? '');
        $current = $pi === 0;
        $url = 'promotion.php?slug=' . rawurlencode(slugify($yrs)); ?>
    <article class="promo-card<?= $current ? ' is-current' : '' ?><?= $thumb ? ' has-photo' : '' ?>">
      <?php if ($thumb): ?>
      <a class="p-photo" href="<?= e($url) ?>" style="background-image:url('<?= e($thumb) ?>')" aria-label="Photo de la promotion <?= e($yrs) ?>"></a>
      <?php endif; ?>
      <div class="p-body">
        <span class="p-eyebrow"><?= $current ? 'Promotion actuelle' : 'Promotion' ?></span>
        <span class="p-years"><?= e($yrs) ?></span>
        <?php if (!empty($p['levels'])): ?><span class="p-levels"><?= e($p['levels']) ?></span><?php endif; ?>
        <?php if ($has): ?>
        <a class="p-link" href="<?= e($url) ?>">Voir la promotion <span>→</span></a>
        <?php endif; ?>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
  <div class="car-progress" aria-hidden="true"><i></i></div>
</div>

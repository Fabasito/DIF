<?php
require_once __DIR__ . '/inc/data.php';
$title = 'Le Réseau — Partenaires & Promotions | Master DIF Lyon 3';
$desc  = 'Les cabinets et entreprises partenaires du Master Droit et Ingénierie Financière, et l\'histoire de ses promotions.';
$active = 'reseau';

$partners = load_json('partenaires', []);
$promos   = load_json('promotions', []);
$nb_part  = count($partners);

include __DIR__ . '/inc/head.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="crumb"><a href="index.php">Accueil</a> / Le Réseau</p>
    <h1>Un réseau qui ouvre les portes.</h1>
    <p class="lead">Plus de 400 diplômés, une quinzaine de cabinets et d'entreprises partenaires, et l'appui de l'Université Jean Moulin Lyon 3 : un écosystème au service de l'insertion et du rayonnement du Master.</p>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="grid cols-3 center reveal">
      <div><div class="num" style="font-size:2.8rem;color:var(--brass)"><span data-count="400" data-suffix="+">400+</span></div><p style="color:var(--text-2)">diplômés dans le monde</p></div>
      <div><div class="num" style="font-size:2.8rem;color:var(--brass)"><span data-count="<?= (int)$nb_part ?>" data-suffix="+"><?= (int)$nb_part ?>+</span></div><p style="color:var(--text-2)">partenaires actifs</p></div>
      <div><div class="num" style="font-size:2.8rem;color:var(--brass)"><span data-count="25" data-suffix="+">25+</span></div><p style="color:var(--text-2)">promotions depuis 1999</p></div>
    </div>
  </div>
</section>

<!-- PARTENAIRES -->
<section class="section alt">
  <div class="container">
    <div class="sec-head"><p class="eyebrow">Nos partenaires</p><h2>Des cabinets et entreprises de référence.</h2></div>
    <div class="grid cols-2">
      <?php foreach ($partners as $i => $p): ?>
      <article class="partner-card reveal"<?= ($i % 2) ? ' data-d="1"' : '' ?>>
        <div class="nm"><?= e($p['name'] ?? '') ?></div>
        <?php if (!empty($p['tags'])): ?><div class="meta"><?= e($p['tags']) ?></div><?php endif; ?>
        <p><?= e($p['description'] ?? '') ?></p>
        <?php if (!empty($p['url'])): ?><a class="link-arrow" href="<?= e($p['url']) ?>" target="_blank" rel="noopener">Accéder au site →</a><?php endif; ?>
      </article>
      <?php endforeach; ?>
    </div>
    <div class="duo mt-6" style="grid-template-columns:1fr" id="partenaire">
      <div><span class="tag">Institution</span><h3>Université Jean Moulin Lyon 3</h3><p>Trois campus, plus de 29 000 étudiants (dont 5 000 internationaux), 80 associations et 6 facultés — le cadre d'excellence du Master DIF.</p></div>
    </div>
  </div>
</section>

<!-- PROMOTIONS -->
<section class="section" id="promotions">
  <div class="container">
    <div class="split">
      <div>
        <p class="eyebrow">Les promotions</p>
        <h2>Une grande famille, promotion après promotion.</h2>
        <p>Chaque année, une nouvelle promotion de Master 1 et de Master 2 rejoint le Master DIF. Les trombinoscopes retracent l'histoire de cette communauté depuis plus de deux décennies.</p>
        <a class="link-arrow mt-2" href="actualites.php">Suivre la vie des promotions →</a>
      </div>
      <div class="reveal">
        <?php foreach ($promos as $p): ?>
        <div class="promo-row"><span class="yr"><?= e($p['years'] ?? '') ?></span><span class="lv"><?= e($p['levels'] ?? '') ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- DEVENIR PARTENAIRE -->
<section class="section-tight">
  <div class="container">
    <div class="cta-band reveal">
      <div class="flex-between" style="align-items:end">
        <div><p class="eyebrow on-ink">Devenez partenaire</p><h2>Rejoignez notre réseau de partenaires.</h2><p>Cabinets, entreprises et institutions : associez votre nom à une formation d'excellence et rencontrez nos talents.</p></div>
        <div class="cta-row" style="margin:0"><a class="btn btn-brass" href="contact.php#partenaire">Nous contacter <span class="arw">→</span></a></div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/inc/footer.php'; ?>

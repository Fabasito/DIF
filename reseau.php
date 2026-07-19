<?php
require_once __DIR__ . '/inc/data.php';
$title = 'Le Réseau — Partenaires & Promotions | Master DIF Lyon 3';
$desc  = 'Les cabinets et entreprises partenaires du Master Droit et Ingénierie Financière, et l\'histoire de ses promotions.';
$active = 'reseau';

$partners = load_json('partenaires', []);
$promos   = load_json('promotions', []);
$nb_part  = count($partners);
$mcounts  = members_count_by_promotion();

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
        <?php if (!empty($p['logo'])): ?><img class="plogo-card" src="<?= e($p['logo']) ?>" alt="<?= e($p['name'] ?? '') ?>"><?php endif; ?>
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
  <div class="container" data-carousel>
    <div class="flex-between sec-head" style="max-width:none;margin-bottom:2.2rem;align-items:end">
      <div style="max-width:640px">
        <p class="eyebrow">Les promotions</p>
        <h2>Une grande famille, promotion après promotion.</h2>
        <p style="margin-bottom:0">De 2011 à aujourd'hui, chaque promotion écrit une page de l'histoire du Master. Les trombinoscopes retracent celle de cette communauté, année après année.</p>
      </div>
      <div class="car-nav">
        <button class="car-btn" data-car-prev aria-label="Promotions précédentes">←</button>
        <button class="car-btn" data-car-next aria-label="Promotions suivantes">→</button>
      </div>
    </div>
    <?php include __DIR__ . '/inc/promo-carousel.php'; ?>
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

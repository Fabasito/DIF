<?php
require_once __DIR__ . '/inc/data.php';
$title = 'Master Droit & Ingénierie Financière — Université Jean Moulin Lyon 3';
$desc  = 'Formation d\'excellence à double compétence droit des affaires et finance d\'entreprise. Master 1 & 2 sélectifs, professionnalisants, à l\'Université Jean Moulin Lyon 3.';
$active = '';

$site    = load_json('site', []);
$hero    = $site['hero'] ?? [];
$stats   = $site['stats'] ?? [];
$actus   = load_json('actualites', []);
$partners= load_json('partenaires', []);
$temoins = load_json('temoignages', []);

$actus   = array_values(array_filter($actus, 'is_published'));
usort($actus, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
$latest  = array_slice($actus, 0, 3);
$logos   = array_slice($partners, 0, 8);
$quote   = $temoins[0] ?? null;

include __DIR__ . '/inc/head.php';
?>

<!-- HERO -->
<section class="hero">
  <div class="bg"><img src="assets/img/lyon-palais-nuit.jpg" alt="Le Palais de l'Université Jean Moulin illuminé sur les berges du Rhône à Lyon, la nuit"></div>
  <div class="container hero-inner">
    <p class="kicker"><?= e($hero['kicker'] ?? 'Université Jean Moulin Lyon 3 · depuis 1999') ?></p>
    <h1 class="h-display"><?= e($hero['title'] ?? '') ?> <span class="accent"><?= e($hero['accent'] ?? '') ?></span></h1>
    <p class="sub"><?= e($hero['subtitle'] ?? '') ?></p>
    <div class="cta-row">
      <a class="btn btn-brass" href="admissions.php">Rejoindre la promotion <span class="arw">→</span></a>
      <a class="btn btn-ghost" href="formation.php">Découvrir la formation</a>
    </div>
  </div>
  <div class="ticker">
    <div class="container">
      <div class="ticker-inner">
        <?php foreach ($stats as $s): ?>
        <div class="cell"><div class="v"><?= e($s['value'] ?? '') ?></div><div class="k"><?= e($s['label'] ?? '') ?></div></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- THESE / DOUBLE COMPETENCE -->
<section class="section">
  <div class="container">
    <div class="split">
      <div>
        <p class="eyebrow">La double compétence</p>
        <h2>Comprendre l'entreprise dans toutes ses dimensions.</h2>
        <p class="lead">Rares sont les profils capables de saisir à la fois l'enjeu juridique et l'enjeu financier d'une opération. Le Master DIF les forme — pour auditer, construire et sécuriser des montages adaptés aux réalités du droit comme de la gestion.</p>
      </div>
      <div class="duo reveal">
        <div>
          <span class="tag">01 — Droit</span>
          <h3>Le droit des affaires</h3>
          <p>Sociétés, fiscalité, restructurations, contentieux, opérations de haut de bilan : la maîtrise des cadres qui gouvernent la vie des entreprises.</p>
        </div>
        <div>
          <span class="tag">02 — Finance</span>
          <h3>La finance d'entreprise</h3>
          <p>Analyse financière, évaluation, ingénierie financière, consolidation : lire les chiffres et bâtir les schémas qui les traduisent en stratégie.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PILIERS -->
<section class="section alt">
  <div class="container">
    <div class="sec-head">
      <p class="eyebrow">Pourquoi le DIF</p>
      <h2>Quatre piliers d'une réussite reconnue.</h2>
    </div>
    <div class="grid cols-4">
      <article class="card reveal">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 21h18M5 21V8l7-4 7 4v13M9 21v-6h6v6"/></svg>
        <div class="idx">01</div>
        <h3>Un corps professoral d'élite</h3>
        <p>Universitaires du plus haut niveau et praticiens renommés : avocats d'affaires, directeurs juridiques et financiers, magistrats, consultants.</p>
      </article>
      <article class="card reveal" data-d="1">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 3v18M5 8l7-5 7 5M4 21h16M6 12v6M18 12v6"/></svg>
        <div class="idx">02</div>
        <h3>Une formation de pointe</h3>
        <p>Une maquette sans cesse renouvelée : stratégie RSE, initiation aux crypto-actifs, fiscalité internationale et prix de transfert.</p>
      </article>
      <article class="card reveal" data-d="2">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 15a7 7 0 1 0 0-14 7 7 0 0 0 0 14ZM8.5 13.5 7 22l5-3 5 3-1.5-8.5"/></svg>
        <div class="idx">03</div>
        <h3>Une sélection rigoureuse</h3>
        <p>Étude des dossiers et entretiens pour une vingtaine d'étudiants par promotion — un encadrement privilégié dès le Master 1.</p>
      </article>
      <article class="card reveal" data-d="3">
        <svg class="ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM23 21v-2a4 4 0 0 0-3-3.87M16 3.13A4 4 0 0 1 16 11"/></svg>
        <div class="idx">04</div>
        <h3>Un réseau puissant</h3>
        <p>Plus de 400 diplômés implantés dans les meilleurs cabinets et entreprises, moteurs de l'insertion des nouvelles promotions.</p>
      </article>
    </div>
  </div>
</section>

<!-- FORMATION PREVIEW -->
<section class="section">
  <div class="container">
    <div class="split reverse">
      <div class="media reveal">
        <img src="assets/img/lyon-palais-nuit.jpg" alt="Berges du Rhône, Université Jean Moulin Lyon 3">
        <div class="badge"><div class="v">2 ans</div><div class="k">Master 1 &amp; Master 2</div></div>
      </div>
      <div>
        <p class="eyebrow">La formation</p>
        <h2>Deux années pour bâtir un profil rare.</h2>
        <p>Accessible sur sélection après une licence en droit ou en gestion, le cursus articule des bases théoriques solides, une spécialisation technique et une professionnalisation exigeante.</p>
        <ul class="flist">
          <li><span class="n">M1</span><div><h3>Fondations</h3><p>Analyse financière, fiscalité, comptabilité, droit des sûretés, financement des entreprises. Stage de 3 mois minimum et mémoire.</p></div></li>
          <li><span class="n">M2</span><div><h3>Spécialisation</h3><p>Ingénierie financière, groupes de sociétés, contentieux des affaires, droit boursier, RSE, crypto-actifs. Stage de 3 à 6 mois et mémoire.</p></div></li>
        </ul>
        <a class="link-arrow mt-2" href="formation.php">Voir le programme détaillé →</a>
      </div>
    </div>
  </div>
</section>

<!-- PROFESSIONNALISATION -->
<section class="section ink">
  <div class="container">
    <div class="sec-head center">
      <p class="eyebrow center on-ink">La professionnalisation</p>
      <h2>Opérationnels avant même le diplôme.</h2>
      <p>Des stages « longue durée » obligatoires en cabinet, en entreprise ou en institution, complétés tout au long de l'année par des mises en situation professionnelle et des conférences d'actualité.</p>
    </div>
    <div class="grid cols-3">
      <div class="reveal center">
        <div class="num" style="font-size:3rem;color:var(--brass)"><span data-count="3" data-suffix=" mois">3</span></div>
        <p style="color:var(--on-ink-mut)">de stage minimum dès le Master 1, mémoire à l'appui.</p>
      </div>
      <div class="reveal center" data-d="1">
        <div class="num" style="font-size:3rem;color:var(--brass)"><span data-count="6" data-suffix=" mois">6</span></div>
        <p style="color:var(--on-ink-mut)">de stage possible en Master 2, tremplin vers l'emploi.</p>
      </div>
      <div class="reveal center" data-d="2">
        <div class="num" style="font-size:3rem;color:var(--brass)"><span data-count="15" data-suffix="+">15</span></div>
        <p style="color:var(--on-ink-mut)">cabinets et entreprises partenaires qui accueillent nos étudiants.</p>
      </div>
    </div>
  </div>
</section>

<!-- DEBOUCHES -->
<section class="section">
  <div class="container">
    <div class="split">
      <div>
        <p class="eyebrow">Les débouchés</p>
        <h2>Une diversité de carrières, un même socle d'excellence.</h2>
        <p>La transversalité du diplôme ouvre les portes des cabinets, des banques, de l'audit-conseil et des directions juridiques et financières. Toute entreprise confrontée à des choix financiers complexes recherche ce juriste-financier capable de concilier options financières, stratégie juridique et fiscalité.</p>
        <a class="link-arrow mt-2" href="formation.php#debouches">Explorer les débouchés →</a>
      </div>
      <div class="reveal">
        <div class="chips">
          <span class="chip">Avocat d'affaires</span>
          <span class="chip">Fusions-acquisitions</span>
          <span class="chip">Fiscaliste</span>
          <span class="chip">Auditeur financier</span>
          <span class="chip">Expert-comptable</span>
          <span class="chip">Private equity</span>
          <span class="chip">Consultant</span>
          <span class="chip">Direction juridique</span>
          <span class="chip">Banque &amp; financement</span>
          <span class="chip">Notariat</span>
          <span class="chip">Propriété intellectuelle</span>
          <span class="chip">Restructuring</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PARTENAIRES -->
<section class="section alt">
  <div class="container">
    <div class="sec-head center">
      <p class="eyebrow center">Ils nous font confiance</p>
      <h2>Un réseau de cabinets et d'entreprises de premier plan.</h2>
    </div>
    <div class="logo-grid reveal">
      <?php foreach ($logos as $p): ?>
      <div class="cell">
        <?php if (!empty($p['logo'])): ?>
          <img class="plogo" src="<?= e($p['logo']) ?>" alt="<?= e($p['name'] ?? '') ?>">
        <?php else: ?>
          <div><div class="nm"><?= e($p['name'] ?? '') ?></div><div class="ct"><?= e($p['tags'] ?? '') ?></div></div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="center mt-4"><a class="link-arrow" href="reseau.php">Tous nos partenaires →</a></div>
  </div>
</section>

<?php if ($quote): ?>
<!-- CITATION -->
<section class="section ink">
  <div class="container container-narrow">
    <div class="quote reveal">
      <blockquote>« <?= e($quote['quote'] ?? '') ?> »</blockquote>
      <div class="who"><?= e($quote['name'] ?? '') ?></div>
      <div class="role"><?= e($quote['role'] ?? '') ?></div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ACTUALITES -->
<section class="section">
  <div class="container">
    <div class="flex-between sec-head" style="max-width:none;margin-bottom:2rem">
      <div>
        <p class="eyebrow">La vie du Master</p>
        <h2>Actualités</h2>
      </div>
      <a class="link-arrow" href="actualites.php">Toutes les actualités →</a>
    </div>
    <div class="grid cols-3">
      <?php foreach ($latest as $i => $n): ?>
      <a class="news-card reveal<?= !empty($n['image']) ? ' has-img' : '' ?>"<?= $i ? ' data-d="'.$i.'"' : '' ?> href="article.php?slug=<?= e(rawurlencode(article_slug($n))) ?>">
        <?php if (!empty($n['image'])): ?><span class="thumb" style="background-image:url('<?= e($n['image']) ?>')"></span><?php endif; ?>
        <div class="flex-between"><span class="cat"><?= e($n['category'] ?? '') ?></span><span class="date"><?= e(fr_date($n['date'] ?? '')) ?></span></div>
        <h3><?= e($n['title'] ?? '') ?></h3>
        <p><?= e($n['excerpt'] ?? '') ?></p>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section-tight">
  <div class="container">
    <div class="cta-band reveal">
      <div class="flex-between" style="align-items:end">
        <div>
          <p class="eyebrow on-ink">Candidatures ouvertes</p>
          <h2>Prêt à rejoindre la prochaine promotion&nbsp;?</h2>
          <p>Licence en droit ou en gestion, dossier, CV, lettre de motivation et entretien. Faites le premier pas vers un profil rare et recherché.</p>
        </div>
        <div class="cta-row" style="margin:0">
          <a class="btn btn-brass" href="admissions.php">Candidater <span class="arw">→</span></a>
          <a class="btn btn-ghost" href="contact.php">Nous contacter</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/inc/footer.php'; ?>

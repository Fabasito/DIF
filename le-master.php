<?php
require_once __DIR__ . '/inc/data.php';
$title = 'Le Master — Droit & Ingénierie Financière | Lyon 3';
$desc  = 'Le mot du directeur, l\'histoire et les valeurs du Master Droit et Ingénierie Financière de l\'Université Jean Moulin Lyon 3.';
$active = 'le-master';

$site = load_json('site', []);
$parrain = $site['parrain'] ?? null;
$resp_email = $site['contact']['responsable_email'] ?? 'quentin.nemoz-rajot@univ-lyon3.fr';

include __DIR__ . '/inc/head.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="crumb"><a href="index.php">Accueil</a> / Le Master</p>
    <h1>Une formation pionnière, devenue une référence.</h1>
    <p class="lead">Créé à Lyon 3, le Master Droit et Ingénierie Financière mêle depuis son origine le droit et la finance — une singularité aujourd'hui reconnue par les professionnels comme par les étudiants.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="grid cols-3 reveal">
      <div>
        <div class="num" style="font-size:2.6rem;color:var(--brass)">1999</div>
        <p style="color:var(--text-2)">Création de la formation par Madame Sabine Dana-Démaret, puis développement sous la direction de Madame Sylvie Thomasset-Pierre.</p>
      </div>
      <div>
        <div class="num" style="font-size:2.6rem;color:var(--brass)">2022</div>
        <p style="color:var(--text-2)">Quentin Nemoz-Rajot succède au Professeur Jean-Pierre Viennois à la tête du Master.</p>
      </div>
      <div>
        <div class="num" style="font-size:2.6rem;color:var(--brass)">400+</div>
        <p style="color:var(--text-2)">Diplômés formés au fil des promotions, implantés dans les meilleurs cabinets et entreprises.</p>
      </div>
    </div>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <div class="split">
      <div>
        <p class="eyebrow">Le mot du directeur</p>
        <h2>« Le Master DIF entend rester novateur. »</h2>
        <p>Depuis le 1<sup>er</sup> septembre 2022, j'ai l'honneur de succéder à Monsieur le Professeur Jean-Pierre Viennois à la tête du Master. Cette formation, en son temps pionnière, est désormais reconnue pour son excellence bien au-delà du territoire lyonnais.</p>
        <p>Cette réussite repose sur des piliers que j'entends consolider : des intervenants experts et reconnus, une formation de pointe sans cesse renouvelée, une sélection rigoureuse des étudiants et un réseau en pleine croissance qui favorise leur insertion.</p>
        <p>Par-delà un cursus reconnu, c'est l'état d'esprit et le dynamisme des étudiants qui, je l'espère, sauront vous séduire. Devenir diplômé du Master DIF, c'est intégrer une grande famille qui se singularise par la curiosité et l'échange.</p>
        <p class="num" style="color:var(--brass);margin-top:1.5rem">Quentin Nemoz-Rajot</p>
        <p style="color:var(--muted);font-size:.9rem;margin-top:.1rem">Directeur du Master · Maître de conférences · Responsable pédagogique M1 &amp; M2</p>
      </div>
      <div class="reveal">
        <div class="duo" style="grid-template-columns:1fr">
          <div>
            <span class="tag">Cap 2026</span>
            <h3>Une maquette en mouvement</h3>
            <p>La nouvelle maquette du Master 2 introduit un cours de stratégie RSE et une initiation aux crypto-actifs, pour répondre aux évolutions juridiques, financières et sociétales.</p>
          </div>
          <div style="border-top:1px solid var(--line)">
            <span class="tag">Contact pédagogique</span>
            <h3 style="font-size:1.2rem">Quentin Nemoz-Rajot</h3>
            <p><a class="link-arrow" href="mailto:<?= e($resp_email) ?>"><?= e($resp_email) ?></a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="sec-head"><p class="eyebrow">Nos valeurs</p><h2>Les piliers de l'excellence.</h2></div>
    <ul class="flist reveal">
      <li><span class="n">01</span><div><h3>Des intervenants experts</h3><p>Universitaires en droit et en gestion du plus haut niveau académique, et praticiens renommés : avocats d'affaires, consultants, directeurs de banques, directeurs juridiques, dirigeants d'entreprises, magistrats.</p></div></li>
      <li><span class="n">02</span><div><h3>Une formation de pointe</h3><p>Un programme sans cesse renouvelé pour répondre aux besoins du monde professionnel et aux évolutions sociétales.</p></div></li>
      <li><span class="n">03</span><div><h3>Une sélection rigoureuse</h3><p>Une admission après étude des dossiers et entretiens, garantissant un encadrement privilégié pour une vingtaine d'étudiants par promotion.</p></div></li>
      <li><span class="n">04</span><div><h3>Un réseau en croissance</h3><p>Une communauté de diplômés qui favorise l'insertion professionnelle des nouveaux venus et fait rayonner le diplôme.</p></div></li>
    </ul>
  </div>
</section>

<?php if ($parrain): ?>
<section class="section ink">
  <div class="container container-narrow">
    <p class="eyebrow center on-ink">Le parrain de la promotion 2026</p>
    <div class="quote reveal">
      <blockquote>« <?= e($parrain['quote'] ?? '') ?> »</blockquote>
      <div class="who"><?= e($parrain['name'] ?? '') ?></div>
      <div class="role"><?= e($parrain['role'] ?? '') ?></div>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section-tight">
  <div class="container">
    <div class="cta-band reveal">
      <div class="flex-between" style="align-items:end">
        <div>
          <p class="eyebrow on-ink">Aller plus loin</p>
          <h2>Découvrez le programme des deux années.</h2>
          <p>Maquette M1 et M2, objectifs, stages et débouchés : tout le détail de la formation.</p>
        </div>
        <div class="cta-row" style="margin:0">
          <a class="btn btn-brass" href="formation.php">Voir la formation <span class="arw">→</span></a>
          <a class="btn btn-ghost" href="admissions.php">Candidater</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/inc/footer.php'; ?>

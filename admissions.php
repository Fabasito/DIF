<?php
require_once __DIR__ . '/inc/data.php';
$title = 'Admissions & Candidature — Master Droit & Ingénierie Financière | Lyon 3';
$desc  = 'Conditions d\'admission, profils recherchés et procédure de candidature au Master 1 et Master 2 Droit et Ingénierie Financière de Lyon 3.';
$active = 'admissions';
$site = load_json('site', []);
$faculty = $site['contact']['faculty_url'] ?? 'https://facdedroit.univ-lyon3.fr/master-droit-et-ingenierie-financiere-2';
$resp_email = $site['contact']['responsable_email'] ?? 'quentin.nemoz-rajot@univ-lyon3.fr';
$email = $site['contact']['email'] ?? 'associationdif1999@gmail.com';
$dates = load_json('dates', []);
$extra_head = <<<CSS
<style>
  .step { display:grid; grid-template-columns:auto 1fr; gap:1.6rem; padding:1.8rem 0; border-top:1px solid var(--line); }
  .step:last-child{ border-bottom:1px solid var(--line); }
  .step .no { font-family:var(--mono); font-size:1.3rem; color:var(--brass); width:2.4rem; }
  .step h3 { margin-bottom:.4rem; }
  .step p { color:var(--text-2); margin:0; }
  .acc-item { border-top:1px solid var(--line); }
  .acc-item:last-child{ border-bottom:1px solid var(--line); }
  .acc-head { width:100%; text-align:left; background:none; border:0; padding:1.3rem 0; display:flex; justify-content:space-between; align-items:center; gap:1rem; font-family:var(--serif); font-size:1.2rem; color:var(--text); }
  .acc-head .pm { font-family:var(--mono); color:var(--brass); transition:transform .25s ease; }
  .acc-item.open .acc-head .pm { transform:rotate(45deg); }
  .acc-body { max-height:0; overflow:hidden; transition:max-height .3s ease; }
  .acc-item.open .acc-body { max-height:360px; }
  .acc-body p { color:var(--text-2); padding-bottom:1.3rem; margin:0; }
  /* Calendrier du candidat */
  .cal { list-style:none; margin:0; padding:0; position:relative; }
  .cal::before { content:""; position:absolute; left:7px; top:10px; bottom:10px; width:1px; background:var(--line); }
  .cal li { position:relative; padding:0 0 2rem 2.2rem; }
  .cal li:last-child { padding-bottom:0; }
  .cal li::before { content:""; position:absolute; left:0; top:6px; width:15px; height:15px; border-radius:50%;
    background:var(--paper); border:2px solid var(--brass); }
  .cal .c-period { font-family:var(--mono); font-size:.76rem; letter-spacing:.14em; text-transform:uppercase; color:var(--brass); }
  .cal h3 { font-size:1.2rem; margin:.25rem 0 .25rem; }
  .cal p { margin:0; color:var(--text-2); font-size:.94rem; max-width:52ch; }
</style>
CSS;
include __DIR__ . '/inc/head.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="crumb"><a href="index.php">Accueil</a> / Admissions</p>
    <h1>Rejoignez une promotion d'exception.</h1>
    <p class="lead">Le Master DIF sélectionne une vingtaine d'étudiants par année. L'admission repose sur l'examen d'un dossier, d'un CV et d'une lettre de motivation, puis d'un entretien pour le Master 1.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="sec-head"><p class="eyebrow">À qui s'adresse le Master</p><h2>Deux portes d'entrée.</h2></div>
    <div class="grid cols-2">
      <div class="card reveal">
        <div class="idx">Master 1</div>
        <h3>Après une licence</h3>
        <p>Ouvert aux titulaires d'une licence en droit ou en gestion, ou d'un diplôme équivalent. L'admissibilité est prononcée après examen du dossier, du CV et de la lettre de motivation, puis un entretien oral déterminant.</p>
      </div>
      <div class="card reveal" data-d="1">
        <div class="idx">Master 2</div>
        <h3>Après un Master 1</h3>
        <p>Ouvert aux titulaires d'un Master 1 en droit ou en gestion, ou d'un diplôme équivalent. L'admission est communiquée après examen du dossier de candidature, du CV et de la lettre de motivation.</p>
      </div>
    </div>
  </div>
</section>

<section class="section alt" id="candidature">
  <div class="container">
    <div class="split">
      <div>
        <p class="eyebrow">La procédure</p>
        <h2>Quatre étapes vers l'admission.</h2>
        <p>Un processus lisible et exigeant, pensé pour révéler la motivation et le potentiel de chaque candidat.</p>
        <a class="btn btn-brass mt-3" href="<?= e($faculty) ?>" target="_blank" rel="noopener">Candidater sur le portail Lyon 3 <span class="arw">→</span></a>
        <p style="font-size:.85rem;color:var(--muted);margin-top:1rem">Les candidatures s'effectuent via la plateforme officielle de l'Université Jean Moulin Lyon 3.</p>
      </div>
      <div class="reveal">
        <div class="step"><span class="no">01</span><div><h3>Constitution du dossier</h3><p>Réunissez vos relevés de notes, votre CV et une lettre de motivation soignée.</p></div></div>
        <div class="step"><span class="no">02</span><div><h3>Dépôt de la candidature</h3><p>Déposez votre dossier sur la plateforme officielle de l'Université, dans les délais annoncés.</p></div></div>
        <div class="step"><span class="no">03</span><div><h3>Étude &amp; entretien</h3><p>Examen du dossier, puis entretien oral (pour le M1) déterminant l'admission.</p></div></div>
        <div class="step"><span class="no">04</span><div><h3>Réponse d'admission</h3><p>L'admissibilité puis l'admission vous sont communiquées par courrier.</p></div></div>
      </div>
    </div>
  </div>
</section>

<!-- CALENDRIER DU CANDIDAT -->
<?php if (!empty($dates)): ?>
<section class="section" id="calendrier">
  <div class="container">
    <div class="split" style="align-items:start">
      <div>
        <p class="eyebrow">Le calendrier du candidat</p>
        <h2>Une année de candidature, étape par étape.</h2>
        <p>Les grandes échéances d'une candidature au Master, de l'ouverture de la plateforme à la rentrée. Dates données à titre indicatif : le calendrier officiel fait foi sur <a href="https://www.monmaster.gouv.fr" target="_blank" rel="noopener" style="color:var(--brass)">monmaster.gouv.fr</a> et sur le site de la Faculté.</p>
        <a class="btn btn-ghost mt-2" href="<?= e($faculty) ?>" target="_blank" rel="noopener">Consulter le calendrier officiel</a>
      </div>
      <ol class="cal reveal">
        <?php foreach ($dates as $d): ?>
        <li>
          <span class="c-period"><?= e($d['period'] ?? '') ?></span>
          <h3><?= e($d['label'] ?? '') ?></h3>
          <?php if (!empty($d['note'])): ?><p><?= e($d['note']) ?></p><?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ol>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CONSEILS -->
<section class="section alt" id="conseils">
  <div class="container">
    <div class="sec-head"><p class="eyebrow">Nos conseils</p><h2>Mettre toutes les chances de votre côté.</h2></div>
    <div class="grid cols-4">
      <article class="card reveal">
        <div class="idx">01</div>
        <h3>Soignez le dossier</h3>
        <p>Des relevés cohérents comptent plus qu'une moyenne parfaite : montrez une progression et un intérêt constant pour le droit des affaires et la finance (matières choisies, mémoires, MOOC…).</p>
      </article>
      <article class="card reveal" data-d="1">
        <div class="idx">02</div>
        <h3>Une lettre qui vous ressemble</h3>
        <p>Expliquez pourquoi la double compétence, pourquoi ce Master et pourquoi vous — avec des exemples concrets plutôt que des formules toutes faites. Une page suffit.</p>
      </article>
      <article class="card reveal" data-d="2">
        <div class="idx">03</div>
        <h3>Valorisez vos expériences</h3>
        <p>Stage, job étudiant, engagement associatif, séjour à l'étranger : tout ce qui témoigne de votre curiosité et de votre capacité à vous investir a sa place dans le CV.</p>
      </article>
      <article class="card reveal" data-d="3">
        <div class="idx">04</div>
        <h3>Préparez l'entretien</h3>
        <p>Suivez l'actualité des affaires (une opération de M&amp;A récente, une réforme fiscale…), sachez présenter votre projet en deux minutes, et venez avec des questions sincères sur la formation.</p>
      </article>
    </div>
    <p style="margin-top:2rem;color:var(--muted);font-size:.9rem">Une question avant de candidater ? L'association répond volontiers aux futurs candidats : <a href="mailto:<?= e($email) ?>" style="color:var(--brass)"><?= e($email) ?></a></p>
  </div>
</section>

<section class="section ink">
  <div class="container">
    <div class="sec-head center"><p class="eyebrow center on-ink">Un dossier qui se démarque</p><h2>Ce que nous recherchons.</h2></div>
    <div class="grid cols-3">
      <div class="reveal"><div class="num" style="color:var(--brass);font-size:1.1rem;letter-spacing:.14em;text-transform:uppercase">Rigueur</div><p style="color:var(--on-ink-mut)">Un parcours académique solide en droit ou en gestion, et le goût du travail exigeant.</p></div>
      <div class="reveal" data-d="1"><div class="num" style="color:var(--brass);font-size:1.1rem;letter-spacing:.14em;text-transform:uppercase">Curiosité</div><p style="color:var(--on-ink-mut)">L'envie de croiser le droit et la finance, deux univers que la formation réunit.</p></div>
      <div class="reveal" data-d="2"><div class="num" style="color:var(--brass);font-size:1.1rem;letter-spacing:.14em;text-transform:uppercase">Projet</div><p style="color:var(--on-ink-mut)">Une motivation claire et un projet professionnel que les stages viendront affiner.</p></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container container-narrow">
    <div class="sec-head center"><p class="eyebrow center">Questions fréquentes</p><h2>Vous hésitez encore&nbsp;?</h2></div>
    <div data-acc>
      <div class="acc-item open"><button class="acc-head" aria-expanded="true">Faut-il une licence de droit&nbsp;? <span class="pm">+</span></button><div class="acc-body"><p>Non. Le Master est accessible aux titulaires d'une licence en droit <em>ou</em> en gestion, ou d'un diplôme équivalent. C'est précisément cette diversité de profils qui nourrit la double compétence.</p></div></div>
      <div class="acc-item"><button class="acc-head" aria-expanded="false">Combien d'étudiants par promotion&nbsp;? <span class="pm">+</span></button><div class="acc-body"><p>Une vingtaine d'étudiants dès le Master 1, pour garantir un encadrement privilégié et un suivi personnalisé.</p></div></div>
      <div class="acc-item"><button class="acc-head" aria-expanded="false">Les stages sont-ils obligatoires&nbsp;? <span class="pm">+</span></button><div class="acc-body"><p>Oui. Un stage de 3 mois minimum en Master 1, puis un stage de 3 à 6 mois en Master 2, chacun donnant lieu à un mémoire.</p></div></div>
      <div class="acc-item"><button class="acc-head" aria-expanded="false">Où déposer ma candidature&nbsp;? <span class="pm">+</span></button><div class="acc-body"><p>Les candidatures s'effectuent sur la plateforme officielle de l'Université Jean Moulin Lyon 3. Le lien figure ci-dessus dans la section « La procédure ».</p></div></div>
      <div class="acc-item"><button class="acc-head" aria-expanded="false">Qui contacter pour plus d'informations&nbsp;? <span class="pm">+</span></button><div class="acc-body"><p>Le responsable pédagogique, Quentin Nemoz-Rajot (<?= e($resp_email) ?>), ou l'association des étudiants (<?= e($email) ?>).</p></div></div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/inc/footer.php'; ?>

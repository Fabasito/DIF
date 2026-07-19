<?php
require_once __DIR__ . '/inc/data.php';
$title = 'La Formation — Master Droit & Ingénierie Financière | Lyon 3';
$desc  = 'Programme détaillé du Master 1 et du Master 2 Droit et Ingénierie Financière : objectifs, maquette, stages et débouchés.';
$active = 'formation';
include __DIR__ . '/inc/head.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="crumb"><a href="index.php">Accueil</a> / La Formation</p>
    <h1>Deux années, une double qualification.</h1>
    <p class="lead">La filière comprend un Master 1 et un Master 2, sur sélection après une licence en droit ou en gestion. Objectif : former des spécialistes de haut niveau en droit et finance d'entreprise, dans leurs dimensions française et internationale.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="sec-head">
      <p class="eyebrow">Objectifs &amp; atouts</p>
      <h2>Une vision complète de l'entreprise.</h2>
      <p>Former des spécialistes de haut niveau en droit et finance d'entreprise, capables de maîtriser l'ingénierie juridique et financière dans ses dimensions française et internationale — et sensibilisés à la transversalité des disciplines juridiques, fiscales, comptables et financières.</p>
    </div>
    <div class="grid cols-2" style="gap:0 3rem;align-items:start">
      <ul class="flist reveal">
        <li><span class="n">01</span><div><h3>Double qualification</h3><p>Droit des affaires et finance d'entreprise, reconnue par les professionnels et les recruteurs.</p></div></li>
        <li><span class="n">02</span><div><h3>Véritable professionnalisation</h3><p>3 mois de stage minimum en Master 1, 3 à 6 mois en Master 2, en entreprise, cabinet d'avocats ou institution — pour former des acteurs immédiatement opérationnels.</p></div></li>
        <li><span class="n">03</span><div><h3>Corps professoral d'excellence</h3><p>Universitaires en droit et en gestion du plus haut niveau académique, et praticiens renommés : avocats d'affaires, consultants, directeurs de banques, directeurs juridiques, dirigeants, magistrats, spécialistes en communication juridique et financière.</p></div></li>
        <li><span class="n">04</span><div><h3>Sélection &amp; encadrement</h3><p>Une sélection à l'entrée menant à un encadrement privilégié et optimal, avec une vingtaine d'étudiants par promotion dès le Master 1.</p></div></li>
      </ul>
      <ul class="flist reveal" data-d="1">
        <li><span class="n">05</span><div><h3>Formation pionnière</h3><p>Innovante et adaptée, elle débouche sur une grande diversité de carrières : avocat, auditeur financier, expert-comptable, consultant en finance ou en fiscalité…</p></div></li>
        <li><span class="n">06</span><div><h3>Un réseau mondial</h3><p>Plus de 400 diplômés implantés dans les meilleurs cabinets et entreprises, dans le monde entier.</p></div></li>
        <li><span class="n">07</span><div><h3>Diplôme classé</h3><p>Depuis plusieurs années parmi les meilleurs programmes français en « Droit des affaires &amp; Management » (SMBG) et bien positionné en « Business &amp; Commercial Law » en Europe de l'Ouest (Eduniversal).</p></div></li>
      </ul>
    </div>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <div class="sec-head center"><p class="eyebrow center">La maquette</p><h2>Le programme, année par année.</h2></div>

    <div class="center" style="margin-bottom:2.5rem">
      <div class="tabs" data-tabs role="tablist" aria-label="Programme">
        <button role="tab" id="t-m1" aria-controls="p-m1" aria-selected="true">Master 1</button>
        <button role="tab" id="t-m2" aria-controls="p-m2" aria-selected="false">Master 2</button>
        <button role="tab" id="t-deb" aria-controls="p-deb" aria-selected="false">Débouchés</button>
      </div>
    </div>

    <div role="tabpanel" id="p-m1" aria-labelledby="t-m1">
      <div class="grid cols-2">
        <div>
          <p class="term-label">Semestre 1 — Fondations théoriques</p>
          <p class="term-obj">Développement de solides bases en droit et en finance.</p>
          <ul class="course-list">
            <li><span class="c-name">Analyse financière</span><span class="c-h">20h + 15h TD</span></li>
            <li><span class="c-name">Fiscalité de l'entreprise</span><span class="c-h">30h + 15h TD</span></li>
            <li><span class="c-name">Comptabilité générale</span><span class="c-h">30h</span></li>
            <li><span class="c-name">Droit de la concurrence</span><span class="c-h">30h</span></li>
            <li><span class="c-name">Comptabilité approfondie</span><span class="c-h">20h</span></li>
            <li><span class="c-name">Droit des sûretés</span><span class="c-h">30h</span></li>
            <li><span class="c-name">Droit international privé</span><span class="c-h">30h</span></li>
            <li><span class="c-name">Anglais juridique et financier</span><span class="c-h">15h TD</span></li>
            <li><span class="c-name">Techniques quantitatives / méthodologie</span><span class="c-h">20h</span></li>
          </ul>
        </div>
        <div>
          <p class="term-label">Semestre 2 — Droit &amp; finance d'entreprise</p>
          <p class="term-obj">Renforcement des connaissances techniques.</p>
          <ul class="course-list">
            <li><span class="c-name">Fiscalité de l'entreprise 2</span><span class="c-h">30h + 15h TD</span></li>
            <li><span class="c-name">Financement des entreprises</span><span class="c-h">30h + 15h TD</span></li>
            <li><span class="c-name">Droit des entreprises en difficulté</span><span class="c-h">30h</span></li>
            <li><span class="c-name">Droit patrimonial de l'entrepreneur</span><span class="c-h">30h</span></li>
            <li><span class="c-name">Évaluation et ingénierie financière</span><span class="c-h">20h</span></li>
            <li><span class="c-name">Contrôle de gestion</span><span class="c-h">20h</span></li>
            <li><span class="c-name">Politique financière de l'entreprise</span><span class="c-h">20h</span></li>
            <li><span class="c-name">Droit de la propriété industrielle</span><span class="c-h">30h</span></li>
            <li><span class="c-name">Anglais juridique et financier</span><span class="c-h">15h TD</span></li>
          </ul>
        </div>
      </div>
      <div class="duo mt-6" style="grid-template-columns:1fr">
        <div><span class="tag">Stage M1</span><h3 style="font-size:1.25rem">3 mois minimum, mémoire à l'appui</h3><p>Le Master 1 s'achève par un stage obligatoire impliquant la rédaction d'un mémoire, pour appliquer les connaissances acquises.</p></div>
      </div>
    </div>

    <div role="tabpanel" id="p-m2" aria-labelledby="t-m2" hidden>
      <div class="grid cols-2">
        <div>
          <p class="term-label">Semestre 1 — Spécialisation &amp; ouverture</p>
          <ul class="course-list">
            <li><span class="c-name">Ingénierie financière</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Groupes de sociétés</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Droit des sociétés approfondi</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Contentieux des affaires</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Évaluation des entreprises</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Fiscalité de l'entreprise approfondie</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Gestion financière internationale</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Relations sociales de l'entreprise</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Introduction aux crypto-actifs</span><span class="c-h">Nouveau</span></li>
            <li><span class="c-name">Anglais des affaires</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Conférences d'actualité</span><span class="c-h">Cycle</span></li>
          </ul>
        </div>
        <div>
          <p class="term-label">Semestre 2 — Technicité &amp; international</p>
          <ul class="course-list">
            <li><span class="c-name">Droit financier approfondi</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Droit international et européen des sociétés</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Fiscalité internationale et prix de transfert</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Techniques de consolidation</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Droit du commerce international</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Introduction au droit boursier</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">L'assurance des risques d'entreprise</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Opérations sociétaires</span><span class="c-h">Cours</span></li>
            <li><span class="c-name">Stratégie RSE</span><span class="c-h">Nouveau</span></li>
            <li><span class="c-name">Mise en situation professionnelle</span><span class="c-h">Atelier</span></li>
            <li><span class="c-name">Stage et mémoire</span><span class="c-h">3 à 6 mois</span></li>
          </ul>
        </div>
      </div>
      <div class="duo mt-6" style="grid-template-columns:1fr">
        <div><span class="tag">Stage M2</span><h3 style="font-size:1.25rem">3 à 6 mois, tremplin vers l'emploi</h3><p>Le Master 2 se termine par un stage obligatoire de 3 à 6 mois impliquant la rédaction d'un mémoire, pour faciliter l'adaptation aux réalités professionnelles.</p></div>
      </div>
    </div>

    <div role="tabpanel" id="p-deb" aria-labelledby="t-deb" hidden>
      <div id="debouches" class="split">
        <div>
          <h2>Les débouchés</h2>
          <p>La double compétence et la transversalité du diplôme permettent d'envisager une grande diversité de carrières. Toute entreprise d'une certaine dimension recherche un juriste-financier capable de concilier les options financières avec les stratégies juridiques et fiscales.</p>
        </div>
        <ul class="flist reveal">
          <li><span class="n">◆</span><div><h3>Cabinets d'avocats spécialisés</h3><p>Droit des affaires, fiscalité, fusions-acquisitions, propriété intellectuelle.</p></div></li>
          <li><span class="n">◆</span><div><h3>Banques &amp; audit-conseil</h3><p>Sociétés d'audit et de conseil, cabinets d'expertise comptable.</p></div></li>
          <li><span class="n">◆</span><div><h3>Notariat &amp; grands groupes</h3><p>Études notariales, directions juridiques et financières de grands groupes industriels ou commerciaux.</p></div></li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="cta-band reveal">
      <div class="flex-between" style="align-items:end">
        <div><p class="eyebrow on-ink">Prochaine étape</p><h2>Ce programme vous ressemble&nbsp;?</h2><p>Découvrez les conditions d'admission et déposez votre candidature.</p></div>
        <div class="cta-row" style="margin:0"><a class="btn btn-brass" href="admissions.php">Voir les admissions <span class="arw">→</span></a></div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/inc/footer.php'; ?>

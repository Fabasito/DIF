<?php
require_once __DIR__ . '/inc/data.php';
http_response_code(404);
$title = 'Page introuvable — Master DIF';
$desc  = 'La page demandée n\'existe pas ou a été déplacée.';
$active = '';
include __DIR__ . '/inc/head.php';
?>
<section class="page-hero">
  <div class="container">
    <p class="crumb">Erreur 404</p>
    <h1>Cette page n'existe pas.</h1>
    <p class="lead">Le lien est peut-être erroné, ou la page a été déplacée. Retrouvez votre chemin ci-dessous.</p>
  </div>
</section>
<section class="section">
  <div class="container">
    <div class="cta-row">
      <a class="btn btn-brass" href="index.php">Retour à l'accueil <span class="arw">→</span></a>
      <a class="btn btn-ghost" href="actualites.php">Voir les actualités</a>
      <a class="btn btn-ghost" href="formation.php">La formation</a>
    </div>
  </div>
</section>
<?php include __DIR__ . '/inc/footer.php'; ?>

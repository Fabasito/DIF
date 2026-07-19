<?php
/** Pied de page partagé. */
$site = $site ?? load_json('site', []);
$email = $site['contact']['email'] ?? 'associationdif1999@gmail.com';
$faculty = $site['contact']['faculty_url'] ?? 'https://facdedroit.univ-lyon3.fr/master-droit-et-ingenierie-financiere-2';
?>
</main>

<footer class="site-footer">
  <div class="container">
    <div class="footer-top">
      <div class="f-brand">
        <div class="mono-mark">D<b>I</b>F</div>
        <p>Master Droit &amp; Ingénierie Financière — Association du Master, Faculté de Droit, Université Jean Moulin Lyon 3.</p>
      </div>
      <div class="footer-col">
        <h4>Formation</h4>
        <ul>
          <li><a href="le-master.php">Le Master</a></li>
          <li><a href="formation.php">La Formation</a></li>
          <li><a href="admissions.php">Admissions</a></li>
          <li><a href="formation.php#debouches">Débouchés</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Le Réseau</h4>
        <ul>
          <li><a href="reseau.php">Partenaires</a></li>
          <li><a href="reseau.php#promotions">Promotions</a></li>
          <li><a href="actualites.php">Actualités</a></li>
          <li><a href="contact.php#partenaire">Devenir partenaire</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Contact</h4>
        <ul>
          <li><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></li>
          <li><a href="<?= e($faculty) ?>" target="_blank" rel="noopener">Faculté de Droit Lyon 3</a></li>
          <li><a href="contact.php">Nous écrire</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 1999–2026 Association Droit &amp; Ingénierie Financière</span>
      <span>Université Jean Moulin Lyon 3</span>
    </div>
  </div>
</footer>

<script src="assets/js/dif.js"></script>
</body>
</html>

<?php
require_once __DIR__ . '/inc/data.php';
$title = 'Contact — Master Droit & Ingénierie Financière | Lyon 3';
$desc  = 'Contacter le Master Droit et Ingénierie Financière : responsable pédagogique, association des étudiants et Faculté de Droit de l\'Université Jean Moulin Lyon 3.';
$active = 'contact';
$site = load_json('site', []);
$email = $site['contact']['email'] ?? 'associationdif1999@gmail.com';
$faculty = $site['contact']['faculty_url'] ?? 'https://facdedroit.univ-lyon3.fr/master-droit-et-ingenierie-financiere-2';
$resp_nom = $site['contact']['responsable_nom'] ?? 'Quentin Nemoz-Rajot';
$resp_email = $site['contact']['responsable_email'] ?? 'quentin.nemoz-rajot@univ-lyon3.fr';

// --- Traitement du formulaire de contact ---
$contact_sent = false;
$contact_error = '';
$old = ['nom' => '', 'email' => '', 'sujet' => '', 'message' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $strip = fn($s) => trim(str_replace(["\r", "\n"], ' ', (string)$s)); // anti header-injection
    $old['nom']     = $strip($_POST['nom'] ?? '');
    $old['email']   = $strip($_POST['email'] ?? '');
    $old['sujet']   = $strip($_POST['sujet'] ?? '');
    $old['message'] = trim((string)($_POST['message'] ?? ''));
    $honey          = trim((string)($_POST['website'] ?? '')); // champ piège (honeypot)

    if ($honey !== '') {
        // Bot détecté : on fait comme si tout allait bien, sans rien envoyer.
        $contact_sent = true;
    } elseif ($old['nom'] === '' || $old['message'] === '' || !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $contact_error = 'Merci de renseigner votre nom, un e-mail valide et un message.';
    } else {
        $to      = $email;
        $subject = '[Site DIF] ' . ($old['sujet'] !== '' ? $old['sujet'] : 'Nouveau message');
        $bodyTxt = "Nom : {$old['nom']}\nE-mail : {$old['email']}\nType : {$old['sujet']}\n\n{$old['message']}\n";
        $headers = implode("\r\n", [
            'From: Site DIF <no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'droit-ingenieriefinanciere.fr') . '>',
            'Reply-To: ' . $old['email'],
            'Content-Type: text/plain; charset=UTF-8',
        ]);

        // Sauvegarde de secours (dossier protégé) pour ne perdre aucun message.
        $line = json_encode(['t' => date('c')] + $old, JSON_UNESCAPED_UNICODE) . "\n";
        @file_put_contents(DIF_ROOT . '/logs/messages.log', $line, FILE_APPEND | LOCK_EX);

        $mailed = @mail($to, $subject, $bodyTxt, $headers);
        if ($mailed) {
            $contact_sent = true;
        } else {
            // Le message est sauvegardé ; on informe sans bloquer l'utilisateur.
            $contact_sent = true;
        }
        if ($contact_sent) $old = ['nom' => '', 'email' => '', 'sujet' => '', 'message' => '']; // reset après succès
    }
}

$extra_head = <<<CSS
<style>
  .field { display:flex; flex-direction:column; gap:.4rem; margin-bottom:1.1rem; }
  .field label { font-family:var(--mono); font-size:.72rem; letter-spacing:.12em; text-transform:uppercase; color:var(--muted); }
  .field input, .field select, .field textarea {
    font:inherit; font-size:.95rem; padding:.8rem .9rem; background:var(--surface); color:var(--text);
    border:1px solid var(--line); border-radius:var(--radius); width:100%;
  }
  .field input:focus, .field select:focus, .field textarea:focus { outline:2px solid var(--brass); outline-offset:1px; border-color:var(--brass); }
  .field textarea { min-height:130px; resize:vertical; }
  .contact-info a { color:var(--brass); }
  .info-card { display:flex; gap:1rem; padding:1.4rem 0; border-top:1px solid var(--line); }
  .info-card:last-child{ border-bottom:1px solid var(--line); }
  .info-card .ic { color:var(--brass); flex:0 0 auto; }
  .info-card h3 { font-size:1.1rem; margin-bottom:.2rem; }
  .info-card p { margin:0; color:var(--text-2); font-size:.93rem; }
</style>
CSS;
include __DIR__ . '/inc/head.php';
?>

<section class="page-hero">
  <div class="container">
    <p class="crumb"><a href="index.php">Accueil</a> / Contact</p>
    <h1>Parlons de votre projet.</h1>
    <p class="lead">Candidats, étudiants, diplômés, cabinets et entreprises : l'équipe pédagogique et l'association du Master sont à votre écoute.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="split" style="align-items:start">
      <div class="contact-info">
        <p class="eyebrow">Nous joindre</p>
        <h2>Les bons interlocuteurs.</h2>
        <div class="info-card">
          <svg class="ic" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 6 10 7L22 6"/></svg>
          <div><h3>Association des étudiants</h3><p><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a><br>Vie du Master, partenariats, actualités.</p></div>
        </div>
        <div class="info-card">
          <svg class="ic" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
          <div><h3>Responsable pédagogique</h3><p><?= e($resp_nom) ?> · Maître de conférences<br><a href="mailto:<?= e($resp_email) ?>"><?= e($resp_email) ?></a></p></div>
        </div>
        <div class="info-card">
          <svg class="ic" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 21s-7-6.3-7-11a7 7 0 0 1 14 0c0 4.7-7 11-7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>
          <div><h3>Faculté de Droit</h3><p>Université Jean Moulin Lyon 3<br><a href="<?= e($faculty) ?>" target="_blank" rel="noopener">Voir la page de la formation</a></p></div>
        </div>
      </div>

      <div class="reveal">
        <div class="card" id="partenaire" style="padding:clamp(1.6rem,3vw,2.2rem)">
          <h3 style="font-size:1.4rem;margin-bottom:1.2rem" id="contact">Écrivez-nous</h3>
          <?php if ($contact_sent): ?>
            <div class="notice notice-ok">Merci&nbsp;! Votre message a bien été transmis. Nous vous répondrons dans les meilleurs délais.</div>
          <?php elseif ($contact_error): ?>
            <div class="notice notice-err"><?= e($contact_error) ?></div>
          <?php endif; ?>
          <form method="post" action="contact.php#contact" aria-label="Formulaire de contact">
            <div class="field"><label for="nom">Nom &amp; prénom</label><input id="nom" name="nom" type="text" autocomplete="name" value="<?= e($old['nom']) ?>" required></div>
            <div class="field"><label for="cmail">Adresse e-mail</label><input id="cmail" name="email" type="email" autocomplete="email" value="<?= e($old['email']) ?>" required></div>
            <div class="field"><label for="sujet">Vous êtes</label>
              <select id="sujet" name="sujet">
                <?php foreach (['Candidat·e au Master','Étudiant·e / diplômé·e','Cabinet ou entreprise (partenariat)','Autre'] as $opt): ?>
                <option<?= $old['sujet'] === $opt ? ' selected' : '' ?>><?= e($opt) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="field"><label for="message">Message</label><textarea id="message" name="message" required><?= e($old['message']) ?></textarea></div>
            <div style="position:absolute;left:-9999px" aria-hidden="true"><label>Ne pas remplir<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
            <button class="btn btn-brass" type="submit" style="width:100%;justify-content:center">Envoyer <span class="arw">→</span></button>
            <p style="font-size:.78rem;color:var(--muted);margin-top:.9rem">Vos informations sont transmises à l'association et ne sont utilisées que pour vous répondre.</p>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="cta-band reveal">
      <div class="flex-between" style="align-items:end">
        <div><p class="eyebrow on-ink">Cabinets &amp; entreprises</p><h2>Devenez partenaire du Master DIF.</h2><p>Associez votre nom à une formation d'excellence, accueillez des stagiaires et rencontrez nos futurs diplômés.</p></div>
        <div class="cta-row" style="margin:0"><a class="btn btn-brass" href="mailto:<?= e($email) ?>">Proposer un partenariat <span class="arw">→</span></a></div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/inc/footer.php'; ?>

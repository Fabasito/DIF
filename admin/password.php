<?php
require_once __DIR__ . '/bootstrap.php';
require_login();
require_once __DIR__ . '/layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $cur = (string)($_POST['current'] ?? '');
    $new = (string)($_POST['new'] ?? '');
    $cfm = (string)($_POST['confirm'] ?? '');

    if (!password_verify($cur, admin_password_hash())) {
        flash('Mot de passe actuel incorrect.', 'err');
    } elseif (strlen($new) < 8) {
        flash('Le nouveau mot de passe doit contenir au moins 8 caractères.', 'err');
    } elseif ($new !== $cfm) {
        flash('La confirmation ne correspond pas au nouveau mot de passe.', 'err');
    } elseif (set_admin_password($new)) {
        flash('Mot de passe mis à jour.');
    } else {
        flash('Impossible d\'écrire le nouveau mot de passe. Le dossier /admin doit être accessible en écriture, ou modifiez ADMIN_PASSWORD_HASH dans admin/config.php.', 'err');
    }
    header('Location: password.php');
    exit;
}

admin_header('', 'Mot de passe');
?>
<div class="page-head"><div><div class="eyebrow">Sécurité</div><h1>Changer le mot de passe</h1></div></div>

<form class="stack" method="post" action="password.php" style="max-width:520px">
  <?= csrf_field() ?>
  <div class="field"><label for="current">Mot de passe actuel</label><input id="current" name="current" type="password" autocomplete="current-password" required></div>
  <div class="field"><label for="new">Nouveau mot de passe</label><input id="new" name="new" type="password" autocomplete="new-password" minlength="8" required><span class="hint">8 caractères minimum.</span></div>
  <div class="field"><label for="confirm">Confirmer le nouveau mot de passe</label><input id="confirm" name="confirm" type="password" autocomplete="new-password" required></div>
  <div class="form-actions"><button class="btn brass" type="submit">Mettre à jour</button><a class="btn ghost" href="index.php">Retour</a></div>
</form>

<?php admin_footer(); ?>

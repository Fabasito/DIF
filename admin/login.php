<?php
require_once __DIR__ . '/bootstrap.php';

if (is_logged_in()) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $pw = (string)($_POST['password'] ?? '');
    if (password_verify($pw, ADMIN_PASSWORD_HASH)) {
        login_success();
        header('Location: index.php');
        exit;
    }
    usleep(600000); // throttle basique contre le bruteforce
    $error = 'Mot de passe incorrect.';
}
?><!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Connexion — Admin DIF</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="login-wrap">
  <form class="login-card" method="post" action="login.php">
    <div class="lb">D<b>I</b>F · Administration</div>
    <div class="ls">Espace réservé — connexion requise</div>
    <?= csrf_field() ?>
    <?php if ($error): ?><div class="flash flash-err"><?= e($error) ?></div><?php endif; ?>
    <div class="field">
      <label for="password">Mot de passe</label>
      <input id="password" name="password" type="password" autocomplete="current-password" autofocus required>
    </div>
    <div class="form-actions" style="margin-top:1.1rem">
      <button class="btn brass" type="submit" style="width:100%;justify-content:center">Se connecter</button>
    </div>
  </form>
</div>
</body>
</html>

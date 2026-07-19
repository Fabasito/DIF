<?php
/** Gabarit HTML du back-office. */

function admin_header(string $active = '', string $pageTitle = 'Administration'): void {
    $items = [
        'index'       => ['Tableau de bord', 'index.php'],
        'actualites'  => ['Actualités', 'collection.php?type=actualites'],
        'partenaires' => ['Partenaires', 'collection.php?type=partenaires'],
        'promotions'  => ['Promotions', 'collection.php?type=promotions'],
        'membres'     => ['Trombinoscope', 'collection.php?type=membres'],
        'temoignages' => ['Citations', 'collection.php?type=temoignages'],
        'messages'    => ['Messages', 'messages.php'],
        'settings'    => ['Réglages du site', 'settings.php'],
    ];
    $unread = unread_messages_count();
    ?><!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= e($pageTitle) ?> — Admin DIF</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-shell">
  <aside class="admin-side">
    <div class="admin-brand"><span class="mk">D<b>I</b>F</span> <span>Administration</span></div>
    <nav>
      <?php foreach ($items as $key => [$label, $href]): ?>
      <a href="<?= e($href) ?>"<?= $active === $key ? ' class="on"' : '' ?>><?= e($label) ?><?php if ($key === 'messages' && $unread): ?> <span class="nav-badge"><?= (int)$unread ?></span><?php endif; ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="admin-side-foot">
      <a href="../index.php" target="_blank" rel="noopener">↗ Voir le site</a>
      <a href="password.php">Mot de passe</a>
      <a href="logout.php" class="danger">Se déconnecter</a>
    </div>
  </aside>
  <main class="admin-main">
    <?php foreach (take_flash() as $f): ?>
    <div class="flash flash-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
    <?php endforeach; ?>
<?php }

function admin_footer(): void { ?>
  </main>
</div>
</body>
</html>
<?php }

<?php
/** Entête partagé. Définir avant l'include : $title, $desc, $active. */
require_once __DIR__ . '/data.php';
$title   = $title   ?? 'Master Droit & Ingénierie Financière — Lyon 3';
$desc    = $desc    ?? 'Formation d\'excellence à double compétence droit des affaires et finance d\'entreprise.';
$active  = $active  ?? '';
$nav = [
    'le-master'   => 'Le Master',
    'formation'   => 'La Formation',
    'admissions'  => 'Admissions',
    'reseau'      => 'Le Réseau',
    'actualites'  => 'Actualités',
    'contact'     => 'Contact',
];
$extra_head = $extra_head ?? '';
?><!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($desc) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..600;1,9..144,300..500&family=IBM+Plex+Mono:wght@400;500&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
<meta name="theme-color" content="#0f1a33">
<link rel="stylesheet" href="assets/css/dif.css">
<?= $extra_head ?>
</head>
<body>
<a class="skip-link" href="#main">Aller au contenu</a>

<header class="site-header">
  <div class="container bar">
    <a class="brand" href="index.php" aria-label="Accueil — Master Droit et Ingénierie Financière">
      <span class="mono-mark">D<b>I</b>F</span>
      <span class="brand-sub">Master Droit &amp;<br>Ingénierie Financière<br>Lyon 3</span>
    </a>
    <nav class="nav primary" aria-label="Navigation principale">
      <?php foreach ($nav as $key => $label): ?>
      <a href="<?= $key ?>.php"<?= $active === $key ? ' aria-current="page"' : '' ?>><?= e($label) ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="header-actions">
      <button class="theme-toggle" data-theme-toggle aria-label="Changer de thème clair/sombre">
        <svg class="moon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>
        <svg class="sun" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4 12H2M22 12h-2M5 5l1.5 1.5M17.5 17.5 19 19M19 5l-1.5 1.5M6.5 17.5 5 19"/></svg>
      </button>
      <a class="btn btn-brass" href="admissions.php">Candidater <span class="arw">→</span></a>
      <button class="burger" data-burger aria-label="Ouvrir le menu"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>
<div class="mobile-nav" aria-label="Menu mobile">
  <?php foreach ($nav as $key => $label): ?>
  <a href="<?= $key ?>.php"><?= e($label) ?></a>
  <?php endforeach; ?>
  <a class="btn btn-brass" href="admissions.php">Candidater →</a>
</div>

<main id="main">

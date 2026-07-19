<?php
require_once __DIR__ . '/bootstrap.php';
require_login();
require_once __DIR__ . '/layout.php';

$cols = collections();
$counts = [];
foreach ($cols as $type => $def) {
    $counts[$type] = count(load_json($type, []));
}

admin_header('index', 'Tableau de bord');
?>
<div class="page-head">
  <div>
    <div class="eyebrow">Bienvenue</div>
    <h1>Tableau de bord</h1>
  </div>
  <a class="btn ghost" href="../index.php" target="_blank" rel="noopener">↗ Voir le site</a>
</div>

<div class="grid c3">
  <?php foreach ($cols as $type => $def): ?>
  <a class="card" href="collection.php?type=<?= e($type) ?>">
    <div class="k"><?= (int)$counts[$type] ?></div>
    <h3><?= e($def['label']) ?></h3>
    <p>Gérer les <?= e(mb_strtolower($def['label'])) ?> affichés sur le site.</p>
  </a>
  <?php endforeach; ?>
  <a class="card" href="settings.php">
    <div class="k">⚙</div>
    <h3>Réglages du site</h3>
    <p>Accroche, chiffres-clés, parrain et coordonnées.</p>
  </a>
</div>

<p style="color:var(--muted);font-size:.85rem;margin-top:2rem">
  Astuce : chaque modification est enregistrée dans un fichier <code>content/*.json</code> et apparaît immédiatement sur le site public.
</p>

<?php admin_footer(); ?>

<?php
require_once __DIR__ . '/bootstrap.php';
require_login();
require_once __DIR__ . '/layout.php';

$cols = collections();
$type = $_GET['type'] ?? '';
if (!isset($cols[$type])) { http_response_code(404); exit('Collection inconnue.'); }
$def   = $cols[$type];
$items = load_json($type, []);

// --- Actions (suppression / réordonnancement) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $i = (int)($_POST['i'] ?? -1);
    if (isset($items[$i])) {
        if ($action === 'delete') {
            array_splice($items, $i, 1);
            save_json($type, $items);
            flash('Élément supprimé.');
        } elseif ($action === 'up' && $i > 0) {
            [$items[$i-1], $items[$i]] = [$items[$i], $items[$i-1]];
            save_json($type, $items);
        } elseif ($action === 'down' && $i < count($items) - 1) {
            [$items[$i+1], $items[$i]] = [$items[$i], $items[$i+1]];
            save_json($type, $items);
        }
    }
    header('Location: collection.php?type=' . urlencode($type));
    exit;
}

$titleField = $def['title'];
admin_header($type, $def['label']);
?>
<div class="page-head">
  <div>
    <div class="eyebrow"><?= (int)count($items) ?> élément(s)</div>
    <h1><?= e($def['label']) ?></h1>
  </div>
  <?php if ($type === 'promotions'): ?>
  <a class="btn brass" href="promo-builder.php">+ Nouvelle promotion (assistant)</a>
  <?php else: ?>
  <a class="btn brass" href="edit.php?type=<?= e($type) ?>">+ Ajouter un <?= e($def['singular']) ?></a>
  <?php endif; ?>
</div>

<?php if (empty($items)): ?>
  <div class="card"><p>Aucun élément pour le moment. Cliquez sur « Ajouter » pour commencer.</p></div>
<?php else: ?>
<div class="list">
  <?php foreach ($items as $i => $it): ?>
  <div class="row">
    <div class="grow">
      <div class="t"><?= e($it[$titleField] ?? '(sans titre)') ?><?php if ($type === 'actualites' && ($it['published'] ?? '') === 'Brouillon'): ?> <span class="badge-draft">Brouillon</span><?php endif; ?></div>
      <?php if ($type === 'actualites'): ?>
        <div class="m"><?= e(fr_date($it['date'] ?? '')) ?> · <?= e($it['category'] ?? '') ?></div>
      <?php elseif (!empty($it['tags'])): ?>
        <div class="m"><?= e($it['tags']) ?></div>
      <?php elseif (!empty($it['levels'])): ?>
        <div class="m"><?= e($it['levels']) ?></div>
      <?php elseif (!empty($it['role'])): ?>
        <div class="m"><?= e($it['role']) ?></div>
      <?php endif; ?>
    </div>
    <div class="actions">
      <form method="post" style="display:inline"><?= csrf_field() ?><input type="hidden" name="i" value="<?= $i ?>"><button class="btn ghost sm" name="action" value="up" title="Monter" <?= $i === 0 ? 'disabled' : '' ?>>↑</button></form>
      <form method="post" style="display:inline"><?= csrf_field() ?><input type="hidden" name="i" value="<?= $i ?>"><button class="btn ghost sm" name="action" value="down" title="Descendre" <?= $i === count($items)-1 ? 'disabled' : '' ?>>↓</button></form>
      <?php if ($type === 'promotions'): ?>
      <a class="btn ghost sm" href="promo-builder.php?slug=<?= e(rawurlencode(slugify((string)($it['years'] ?? '')))) ?>">Assistant</a>
      <?php endif; ?>
      <a class="btn ghost sm" href="edit.php?type=<?= e($type) ?>&i=<?= $i ?>">Modifier</a>
      <form method="post" style="display:inline" onsubmit="return confirm('Supprimer cet élément ?');"><?= csrf_field() ?><input type="hidden" name="i" value="<?= $i ?>"><button class="btn del sm" name="action" value="delete">Supprimer</button></form>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php admin_footer(); ?>

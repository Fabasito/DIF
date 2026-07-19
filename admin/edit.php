<?php
require_once __DIR__ . '/bootstrap.php';
require_login();
require_once __DIR__ . '/layout.php';

$cols = collections();
$type = $_GET['type'] ?? '';
if (!isset($cols[$type])) { http_response_code(404); exit('Collection inconnue.'); }
$def    = $cols[$type];
$fields = $def['fields'];
$items  = load_json($type, []);

$i      = isset($_GET['i']) ? (int)$_GET['i'] : null;
$isEdit = $i !== null && isset($items[$i]);
$item   = $isEdit ? $items[$i] : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $new = $isEdit ? $items[$i] : [];
    foreach ($fields as $name => $meta) {
        $val = trim((string)($_POST[$name] ?? ''));
        // Les valeurs sont stockées en texte brut ; l'échappement se fait à l'affichage.
        $new[$name] = $val;
    }
    if ($isEdit) {
        $items[$i] = $new;
    } else {
        // Nouveaux articles : ajoutés en tête ; autres collections : en fin de liste.
        if ($type === 'actualites') array_unshift($items, $new);
        else $items[] = $new;
    }
    if (save_json($type, $items)) {
        flash($isEdit ? 'Modifications enregistrées.' : 'Élément ajouté.');
    } else {
        flash('Erreur lors de l\'enregistrement (droits d\'écriture ?).', 'err');
    }
    header('Location: collection.php?type=' . urlencode($type));
    exit;
}

admin_header($type, ($isEdit ? 'Modifier' : 'Ajouter') . ' — ' . $def['label']);
?>
<div class="page-head">
  <div>
    <div class="eyebrow"><a href="collection.php?type=<?= e($type) ?>">← <?= e($def['label']) ?></a></div>
    <h1><?= $isEdit ? 'Modifier' : 'Ajouter' ?> un <?= e($def['singular']) ?></h1>
  </div>
</div>

<form class="stack" method="post" action="edit.php?type=<?= e($type) ?><?= $isEdit ? '&i='.$i : '' ?>">
  <?= csrf_field() ?>
  <?php foreach ($fields as $name => $meta):
        [$label, $ftype] = [$meta[0], $meta[1]];
        $val = $item[$name] ?? ($ftype === 'date' ? date('Y-m-d') : ''); ?>
  <div class="field">
    <label for="f-<?= e($name) ?>"><?= e($label) ?></label>
    <?php if ($ftype === 'textarea'): ?>
      <textarea id="f-<?= e($name) ?>" name="<?= e($name) ?>"><?= e($val) ?></textarea>
    <?php elseif ($ftype === 'select'):
        $opts = $meta[2] ?? []; ?>
      <select id="f-<?= e($name) ?>" name="<?= e($name) ?>">
        <?php foreach ($opts as $opt): ?>
        <option<?= $opt === $val ? ' selected' : '' ?>><?= e($opt) ?></option>
        <?php endforeach; ?>
      </select>
    <?php elseif ($ftype === 'date'): ?>
      <input id="f-<?= e($name) ?>" name="<?= e($name) ?>" type="date" value="<?= e($val) ?>">
    <?php else: ?>
      <input id="f-<?= e($name) ?>" name="<?= e($name) ?>" type="text" value="<?= e($val) ?>">
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
  <div class="form-actions">
    <button class="btn brass" type="submit"><?= $isEdit ? 'Enregistrer' : 'Ajouter' ?></button>
    <a class="btn ghost" href="collection.php?type=<?= e($type) ?>">Annuler</a>
  </div>
</form>

<?php admin_footer(); ?>

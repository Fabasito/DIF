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

$hasImage = false;
foreach ($fields as $meta) { if ($meta[1] === 'image') { $hasImage = true; break; } }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $new = $isEdit ? $items[$i] : [];
    $imgWarning = '';

    foreach ($fields as $name => $meta) {
        $ftype = $meta[1];
        if ($ftype === 'image') {
            if (!empty($_POST['remove_' . $name])) {
                $new[$name] = '';
            } else {
                $up = handle_upload($name);
                if (!empty($up['ok'])) {
                    $new[$name] = $up['path'];
                } elseif (empty($up['empty'])) {
                    $imgWarning = $up['error'] ?? 'Image non enregistrée.';
                }
            }
        } else {
            $new[$name] = trim((string)($_POST[$name] ?? ''));
        }
    }

    // Slug stable pour les articles : généré à la création, conservé ensuite.
    if (isset($def['title'])) {
        if (empty($new['slug'])) {
            $base = slugify((string)($new[$def['title']] ?? ''));
            $slug = $base; $n = 2;
            $exists = function ($s) use ($items, $i) {
                foreach ($items as $k => $it) {
                    if ($k === $i) continue;
                    if (($it['slug'] ?? '') === $s) return true;
                }
                return false;
            };
            while ($exists($slug)) { $slug = $base . '-' . $n++; }
            $new['slug'] = $slug;
        }
    }

    if ($isEdit) {
        $items[$i] = $new;
    } else {
        if ($type === 'actualites') array_unshift($items, $new);
        else $items[] = $new;
    }

    if (save_json($type, $items)) {
        flash($isEdit ? 'Modifications enregistrées.' : 'Élément ajouté.');
        if ($imgWarning) flash($imgWarning, 'err');
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

<form class="stack" method="post" action="edit.php?type=<?= e($type) ?><?= $isEdit ? '&i='.$i : '' ?>"<?= $hasImage ? ' enctype="multipart/form-data"' : '' ?>>
  <?= csrf_field() ?>
  <?php foreach ($fields as $name => $meta):
        [$label, $ftype] = [$meta[0], $meta[1]];
        $val = $item[$name] ?? ($ftype === 'date' ? date('Y-m-d') : ''); ?>
  <div class="field">
    <label for="f-<?= e($name) ?>"><?= e($label) ?></label>
    <?php if ($ftype === 'image'): ?>
      <?php if (!empty($val)): ?>
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:.4rem">
          <img src="../<?= e($val) ?>" alt="" style="height:64px;width:auto;border:1px solid var(--line);border-radius:6px;background:#fff;padding:4px">
          <label style="display:flex;align-items:center;gap:.4rem;font-family:inherit;text-transform:none;letter-spacing:0;color:var(--danger)">
            <input type="checkbox" name="remove_<?= e($name) ?>" value="1" style="width:auto"> Supprimer l'image
          </label>
        </div>
      <?php endif; ?>
      <input id="f-<?= e($name) ?>" name="<?= e($name) ?>" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
      <span class="hint">JPG, PNG, WEBP ou GIF — 4 Mo maximum.</span>
    <?php elseif ($ftype === 'textarea'): ?>
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

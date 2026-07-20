<?php
require_once __DIR__ . '/bootstrap.php';
require_login();
require_once __DIR__ . '/layout.php';

$promos = load_json('promotions', []);

/* ---- Mode édition : préremplissage depuis une promotion existante ---- */
$editSlug = (string)($_GET['slug'] ?? '');
$editing = null;
foreach ($promos as $p) {
    if ($editSlug !== '' && slugify((string)($p['years'] ?? '')) === $editSlug) { $editing = $p; break; }
}

/* ---- Enregistrement ---- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $years = trim((string)($_POST['years'] ?? ''));
    $num   = (int)($_POST['num'] ?? 0);
    if ($years === '') { flash('L\'année est obligatoire.', 'err'); header('Location: promo-builder.php'); exit; }

    $entry = [
        'years'    => $years,
        'levels'   => 'Master 1 & Master 2',
        'num'      => $num,
        'photo'    => trim((string)($_POST['photo_group'] ?? '')),
        'photo_m2' => trim((string)($_POST['photo_m2'] ?? '')),
        'photo_m1' => trim((string)($_POST['photo_m1'] ?? '')),
    ];

    // Insertion / remplacement dans promotions.json
    $promos = load_json('promotions', []);
    $pos = null;
    foreach ($promos as $k => $p) { if (($p['years'] ?? '') === $years) { $pos = $k; break; } }
    if ($pos !== null) { $promos[$pos] = $entry; }        // édition : garde sa place
    else { array_unshift($promos, $entry); }              // nouveauté : devient la promotion actuelle
    save_json('promotions', $promos);

    // Reconstruction des membres de cette promotion
    $membres = array_values(array_filter(load_json('membres', []), fn($m) => ($m['promotion'] ?? '') !== $years));
    foreach ([['Master 2', 'm2'], ['Master 1', 'm1']] as [$levelName, $pfx]) {
        $prenoms = $_POST[$pfx . '_prenom'] ?? [];
        $noms    = $_POST[$pfx . '_nom'] ?? [];
        $mails   = $_POST[$pfx . '_email'] ?? [];
        $lis     = $_POST[$pfx . '_linkedin'] ?? [];
        $photos  = $_POST[$pfx . '_photo'] ?? [];
        $n = max(count($prenoms), count($noms));
        for ($i = 0; $i < $n; $i++) {
            $prenom = trim((string)($prenoms[$i] ?? ''));
            $nom    = trim((string)($noms[$i] ?? ''));
            if ($prenom === '' && $nom === '') continue;
            $membres[] = [
                'name'      => trim($prenom . ' ' . $nom),
                'promotion' => $years,
                'level'     => $levelName,
                'photo'     => trim((string)($photos[$i] ?? '')),
                'email'     => trim((string)($mails[$i] ?? '')),
                'linkedin'  => trim((string)($lis[$i] ?? '')),
            ];
        }
    }
    save_json('membres', $membres);

    // Mise à jour du numéro de promotion sur l'accueil (uniquement si c'est la promotion actuelle)
    if (($promos[0]['years'] ?? '') === $years && $num > 0) {
        $site = load_json('site', []);
        if (!isset($site['stats']) || !is_array($site['stats'])) $site['stats'] = [];
        preg_match_all('/\d{4}/', $years, $yy);
        $endYear = $yy[0] ? end($yy[0]) : date('Y');
        // Cible le stat ordinal (valeur « 25e »), sinon le stat dont le libellé commence par « promotion ».
        $target = null;
        foreach ($site['stats'] as $k => $s) {
            if (preg_match('/^\s*\d+\s*e/iu', (string)($s['value'] ?? ''))) { $target = $k; break; }
        }
        if ($target === null) {
            foreach ($site['stats'] as $k => $s) {
                if (preg_match('/^\s*promotion/iu', (string)($s['label'] ?? ''))) { $target = $k; break; }
            }
        }
        if ($target !== null) {
            $site['stats'][$target] = ['value' => $num . 'e', 'label' => 'promotion en ' . $endYear];
            save_json('site', $site);
        }
    }

    flash($pos !== null ? 'Promotion mise à jour.' : 'Promotion créée et publiée comme promotion actuelle.');
    header('Location: collection.php?type=promotions');
    exit;
}

/* ---- Valeurs par défaut (création) ---- */
$curNum = (int)($promos[0]['num'] ?? 25);
$suggestNum = $editing ? (int)($editing['num'] ?? $curNum) : $curNum + 1;

$suggestYears = '';
if ($editing) {
    $suggestYears = (string)$editing['years'];
} elseif (!empty($promos)) {
    preg_match_all('/\d{4}/', (string)$promos[0]['years'], $yy);
    if ($yy[0]) { $last = (int)end($yy[0]); $suggestYears = $last . ' — ' . ($last + 1); }
}

/* Membres existants (édition) pour préremplir le JS */
$prefill = ['m2' => [], 'm1' => []];
if ($editing) {
    foreach (load_json('membres', []) as $m) {
        if (($m['promotion'] ?? '') !== $editing['years']) continue;
        $parts = explode(' ', trim((string)($m['name'] ?? '')), 2);
        $row = [
            'prenom'   => $parts[0] ?? '',
            'nom'      => $parts[1] ?? '',
            'email'    => $m['email'] ?? '',
            'linkedin' => $m['linkedin'] ?? '',
            'photo'    => $m['photo'] ?? '',
        ];
        if (($m['level'] ?? '') === 'Master 1') $prefill['m1'][] = $row;
        else $prefill['m2'][] = $row;
    }
}

admin_header('', $editing ? 'Modifier une promotion' : 'Nouvelle promotion');
?>
<style>
  .pb-section { background:var(--surface); border:1px solid var(--line); border-radius:var(--radius); padding:1.4rem 1.5rem; margin-bottom:1.4rem; }
  .pb-section > h2 { font-size:1.25rem; display:flex; align-items:center; gap:.6rem; }
  .pb-count { font-family:var(--mono); font-size:.72rem; background:var(--brass); color:#1a1406; border-radius:999px; padding:.1rem .55rem; }
  .pb-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:1.1rem; }
  @media(max-width:700px){ .pb-grid2{grid-template-columns:1fr;} }
  .pb-drop { display:flex; align-items:center; gap:1rem; }
  .pb-thumb { width:74px; height:74px; flex:0 0 auto; border:1px dashed var(--line); border-radius:8px; background:var(--surface-2) center/cover no-repeat; display:grid; place-items:center; color:var(--muted); font-size:.65rem; text-align:center; overflow:hidden; }
  .pb-thumb.big{ width:120px; height:80px; }
  .pb-up-status { font-size:.78rem; color:var(--muted); }
  .pb-up-status.ok { color:var(--ok); } .pb-up-status.err{ color:var(--err); }
  .std-list { display:flex; flex-direction:column; gap:.7rem; margin-top:1rem; }
  .std-row { display:grid; grid-template-columns:74px 1fr 1fr 1.3fr 1.3fr auto; gap:.6rem; align-items:center; padding:.7rem; border:1px solid var(--line); border-radius:8px; background:var(--paper); }
  @media(max-width:860px){ .std-row{ grid-template-columns:74px 1fr 1fr; } }
  .std-row input[type=text], .std-row input[type=email], .std-row input[type=url]{ padding:.5rem .6rem; }
  .std-row .std-file{ font-size:.72rem; width:100%; }
  .std-photo{ display:flex; flex-direction:column; gap:.25rem; align-items:center; }
  .std-remove{ width:34px; height:34px; border-radius:8px; border:1px solid #e7c9c5; background:#fff; color:var(--danger); font-size:1.1rem; cursor:pointer; }
  .std-remove:hover{ background:var(--errbg); }
  .pb-addbtn{ margin-top:1rem; }
  .pb-hint{ font-size:.8rem; color:var(--muted); }
  .pb-bar{ position:sticky; bottom:0; background:color-mix(in srgb,var(--paper) 92%,transparent); backdrop-filter:blur(6px); padding:1rem 0; margin-top:1.5rem; border-top:1px solid var(--line); display:flex; gap:.7rem; align-items:center; }
</style>

<div class="page-head">
  <div>
    <div class="eyebrow"><a href="collection.php?type=promotions">← Promotions</a></div>
    <h1><?= $editing ? 'Modifier la promotion' : 'Assistant — nouvelle promotion' ?></h1>
  </div>
</div>

<form method="post" id="pb-form" action="promo-builder.php<?= $editing ? '?slug=' . e($editSlug) : '' ?>">
  <?= csrf_field() ?>

  <!-- 1. Généralités -->
  <div class="pb-section">
    <h2>La promotion</h2>
    <div class="pb-grid2" style="margin-top:1rem">
      <div class="field">
        <label for="years">Année(s)</label>
        <input id="years" name="years" type="text" value="<?= e($suggestYears) ?>" placeholder="2026 — 2027" required>
        <span class="pb-hint">Format libre, ex. « 2026 — 2027 ».</span>
      </div>
      <div class="field">
        <label for="num">Numéro de la promotion</label>
        <input id="num" name="num" type="number" min="1" value="<?= (int)$suggestNum ?>">
        <span class="pb-hint">Affiché sur l'accueil (« <?= (int)$suggestNum ?>e promotion »). Pré-rempli à partir de l'actuelle (<?= (int)$curNum ?>e).</span>
      </div>
    </div>
    <div class="field" style="margin-top:1rem">
      <label>Photo de groupe — promotion entière (M1 + M2)</label>
      <div class="pb-drop">
        <span class="pb-thumb big" data-thumb></span>
        <div>
          <input type="file" accept="image/jpeg,image/png,image/webp,image/gif" data-upload>
          <input type="hidden" name="photo_group" value="<?= e($editing['photo'] ?? '') ?>">
          <div class="pb-up-status"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Master 2 -->
  <div class="pb-section">
    <h2>Master 2 <span class="pb-count" data-count="m2">0</span></h2>
    <div class="field" style="margin-top:1rem">
      <label>Photo de groupe — Master 2</label>
      <div class="pb-drop">
        <span class="pb-thumb big" data-thumb></span>
        <div>
          <input type="file" accept="image/jpeg,image/png,image/webp,image/gif" data-upload>
          <input type="hidden" name="photo_m2" value="<?= e($editing['photo_m2'] ?? '') ?>">
          <div class="pb-up-status"></div>
        </div>
      </div>
    </div>
    <div class="std-list" data-list="m2"></div>
    <button type="button" class="btn ghost pb-addbtn" data-add="m2">+ Ajouter un étudiant en M2</button>
  </div>

  <!-- 3. Master 1 -->
  <div class="pb-section">
    <h2>Master 1 <span class="pb-count" data-count="m1">0</span></h2>
    <div class="field" style="margin-top:1rem">
      <label>Photo de groupe — Master 1</label>
      <div class="pb-drop">
        <span class="pb-thumb big" data-thumb></span>
        <div>
          <input type="file" accept="image/jpeg,image/png,image/webp,image/gif" data-upload>
          <input type="hidden" name="photo_m1" value="<?= e($editing['photo_m1'] ?? '') ?>">
          <div class="pb-up-status"></div>
        </div>
      </div>
    </div>
    <div class="std-list" data-list="m1"></div>
    <button type="button" class="btn ghost pb-addbtn" data-add="m1">+ Ajouter un étudiant en M1</button>
  </div>

  <div class="pb-bar">
    <button class="btn brass" type="submit"><?= $editing ? 'Enregistrer les modifications' : 'Créer la promotion' ?></button>
    <a class="btn ghost" href="collection.php?type=promotions">Annuler</a>
    <span class="pb-hint" id="pb-uploading" style="display:none">⏳ Envoi d'images en cours…</span>
  </div>
</form>

<!-- Gabarit d'une ligne étudiant -->
<template id="std-tpl">
  <div class="std-row">
    <div class="std-photo">
      <span class="pb-thumb" data-thumb></span>
      <input type="file" class="std-file" accept="image/jpeg,image/png,image/webp,image/gif" data-upload>
      <input type="hidden" data-name="photo" value="">
    </div>
    <input type="text" data-name="prenom" placeholder="Prénom" autocomplete="off">
    <input type="text" data-name="nom" placeholder="NOM" autocomplete="off">
    <input type="email" data-name="email" placeholder="E-mail" autocomplete="off">
    <input type="url" data-name="linkedin" placeholder="LinkedIn (URL)" autocomplete="off">
    <button type="button" class="std-remove" title="Retirer">×</button>
  </div>
</template>

<script>
(function () {
  var CSRF = <?= json_encode(csrf_token()) ?>;
  var PREFILL = <?= json_encode($prefill, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
  var uploads = 0;

  function setUploading(delta) {
    uploads += delta;
    document.getElementById('pb-uploading').style.display = uploads > 0 ? '' : 'none';
  }

  function wireUpload(fileInput, hidden, thumb, status) {
    fileInput.addEventListener('change', function () {
      var f = fileInput.files[0];
      if (!f) return;
      status.className = 'pb-up-status'; status.textContent = 'Envoi…';
      setUploading(1);
      var fd = new FormData();
      fd.append('csrf', CSRF);
      fd.append('file', f);
      fetch('upload.php', { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (j) {
          setUploading(-1);
          if (j.ok) {
            hidden.value = j.path;
            if (thumb) thumb.style.backgroundImage = "url('../" + j.path + "')";
            if (thumb) thumb.textContent = '';
            status.className = 'pb-up-status ok'; status.textContent = '✓ Image enregistrée';
          } else {
            status.className = 'pb-up-status err'; status.textContent = j.error || 'Échec.';
          }
        })
        .catch(function () { setUploading(-1); status.className = 'pb-up-status err'; status.textContent = 'Erreur réseau.'; });
    });
  }

  // Uploads des photos de groupe
  document.querySelectorAll('.pb-drop').forEach(function (drop) {
    var fileInput = drop.querySelector('[data-upload]');
    var hidden = drop.querySelector('input[type=hidden]');
    var thumb = drop.querySelector('[data-thumb]');
    var status = drop.querySelector('.pb-up-status');
    if (hidden.value && thumb) { thumb.style.backgroundImage = "url('../" + hidden.value + "')"; thumb.textContent = ''; }
    wireUpload(fileInput, hidden, thumb, status);
  });

  function recount(level) {
    var list = document.querySelector('[data-list="' + level + '"]');
    document.querySelector('[data-count="' + level + '"]').textContent = list.children.length;
  }

  function addStudent(level, data) {
    data = data || {};
    var tpl = document.getElementById('std-tpl').content.cloneNode(true);
    var row = tpl.querySelector('.std-row');
    var setName = function (key, suffix) {
      var el = row.querySelector('[data-name="' + key + '"]');
      el.name = level + '_' + key + (suffix || '') ;
      return el;
    };
    var pren = setName('prenom', '[]'); pren.value = data.prenom || '';
    var nom = setName('nom', '[]'); nom.value = data.nom || '';
    var email = setName('email', '[]'); email.value = data.email || '';
    var li = setName('linkedin', '[]'); li.value = data.linkedin || '';
    var hidden = row.querySelector('[data-name="photo"]'); hidden.name = level + '_photo[]'; hidden.value = data.photo || '';
    var thumb = row.querySelector('[data-thumb]');
    var file = row.querySelector('[data-upload]');
    var status = document.createElement('span'); status.className = 'pb-up-status';
    row.querySelector('.std-photo').appendChild(status);
    if (data.photo) { thumb.style.backgroundImage = "url('../" + data.photo + "')"; thumb.textContent = ''; }
    else { thumb.textContent = 'photo'; }
    wireUpload(file, hidden, thumb, status);
    row.querySelector('.std-remove').addEventListener('click', function () {
      row.remove(); recount(level);
    });
    document.querySelector('[data-list="' + level + '"]').appendChild(row);
    recount(level);
  }

  document.querySelectorAll('[data-add]').forEach(function (btn) {
    btn.addEventListener('click', function () { addStudent(btn.getAttribute('data-add')); });
  });

  // Préremplissage (édition)
  ['m2', 'm1'].forEach(function (lvl) {
    (PREFILL[lvl] || []).forEach(function (d) { addStudent(lvl, d); });
  });

  // Garde-fou : ne pas soumettre pendant un upload
  document.getElementById('pb-form').addEventListener('submit', function (e) {
    if (uploads > 0) {
      e.preventDefault();
      alert('Veuillez patienter : des images sont encore en cours d\'envoi.');
    }
  });
})();
</script>

<?php admin_footer(); ?>

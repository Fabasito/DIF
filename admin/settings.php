<?php
require_once __DIR__ . '/bootstrap.php';
require_login();
require_once __DIR__ . '/layout.php';

$site = load_json('site', []);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $t = fn($k) => trim((string)($_POST[$k] ?? ''));

    $stats = [];
    for ($n = 0; $n < 4; $n++) {
        $v = trim((string)($_POST["stat_value_$n"] ?? ''));
        $l = trim((string)($_POST["stat_label_$n"] ?? ''));
        if ($v !== '' || $l !== '') $stats[] = ['value' => $v, 'label' => $l];
    }

    $site = [
        'hero' => [
            'kicker'   => $t('hero_kicker'),
            'title'    => $t('hero_title'),
            'accent'   => $t('hero_accent'),
            'subtitle' => $t('hero_subtitle'),
        ],
        'stats' => $stats,
        'parrain' => [
            'quote' => $t('parrain_quote'),
            'name'  => $t('parrain_name'),
            'role'  => $t('parrain_role'),
        ],
        'contact' => [
            'email'             => $t('contact_email'),
            'faculty_url'       => $t('contact_faculty_url'),
            'responsable_nom'   => $t('contact_responsable_nom'),
            'responsable_email' => $t('contact_responsable_email'),
        ],
    ];
    if (save_json('site', $site)) flash('Réglages enregistrés.');
    else flash('Erreur lors de l\'enregistrement (droits d\'écriture ?).', 'err');
    header('Location: settings.php');
    exit;
}

$hero = $site['hero'] ?? [];
$stats = $site['stats'] ?? [];
$parr = $site['parrain'] ?? [];
$ct   = $site['contact'] ?? [];
$g = fn($arr, $k) => e($arr[$k] ?? '');

admin_header('settings', 'Réglages du site');
?>
<div class="page-head"><div><div class="eyebrow">Contenu global</div><h1>Réglages du site</h1></div></div>

<form class="stack" method="post" action="settings.php">
  <?= csrf_field() ?>

  <h3>Accroche (page d'accueil)</h3>
  <div class="field"><label for="hk">Sur-titre</label><input id="hk" name="hero_kicker" type="text" value="<?= $g($hero,'kicker') ?>"></div>
  <div class="grid c2" style="gap:1.1rem">
    <div class="field"><label for="ht">Titre</label><input id="ht" name="hero_title" type="text" value="<?= $g($hero,'title') ?>"></div>
    <div class="field"><label for="ha">Mot mis en avant (or)</label><input id="ha" name="hero_accent" type="text" value="<?= $g($hero,'accent') ?>"></div>
  </div>
  <div class="field"><label for="hs">Sous-titre</label><textarea id="hs" name="hero_subtitle"><?= $g($hero,'subtitle') ?></textarea></div>

  <h3 style="margin-top:1rem">Chiffres-clés (bandeau)</h3>
  <div class="grid c2" style="gap:1.1rem">
    <?php for ($n = 0; $n < 4; $n++): $s = $stats[$n] ?? ['value'=>'','label'=>'']; ?>
    <div class="field">
      <label>Chiffre <?= $n+1 ?></label>
      <input name="stat_value_<?= $n ?>" type="text" placeholder="ex : 400+" value="<?= e($s['value'] ?? '') ?>" style="margin-bottom:.4rem">
      <input name="stat_label_<?= $n ?>" type="text" placeholder="légende" value="<?= e($s['label'] ?? '') ?>">
    </div>
    <?php endfor; ?>
  </div>

  <h3 style="margin-top:1rem">Parrain (page « Le Master »)</h3>
  <div class="field"><label for="pq">Citation</label><textarea id="pq" name="parrain_quote"><?= $g($parr,'quote') ?></textarea></div>
  <div class="grid c2" style="gap:1.1rem">
    <div class="field"><label for="pn">Nom</label><input id="pn" name="parrain_name" type="text" value="<?= $g($parr,'name') ?>"></div>
    <div class="field"><label for="pr">Fonction / promotion</label><input id="pr" name="parrain_role" type="text" value="<?= $g($parr,'role') ?>"></div>
  </div>

  <h3 style="margin-top:1rem">Coordonnées</h3>
  <div class="grid c2" style="gap:1.1rem">
    <div class="field"><label for="ce">E-mail de l'association</label><input id="ce" name="contact_email" type="email" value="<?= $g($ct,'email') ?>"></div>
    <div class="field"><label for="cf">Lien Faculté / formation</label><input id="cf" name="contact_faculty_url" type="text" value="<?= $g($ct,'faculty_url') ?>"></div>
    <div class="field"><label for="crn">Responsable pédagogique</label><input id="crn" name="contact_responsable_nom" type="text" value="<?= $g($ct,'responsable_nom') ?>"></div>
    <div class="field"><label for="cre">E-mail du responsable</label><input id="cre" name="contact_responsable_email" type="email" value="<?= $g($ct,'responsable_email') ?>"></div>
  </div>

  <div class="form-actions"><button class="btn brass" type="submit">Enregistrer</button><a class="btn ghost" href="index.php">Retour</a></div>
</form>

<?php admin_footer(); ?>

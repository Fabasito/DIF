<?php
require_once __DIR__ . '/bootstrap.php';
require_login();
require_once __DIR__ . '/layout.php';

$messages = load_messages();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $id = (string)($_POST['id'] ?? '');

    if ($action === 'read_all') {
        foreach ($messages as &$m) { $m['read'] = true; }
        unset($m);
        save_messages($messages);
        flash('Tous les messages ont été marqués comme lus.');
    } else {
        foreach ($messages as $k => $m) {
            if (($m['id'] ?? '') !== $id) continue;
            if ($action === 'toggle')      { $messages[$k]['read'] = empty($m['read']); }
            elseif ($action === 'delete')  { array_splice($messages, $k, 1); flash('Message supprimé.'); }
            break;
        }
        if ($action === 'toggle') save_messages($messages);
        elseif ($action === 'delete') save_messages($messages);
    }
    header('Location: messages.php');
    exit;
}

$unread = 0; foreach ($messages as $m) { if (empty($m['read'])) $unread++; }

admin_header('messages', 'Messages');
?>
<div class="page-head">
  <div>
    <div class="eyebrow"><?= (int)count($messages) ?> message(s)<?= $unread ? ' · '.$unread.' non lu(s)' : '' ?></div>
    <h1>Messages reçus</h1>
  </div>
  <?php if ($unread): ?>
  <form method="post"><?= csrf_field() ?><button class="btn ghost" name="action" value="read_all">Tout marquer comme lu</button></form>
  <?php endif; ?>
</div>

<?php if (empty($messages)): ?>
  <div class="card"><p>Aucun message pour l'instant. Les demandes envoyées depuis le formulaire de contact apparaîtront ici.</p></div>
<?php else: ?>
<div class="msg-list">
  <?php foreach ($messages as $m):
    $ts = strtotime($m['t'] ?? '');
    $when = $ts ? fr_date(date('Y-m-d', $ts)) . ' à ' . date('H:i', $ts) : ''; ?>
  <article class="msg<?= empty($m['read']) ? ' unread' : '' ?>">
    <div class="msg-head">
      <div>
        <span class="msg-from"><?= e($m['nom'] ?? '') ?></span>
        <a class="msg-mail" href="mailto:<?= e($m['email'] ?? '') ?>"><?= e($m['email'] ?? '') ?></a>
      </div>
      <span class="msg-date"><?= e($when) ?></span>
    </div>
    <?php if (!empty($m['sujet'])): ?><div class="msg-subject"><?= e($m['sujet']) ?></div><?php endif; ?>
    <p class="msg-body"><?= nl2br(e($m['message'] ?? '')) ?></p>
    <div class="msg-actions">
      <a class="btn brass sm" href="mailto:<?= e($m['email'] ?? '') ?>?subject=<?= e(rawurlencode('Re : ' . ($m['sujet'] ?? 'votre message'))) ?>">Répondre</a>
      <form method="post" style="display:inline"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e($m['id'] ?? '') ?>"><button class="btn ghost sm" name="action" value="toggle"><?= empty($m['read']) ? 'Marquer lu' : 'Marquer non lu' ?></button></form>
      <form method="post" style="display:inline" onsubmit="return confirm('Supprimer ce message ?');"><?= csrf_field() ?><input type="hidden" name="id" value="<?= e($m['id'] ?? '') ?>"><button class="btn del sm" name="action" value="delete">Supprimer</button></form>
    </div>
  </article>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<?php admin_footer(); ?>

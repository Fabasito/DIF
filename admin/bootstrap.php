<?php
/** Socle commun du back-office : session, auth, CSRF, flash. */
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once dirname(__DIR__) . '/inc/data.php';

// --- Session durcie ---
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || (($_SERVER['SERVER_PORT'] ?? '') === '443');
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'httponly' => true,
    'secure'   => $https,
    'samesite' => 'Lax',
]);
session_name('dif_admin');
session_start();

// --- CSRF ---
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}
function csrf_check(): void {
    $ok = isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], (string)$_POST['csrf']);
    if (!$ok) {
        http_response_code(400);
        exit('Jeton de sécurité invalide. Rechargez la page et réessayez.');
    }
}

// --- Mot de passe effectif : surcharge locale (modifiable depuis l'admin) sinon config ---
define('DIF_AUTH_OVERRIDE', (DIF_WRITABLE !== DIF_ROOT ? DIF_WRITABLE : __DIR__) . '/auth.local.php');
function admin_password_hash(): string {
    if (is_file(DIF_AUTH_OVERRIDE)) {
        $h = include DIF_AUTH_OVERRIDE;
        if (is_string($h) && $h !== '') return $h;
    }
    return ADMIN_PASSWORD_HASH;
}
function set_admin_password(string $plain): bool {
    $hash = password_hash($plain, PASSWORD_DEFAULT);
    $code = "<?php\n// Mot de passe défini depuis le back-office. Ne pas partager.\nreturn " . var_export($hash, true) . ";\n";
    $tmp = DIF_AUTH_OVERRIDE . '.tmp' . getmypid();
    if (file_put_contents($tmp, $code, LOCK_EX) === false) return false;
    return rename($tmp, DIF_AUTH_OVERRIDE);
}

// --- Authentification ---
function is_logged_in(): bool {
    if (empty($_SESSION['auth'])) return false;
    if (isset($_SESSION['last']) && (time() - $_SESSION['last']) > ADMIN_IDLE_TIMEOUT) {
        logout();
        return false;
    }
    $_SESSION['last'] = time();
    return true;
}
function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
function login_success(): void {
    session_regenerate_id(true);
    $_SESSION['auth'] = true;
    $_SESSION['last'] = time();
}
function logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

// --- Flash ---
function flash(string $msg, string $type = 'ok'): void {
    $_SESSION['flash'][] = ['msg' => $msg, 'type' => $type];
}
function take_flash(): array {
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

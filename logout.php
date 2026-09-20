<?php
require_once __DIR__ . '/includes/functions.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $users = readUsers();
    foreach ($users as &$u) {
        if ((int) $u['id'] === (int) $_SESSION['user_id']) {
            $u['remember_token'] = null;
        }
    }
    unset($u);
    saveUsers($users);
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain']);
}

session_destroy();

setcookie('remember_token', '', time() - 3600, '/');

header('Location: login.php?msg=logged_out');
exit;

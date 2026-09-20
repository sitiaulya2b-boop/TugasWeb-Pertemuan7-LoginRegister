<?php

require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $parts = explode(':', $_COOKIE['remember_token'], 2);

    if (count($parts) === 2) {
        [$cookieUserId, $cookieToken] = $parts;
        $users = readUsers();
        $user = findUserById($users, (int) $cookieUserId);

        if ($user && !empty($user['remember_token']) && hash_equals($user['remember_token'], $cookieToken)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
        }
    }
}

function requireLogin(): void
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

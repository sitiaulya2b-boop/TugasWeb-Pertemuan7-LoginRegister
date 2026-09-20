<?php
define('USERS_FILE', __DIR__ . '/../data/users.json');

function readUsers(): array
{
    if (!file_exists(USERS_FILE)) {
        return [];
    }

    $json = file_get_contents(USERS_FILE);
    $users = json_decode($json, true);

    return is_array($users) ? $users : [];
}

function saveUsers(array $users): bool
{
    $dir = dirname(USERS_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents(USERS_FILE, $json) !== false;
}

function findUserByEmail(array $users, string $email): ?array
{
    foreach ($users as $user) {
        if (strtolower($user['email']) === strtolower($email)) {
            return $user;
        }
    }
    return null;
}

function findUserById(array $users, int $id): ?array
{
    foreach ($users as $user) {
        if ((int) $user['id'] === $id) {
            return $user;
        }
    }
    return null;
}

function nextUserId(array $users): int
{
    $max = 0;
    foreach ($users as $user) {
        if ((int) $user['id'] > $max) {
            $max = (int) $user['id'];
        }
    }
    return $max + 1;
}

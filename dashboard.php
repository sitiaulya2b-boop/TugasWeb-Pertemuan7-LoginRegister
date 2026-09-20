<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$users = readUsers();
$currentUser = findUserById($users, (int) $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>dashboard.php - Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="term term--wide">
    <div class="term__bar">
      <span class="term__dot term__dot--red"></span>
      <span class="term__dot term__dot--yellow"></span>
      <span class="term__dot term__dot--green"></span>
      <span class="term__path">dashboard.php</span>
    </div>
    <div class="term__body">
      <span class="badge">● session aktif</span>
      <p class="brand"><span class="brand__prompt">&gt;</span><span class="brand__title">Selamat datang, <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>!</span></p>
      <p class="subtitle">Halaman ini hanya bisa diakses kalau kamu sudah login. Coba buka <code>dashboard.php</code> di tab mode incognito tanpa login - kamu akan langsung di-redirect ke halaman login.</p>

      <dl>
        <div class="kv">
          <dt>Nama</dt>
          <dd><?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></dd>
        </div>
        <div class="kv">
          <dt>Email</dt>
          <dd><?= htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8') ?></dd>
        </div>
        <div class="kv">
          <dt>User ID</dt>
          <dd>#<?= (int) $_SESSION['user_id'] ?></dd>
        </div>
        <?php if ($currentUser): ?>
        <div class="kv">
          <dt>Terdaftar sejak</dt>
          <dd><?= htmlspecialchars($currentUser['created_at'], ENT_QUOTES, 'UTF-8') ?></dd>
        </div>
        <?php endif; ?>
      </dl>

      <div class="actions">
        <a href="edit-profile.php" class="btn--ghost">✎ Edit Profil</a>
        <a href="logout.php" class="btn--ghost">⏻ Logout</a>
      </div>
    </div>
  </div>
  <p class="credit">Tugas Rutin 7 - PHP Native Login/Register System</p>
</body>
</html>

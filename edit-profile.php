<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$error = '';
$success = '';

$users = readUsers();
$currentUser = findUserById($users, (int) $_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';

    if ($nama === '') {
        $error = 'Nama tidak boleh kosong!';
    } elseif ($newPassword !== '' && strlen($newPassword) < 6) {
        $error = 'Password baru minimal 6 karakter!';
    } else {
        $namaAman = htmlspecialchars($nama, ENT_QUOTES, 'UTF-8');

        foreach ($users as &$u) {
            if ((int) $u['id'] === (int) $currentUser['id']) {
                $u['nama'] = $namaAman;
                if ($newPassword !== '') {
                    $u['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
            }
        }
        unset($u);
        saveUsers($users);

        $_SESSION['username'] = $namaAman;
        $currentUser['nama'] = $namaAman;

        $success = 'Profil berhasil diperbarui!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>edit-profile.php - Edit Profil</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="term">
    <div class="term__bar">
      <span class="term__dot term__dot--red"></span>
      <span class="term__dot term__dot--yellow"></span>
      <span class="term__dot term__dot--green"></span>
      <span class="term__path">edit-profile.php</span>
    </div>
    <div class="term__body">
      <p class="brand"><span class="brand__prompt">&gt;</span><span class="brand__title">Edit Profil</span></p>
      <p class="subtitle">Ubah nama atau password kamu. Kosongkan password kalau tidak ingin menggantinya.</p>

      <?php if ($success): ?>
        <div class="alert alert--success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="alert alert--error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="field">
          <label for="nama">Nama Lengkap</label>
          <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($currentUser['nama'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="field">
          <label>Email</label>
          <input type="email" value="<?= htmlspecialchars($currentUser['email'], ENT_QUOTES, 'UTF-8') ?>" disabled>
          <p class="field-hint">Email tidak bisa diubah.</p>
        </div>
        <div class="field">
          <label for="new_password">Password Baru (opsional)</label>
          <input type="password" id="new_password" name="new_password" placeholder="Kosongkan jika tidak diubah">
        </div>
        <button type="submit" class="btn">Simpan Perubahan</button>
      </form>

      <p class="foot-link"><a href="dashboard.php">← Kembali ke Dashboard</a></p>
    </div>
  </div>
  <p class="credit">Tugas Rutin 7 - PHP Native Login/Register System</p>
</body>
</html>

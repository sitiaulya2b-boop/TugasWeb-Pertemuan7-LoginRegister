<?php
require_once __DIR__ . '/includes/functions.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$old = ['nama' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $old['nama'] = $nama;
    $old['email'] = $email;

    if ($nama === '' || $email === '' || $password === '' || $confirmPassword === '') {
        $error = 'Semua field wajib diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif ($password !== $confirmPassword) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $users = readUsers();

        if (findUserByEmail($users, $email)) {
            $error = 'Email ini sudah terdaftar. Silakan login.';
        } else {
            $namaAman = htmlspecialchars($nama, ENT_QUOTES, 'UTF-8');
            $emailAman = filter_var($email, FILTER_SANITIZE_EMAIL);

            $newUser = [
                'id' => nextUserId($users),
                'nama' => $namaAman,
                'email' => $emailAman,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'remember_token' => null,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $users[] = $newUser;
            saveUsers($users);

            header('Location: login.php?msg=registered');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>register.php — Daftar Akun</title>
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
      <span class="term__path">register.php</span>
    </div>
    <div class="term__body">
      <p class="brand"><span class="brand__prompt">&gt;</span><span class="brand__title">Buat Akun Baru</span></p>
      <p class="subtitle">Isi data di bawah untuk mendaftar. Password akan di-hash sebelum disimpan.</p>

      <?php if ($error): ?>
        <div class="alert alert--error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="field">
          <label for="nama">Nama Lengkap</label>
          <input type="text" id="nama" name="nama" placeholder="Nama kamu" value="<?= htmlspecialchars($old['nama'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="field">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="kamu@email.com" value="<?= htmlspecialchars($old['email'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required>
        </div>
        <div class="field">
          <label for="confirm_password">Konfirmasi Password</label>
          <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password" required>
        </div>
        <button type="submit" class="btn">Daftar →</button>
      </form>

      <p class="foot-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
  </div>
  <p class="credit">Tugas Rutin 7 - PHP Native Login/Register System</p>
</body>
</html>

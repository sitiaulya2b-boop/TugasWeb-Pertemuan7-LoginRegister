<?php
require_once __DIR__ . '/includes/functions.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$successMsg = '';

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'registered') {
        $successMsg = 'Registrasi berhasil! Silakan login.';
    } elseif ($_GET['msg'] === 'logged_out') {
        $successMsg = 'Kamu berhasil logout.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['remember_me']);

    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } else {
        $users = readUsers();
        $foundUser = findUserByEmail($users, $email);

        if ($foundUser && password_verify($password, $foundUser['password'])) {
            $_SESSION['user_id'] = (int) $foundUser['id'];
            $_SESSION['username'] = $foundUser['nama'];
            $_SESSION['email'] = $foundUser['email'];

            if ($rememberMe) {
                $token = bin2hex(random_bytes(32));

                foreach ($users as &$u) {
                    if ((int) $u['id'] === (int) $foundUser['id']) {
                        $u['remember_token'] = $token;
                    }
                }
                unset($u);
                saveUsers($users);

                setcookie(
                    'remember_token',
                    $foundUser['id'] . ':' . $token,
                    time() + (30 * 24 * 60 * 60), 
                    '/',
                    '',
                    false,
                    true 
                );
            }

            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>login.php - Masuk</title>
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
      <span class="term__path">login.php</span>
    </div>
    <div class="term__body">
      <p class="brand"><span class="brand__prompt">&gt;</span><span class="brand__title">Masuk ke Akun</span></p>
      <p class="subtitle">Masukkan email dan password kamu untuk melanjutkan.</p>

      <?php if ($successMsg): ?>
        <div class="alert alert--success"><?= htmlspecialchars($successMsg, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="alert alert--error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="field">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="kamu@email.com" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : '' ?>" required>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Password kamu" required>
        </div>
        <label class="checkbox-row">
          <input type="checkbox" name="remember_me" value="1">
          Ingat saya di perangkat ini selama 30 hari
        </label>
        <button type="submit" class="btn">Login →</button>
      </form>

      <p class="foot-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
  </div>
  <p class="credit">Tugas Rutin 7 — PHP Native Login/Register System</p>
</body>
</html>

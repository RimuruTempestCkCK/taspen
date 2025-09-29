<?php
require 'init.php';
include 'koneksi.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
  $role = strtolower(trim($_SESSION['role']));

  if ($role === 'admin') {
      header("Location: admin/dashboard.php");
  } elseif ($role === 'peminjam') {
      header("Location: peminjam/dashboard.php");
  } elseif ($role === 'pimpinan') {
      header("Location: pimpinan/dashboard.php");
  } else {
      echo "Role tidak dikenal: " . htmlspecialchars($role);
      exit;
  }
  exit;
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = strtolower(trim($user['role']));

    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } elseif ($_SESSION['role'] === 'peminjam') {
        header("Location: peminjam/dashboard.php");
    } elseif ($_SESSION['role'] === 'pimpinan') {
        header("Location: pimpinan/dashboard.php");
    } else {
        echo "Role tidak dikenal: " . htmlspecialchars($_SESSION['role']);
        exit;
    }
    exit;
  } else {
    $_SESSION['error'] = "Email atau password salah!";
    header("Location: login.php");
    exit;
  }
}


?>


<!-- FORM LOGIN -->
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Sistem Peminjaman Dosir</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>
<body>
  <div class="page-wrapper" id="main-wrapper">
    <div class="position-relative min-vh-100 d-flex align-items-center justify-content-center">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="text-center mb-3">
                 <span style="font-size: 20px; font-weight: bold; color: #0d6efd;">PT. Taspen Padang</span>
            </div>
            <h5 class="text-center mb-4">Silakan Login</h5>

            <?php if (isset($_SESSION['error'])): ?>
              <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
              <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email" required>
              </div>
              <div class="mb-4">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required autocomplete="current-password">
              </div>
              <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

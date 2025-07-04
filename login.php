<?php
require 'init.php';
include 'koneksi.php';

// Jika sudah login, langsung redirect ke dashboard sesuai role
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
  $role = $_SESSION['role'];
  if ($role === 'admin') {
    header("Location: admin/dashboard.php");
  } elseif ($role === 'peminjam') {
    header("Location: peminjam/dashboard.php");
  } else {
    echo "Role tidak dikenal.";
  }
  exit;
}

// Proses Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
  $user = mysqli_fetch_assoc($query);

  if ($user && md5($password) === $user['password']) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } elseif ($user['role'] === 'peminjam') {
        header("Location: peminjam/dashboard.php");
    } else {
        echo "Role tidak dikenal: " . $user['role'];
        exit;
    }
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
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-4">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
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

<?php
require_once __DIR__ . '/../init.php';
if (isset($_SESSION['user_id'])) {
  if ($_SESSION['role'] === 'admin') {
    header("Location: ../admin/dashboard.php");
  } elseif ($_SESSION['role'] === 'peminjam') {
    header("Location: ../peminjam/dashboard.php");
  } else {
    header("Location: ../index.php"); // fallback
  }
  exit;
}


include 'koneksi.php';

// PROSES LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
  $user = mysqli_fetch_assoc($query);

  if ($user && md5($password) == $user['password']) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['nama'] = $user['nama'];
      $_SESSION['role'] = $user['role'];
      header("Location: index.php");
      exit;
  } else {
      $_SESSION['error'] = "Email atau Password salah!";
      header("Location: login.php");
      exit;
  }
}
?>

<!-- FORM LOGIN TETAP DI BAWAH -->
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Sistem Peminjaman Dosir</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>
<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
    data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="#" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="assets/images/logos/dark-logo.svg" width="180" alt="">
                </a>
                <p class="text-center">Silakan login untuk melanjutkan</p>

                <?php if (isset($_SESSION['error'])): ?>
                  <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" required>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-2 fs-4 mb-4 rounded-2">Login</button>
                </form>

                <div class="text-center">
                  <p class="fs-6 mb-0">Belum punya akun?</p>
                  <a class="text-primary fw-bold" href="register.php">Buat Akun</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

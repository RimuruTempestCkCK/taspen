<?php
require_once '../init.php';
require_once '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

// Notifikasi dari session
$flash = '';
if (isset($_SESSION['flash'])) {
  $flash = $_SESSION['flash'];
  unset($_SESSION['flash']);
}

// Tambah user
if (isset($_POST['tambah_user'])) {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role = $_POST['role'];

  $insert = mysqli_query($conn, "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$password', '$role')");
  $_SESSION['flash'] = $insert ? 'User berhasil ditambahkan' : 'Gagal menambahkan user';
  header("Location: kelola-user.php");
  exit;
}

// Edit user
if (isset($_POST['edit_user'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $role = $_POST['role'];
  
  $query = "UPDATE users SET nama='$nama', email='$email', role='$role'";
  if (!empty($_POST['password'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $query .= ", password='$password'";
  }
  $query .= " WHERE id='$id'";

  $update = mysqli_query($conn, $query);
  $_SESSION['flash'] = $update ? 'User berhasil diubah' : 'Gagal mengubah user';
  header("Location: kelola-user.php");
  exit;
}

// Hapus user
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $delete = mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
  $_SESSION['flash'] = $delete ? 'User berhasil dihapus' : 'Gagal menghapus user';
  header("Location: kelola-user.php");
  exit;
}

// Ambil data user
$query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Kelola User</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>
<body>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
  data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
  
  <?php include '../layouts/sidebar.php'; ?>
  <div class="body-wrapper">
    
    <div class="container-fluid">

      <!-- Flash Notifikasi -->
      <?php if ($flash): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= $flash ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h5 class="fw-semibold mb-1">Kelola Pengguna</h5>
          <p class="text-muted mb-0">Daftar akun pengguna sistem</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
          <i class="ti ti-user-plus me-1"></i> Tambah User
        </button>
      </div>

      <!-- Tabel User -->
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table align-middle text-nowrap mb-0">
              <thead class="text-dark fs-4">
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; while ($user = mysqli_fetch_assoc($query)) : ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($user['nama']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                      <span class="badge bg-<?= $user['role'] === 'admin' ? 'primary' : 'success' ?>">
                        <?= $user['role'] === 'peminjam' ? 'Peminjam' : ucfirst($user['role']) ?>
                      </span>

                    </td>
                    <td>
                      <button class="btn btn-sm btn-warning me-1 btn-edit" 
                        data-id="<?= $user['id'] ?>" 
                        data-nama="<?= htmlspecialchars($user['nama']) ?>" 
                        data-email="<?= htmlspecialchars($user['email']) ?>" 
                        data-role="<?= $user['role'] ?>">
                        <i class="ti ti-edit"></i> Edit
                      </button>
                      <a href="?hapus=<?= $user['id'] ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Yakin ingin menghapus user ini?')">
                        <i class="ti ti-trash"></i> Hapus
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($query) === 0): ?>
                  <tr><td colspan="5" class="text-center">Belum ada data pengguna.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Pengguna</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="nama" class="form-control mb-3" placeholder="Nama" required>
        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
        <select name="role" class="form-control" required>
          <option value="admin">Admin</option>
          <option value="peminjam">Peminjam</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="submit" name="tambah_user" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <input type="hidden" name="id" id="edit-id">
      <div class="modal-header">
        <h5 class="modal-title">Edit Pengguna</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="nama" id="edit-nama" class="form-control mb-3" required>
        <input type="email" name="email" id="edit-email" class="form-control mb-3" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="Ganti Password (opsional)">
        <select name="role" id="edit-role" class="form-control" required>
          <option value="admin">Admin</option>
          <option value="peminjam">Peminjam</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="submit" name="edit_user" class="btn btn-warning">Update</button>
      </div>
    </form>
  </div>
</div>

<?php include '../layouts/footer.php'; ?>

<script src="../assets/libs/jquery/dist/jquery.min.js"></script>
<script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sidebarmenu.js"></script>
<script src="../assets/js/app.min.js"></script>
<script>
  // Isi form edit ketika tombol edit diklik
  $(document).on('click', '.btn-edit', function () {
    $('#edit-id').val($(this).data('id'));
    $('#edit-nama').val($(this).data('nama'));
    $('#edit-email').val($(this).data('email'));
    $('#edit-role').val($(this).data('role'));
    $('#modalEdit').modal('show');
  });
</script>
</body>
</html>

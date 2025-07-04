<?php
require_once '../init.php';
require_once '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

$flash = '';
if (isset($_SESSION['flash'])) {
  $flash = $_SESSION['flash'];
  unset($_SESSION['flash']);
}

// Tambah dosir
if (isset($_POST['tambah_dosir'])) {
  $nama = $_POST['nama_dosir'];
  $deskripsi = $_POST['deskripsi'];
  $insert = mysqli_query($conn, "INSERT INTO dosir (nama_dosir, deskripsi) VALUES ('$nama', '$deskripsi')");
  $_SESSION['flash'] = $insert ? 'Data dosir berhasil ditambahkan' : 'Gagal menambahkan data dosir';
  header("Location: kelola-dosir.php");
  exit;
}

// Edit dosir
if (isset($_POST['edit_dosir'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama_dosir'];
  $deskripsi = $_POST['deskripsi'];
  $update = mysqli_query($conn, "UPDATE dosir SET nama_dosir='$nama', deskripsi='$deskripsi' WHERE id='$id'");
  $_SESSION['flash'] = $update ? 'Data dosir berhasil diubah' : 'Gagal mengubah data dosir';
  header("Location: kelola-dosir.php");
  exit;
}

// Hapus dosir
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $delete = mysqli_query($conn, "DELETE FROM dosir WHERE id='$id'");
  $_SESSION['flash'] = $delete ? 'Data dosir berhasil dihapus' : 'Gagal menghapus data dosir';
  header("Location: kelola-dosir.php");
  exit;
}

$query = mysqli_query($conn, "SELECT * FROM dosir ORDER BY id DESC");
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Kelola Dosir</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>
<body>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
  data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
  
  <?php include '../layouts/sidebar.php'; ?>
  <div class="body-wrapper">
    <div class="container-fluid">

      <!-- Notifikasi -->
      <?php if ($flash): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= $flash ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h5 class="fw-semibold mb-1">Kelola Dosir</h5>
          <p class="text-muted mb-0">Manajemen data arsip dosir</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
          <i class="ti ti-folder-plus me-1"></i> Tambah Dosir
        </button>
      </div>

      <!-- Tabel -->
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table align-middle text-nowrap mb-0">
              <thead class="text-dark fs-4">
                <tr>
                  <th>No</th>
                  <th>Nama Dosir</th>
                  <th>Deskripsi</th>
                  <th>Waktu Dibuat</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_dosir']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                      <button class="btn btn-sm btn-warning me-1 btn-edit" 
                        data-id="<?= $row['id'] ?>" 
                        data-nama="<?= htmlspecialchars($row['nama_dosir']) ?>" 
                        data-deskripsi="<?= htmlspecialchars($row['deskripsi']) ?>">
                        <i class="ti ti-edit"></i> Edit
                      </button>
                      <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                        <i class="ti ti-trash"></i> Hapus
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($query) === 0): ?>
                  <tr><td colspan="5" class="text-center">Belum ada data dosir.</td></tr>
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
        <h5 class="modal-title">Tambah Dosir</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="nama_dosir" class="form-control mb-3" placeholder="Nama Dosir" required>
        <textarea name="deskripsi" class="form-control" placeholder="Deskripsi (opsional)"></textarea>
      </div>
      <div class="modal-footer">
        <button type="submit" name="tambah_dosir" class="btn btn-primary">Simpan</button>
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
        <h5 class="modal-title">Edit Dosir</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="nama_dosir" id="edit-nama" class="form-control mb-3" required>
        <textarea name="deskripsi" id="edit-deskripsi" class="form-control"></textarea>
      </div>
      <div class="modal-footer">
        <button type="submit" name="edit_dosir" class="btn btn-warning">Update</button>
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
  $(document).on('click', '.btn-edit', function () {
    $('#edit-id').val($(this).data('id'));
    $('#edit-nama').val($(this).data('nama'));
    $('#edit-deskripsi').val($(this).data('deskripsi'));
    $('#modalEdit').modal('show');
  });
</script>
</body>
</html>

<?php
ob_start(); // Memulai output buffering
require_once '../init.php';
require_once '../koneksi.php';

// Cek apakah pengguna sudah login dan memiliki peran admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once BASE_PATH . '/koneksi.php';
require_once BASE_PATH . '/layouts/header.php';
require_once BASE_PATH . '/layouts/sidebar.php';
// require_once BASE_PATH . '/layouts/topbar.php';
// require_once BASE_PATH . '/layouts/footer.php';

$flash = '';
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}

// Tambah dosir
if (isset($_POST['tambah_dosir'])) {
    $nama = $_POST['nama_dosir'];
    $deskripsi = $_POST['deskripsi'];
    $id_user = $_POST['id_user']; // dari dropdown

    $upload_dir = 'dosir/';
    $file_name = null;

    if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] == 0) {
        $ext = pathinfo($_FILES['file_pdf']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) === 'pdf') {
            $file_name = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['file_pdf']['tmp_name'], __DIR__ . '/' . $upload_dir . $file_name);
        }
    }

    $insert = mysqli_query($conn, "INSERT INTO dosir (id_user, nama_dosir, deskripsi, file_pdf) 
                                   VALUES ('$id_user', '$nama', '$deskripsi', '$file_name')");
    $_SESSION['flash'] = $insert ? 'Data dosir berhasil ditambahkan' : 'Gagal menambahkan data dosir';
    header("Location: kelola-dosir.php");
    exit;
}

if (isset($_POST['edit_dosir'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama_dosir'];
    $deskripsi = $_POST['deskripsi'];
    $id_user = $_POST['id_user']; // ikut diperbarui

    // Handle file upload (jika ada file baru)
    $file_sql = '';
    if (isset($_FILES['file_pdf']) && $_FILES['file_pdf']['error'] == 0) {
        $ext = pathinfo($_FILES['file_pdf']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) === 'pdf') {
            // Ambil nama file lama
            $result = mysqli_query($conn, "SELECT file_pdf FROM dosir WHERE id='$id'");
            $data = mysqli_fetch_assoc($result);

            // Hapus file lama jika ada
            if (!empty($data['file_pdf']) && file_exists(__DIR__ . '/dosir/' . $data['file_pdf'])) {
                unlink(__DIR__ . '/dosir/' . $data['file_pdf']);
            }

            // Simpan file baru
            $file_name = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['file_pdf']['tmp_name'], __DIR__ . '/dosir/' . $file_name);
            $file_sql = ", file_pdf='$file_name'";
        }
    }

    // Update dengan id_user juga
    $update = mysqli_query($conn, 
        "UPDATE dosir 
         SET id_user='$id_user', nama_dosir='$nama', deskripsi='$deskripsi' $file_sql 
         WHERE id='$id'"
    );

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

// Ambil data dosir
$query = mysqli_query($conn, "SELECT * FROM dosir ORDER BY id DESC");

// Ambil daftar user
$userList = mysqli_query($conn, "SELECT id, nama FROM users ORDER BY nama ASC");

$query = mysqli_query($conn, "SELECT d.*, u.nama as nama_user 
                              FROM dosir d 
                              LEFT JOIN users u ON d.id_user = u.id 
                              ORDER BY d.id DESC");


?>

<?php include BASE_PATH . '/layouts/topbar.php'; ?>

<!-- Konten utama -->
<div class="main-content py-4">
    <div class="container">
        <br><br><br>
        <!-- Notifikasi -->
        <?php if ($flash): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $flash ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <br><br>

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
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama User</th>
                                <th>Nama Dosir</th>
                                <th>Deskripsi</th>
                                <th>File Dosir</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama_user']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_dosir']) ?></td>
                                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                    <td>
                                        <?php if (!empty($row['file_pdf'])): ?>
                                            <a href="dosir/<?= htmlspecialchars($row['file_pdf']) ?>" target="_blank">Lihat File</a>
                                        <?php else: ?>
                                            <em>Tidak ada</em>
                                        <?php endif; ?>
                                    </td>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div> <!-- penutup .container -->

</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Dosir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <!-- Dropdown user -->
                <label for="id_user" class="form-label">Pilih User</label>
                <select name="id_user" class="form-control mb-3" required>
                    <option value="">-- Pilih User --</option>
                    <?php while ($user = mysqli_fetch_assoc($userList)) : ?>
                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['nama']) ?></option>
                    <?php endwhile; ?>
                </select>

                <input type="text" name="nama_dosir" class="form-control mb-3" placeholder="Nama Dosir" required>
                <textarea name="deskripsi" class="form-control" placeholder="Deskripsi (opsional)"></textarea>
                <br>
                <input type="file" name="file_pdf" class="form-control mb-3" accept="application/pdf">
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
        <form method="post" enctype="multipart/form-data" class="modal-content">
            <input type="hidden" name="id" id="edit-id">
            <div class="modal-header">
                <h5 class="modal-title">Edit Dosir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <!-- Dropdown user -->
                <label for="edit-id_user" class="form-label">Pilih User</label>
                <select name="id_user" id="edit-id_user" class="form-control mb-3" required>
                    <option value="">-- Pilih User --</option>
                    <?php
                    // ulangi query userList karena sudah habis dipakai di modal tambah
                    $userList2 = mysqli_query($conn, "SELECT id, nama FROM users ORDER BY nama ASC");
                    while ($user = mysqli_fetch_assoc($userList2)) :
                    ?>
                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['nama']) ?></option>
                    <?php endwhile; ?>
                </select>

                <input type="text" name="nama_dosir" id="edit-nama" class="form-control mb-3" required>
                <textarea name="deskripsi" id="edit-deskripsi" class="form-control"></textarea>
                <br>
                <input type="file" name="file_pdf" class="form-control mb-3" accept="application/pdf">
            </div>
            <div class="modal-footer">
                <button type="submit" name="edit_dosir" class="btn btn-warning">Update</button>
            </div>
        </form>
    </div>
</div>


</div> <!-- penutup .container -->
<?php include BASE_PATH . '/layouts/footer.php'; ?>

</div>


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

<?php ob_end_flush(); ?>

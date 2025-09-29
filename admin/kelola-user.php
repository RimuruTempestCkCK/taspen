<?php
ob_start();
require_once '../init.php';
require_once BASE_PATH . '/koneksi.php';

// Cek login dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Notifikasi session
$flash = '';
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}

// Tambah user
if (isset($_POST['tambah_user'])) {
    $nip = htmlspecialchars(trim($_POST['nip']));
    $nama = htmlspecialchars(trim($_POST['nama']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (nip, nama, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nip, $nama, $email, $password, $role);
    $result = $stmt->execute();

    $_SESSION['flash'] = $result ? 'User berhasil ditambahkan' : 'Gagal menambahkan user';
    header("Location: kelola-user.php");
    exit;
}

// Edit user
if (isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $nip = htmlspecialchars(trim($_POST['nip']));
    $nama = htmlspecialchars(trim($_POST['nama']));
    $email = htmlspecialchars(trim($_POST['email']));
    $role = $_POST['role'];

    $query = "UPDATE users SET nip = ?, nama = ?, email = ?, role = ?";
    $types = "ssss";
    $params = [$nip, $nama, $email, $role];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query .= ", password = ?";
        $types .= "s";
        $params[] = $password;
    }

    $query .= " WHERE id = ?";
    $types .= "i";
    $params[] = $id;

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $result = $stmt->execute();

    $_SESSION['flash'] = $result ? 'User berhasil diubah' : 'Gagal mengubah user';
    header("Location: kelola-user.php");
    exit;
}


// Hapus user
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();

    $_SESSION['flash'] = $result ? 'User berhasil dihapus' : 'Gagal menghapus user';
    header("Location: kelola-user.php");
    exit;
}

// Ambil data user
$query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<?php include BASE_PATH . '/layouts/header.php'; ?>
<?php include BASE_PATH . '/layouts/sidebar.php'; ?>
<?php include BASE_PATH . '/layouts/topbar.php'; ?>

<div class="main-content py-4">
    <div class="container">
        <br><br><br>
        <?php if ($flash): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flash) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-semibold mb-1">Kelola Pengguna</h5>
                <p class="text-muted mb-0">Daftar akun pengguna sistem</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="ti ti-user-plus me-1"></i> Tambah User
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle text-nowrap mb-0">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Nip</th>
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
                                    <td><?= htmlspecialchars($user['nip']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <?php
                                            $badgeClass = match ($user['role']) {
                                                'admin' => 'primary',
                                                'peminjam' => 'success',
                                                'pimpinan' => 'info',
                                                default => 'secondary'
                                            };
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?>">
                                            <?= ucfirst(htmlspecialchars($user['role'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning me-1 btn-edit" 
                                            data-id="<?= $user['id'] ?>" 
                                            data-nama="<?= htmlspecialchars($user['nama']) ?>"
                                            data-nip="<?= htmlspecialchars($user['nip']) ?>"  
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
                <input type="text" name="nip" class="form-control mb-3" placeholder="Nip" required>
                <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                <select name="role" class="form-control" required>
                    <option value="admin">Admin</option>
                    <option value="peminjam">Peminjam</option>
                    <option value="pimpinan">Pimpinan</option>
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
                <input type="text" name="nip" id="edit-nip" class="form-control mb-3" required>
                <input type="email" name="email" id="edit-email" class="form-control mb-3" required>
                <input type="password" name="password" class="form-control mb-3" placeholder="Ganti Password (opsional)">
                <select name="role" id="edit-role" class="form-control" required>
                    <option value="admin">Admin</option>
                    <option value="peminjam">Peminjam</option>
                    <option value="pimpinan">Pimpinan</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="submit" name="edit_user" class="btn btn-warning">Update</button>
            </div>
        </form>
    </div>
</div>

<?php include BASE_PATH . '/layouts/footer.php'; ?>

<script src="../assets/libs/jquery/dist/jquery.min.js"></script>
<script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sidebarmenu.js"></script>
<script src="../assets/js/app.min.js"></script>
<script>
    $(document).on('click', '.btn-edit', function () {
        $('#edit-id').val($(this).data('id'));
        $('#edit-nama').val($(this).data('nama'));
        $('#edit-nip').val($(this).data('nip'));
        $('#edit-email').val($(this).data('email'));
        $('#edit-role').val($(this).data('role'));
        $('#modalEdit').modal('show');
    });
</script>

<?php ob_end_flush(); ?>

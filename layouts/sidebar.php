<?php
require_once __DIR__ . '/../init.php';
$role = $_SESSION['role'];
?>

<!-- Sidebar Start -->
<aside class="left-sidebar">
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="index.php" class="text-nowrap logo-img">
        <span style="font-size: 20px; font-weight: bold; color: #0d6efd;">PT. Taspen Padang</span>
      </a>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="fas fa-times fs-8"></i>
      </div>
    </div>
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">

        <!-- Main -->
        <li class="nav-small-cap">
          <i class="fas fa-ellipsis-h nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Main</span>
        </li>

        <!-- DASHBOARD -->
        <li class="sidebar-item">
          <a class="sidebar-link" href="dashboard.php">
            <i class="fas fa-tachometer-alt"></i>
            <span class="hide-menu">Dashboard</span>
          </a>
        </li>

        <?php if ($role === 'admin'): ?>
          <!-- Admin -->
          <li class="nav-small-cap"><i class="fas fa-database nav-small-cap-icon fs-4"></i><span class="hide-menu">Master Data</span></li>
          <li class="sidebar-item"><a class="sidebar-link" href="kelola-user.php"><i class="fas fa-users-cog"></i><span class="hide-menu">Kelola User</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link" href="kelola-dosir.php"><i class="fas fa-archive"></i><span class="hide-menu">Kelola Dosir</span></a></li>

          <li class="nav-small-cap"><i class="fas fa-random nav-small-cap-icon fs-4"></i><span class="hide-menu">Transaksi</span></li>
          <li class="sidebar-item"><a class="sidebar-link" href="data-peminjaman.php"><i class="fas fa-book"></i><span class="hide-menu">Data Peminjaman</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link" href="verifikasi-peminjaman.php"><i class="fas fa-check-double"></i><span class="hide-menu">Verifikasi Peminjaman</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link" href="verifikasi_pengembalian.php"><i class="fas fa-undo-alt"></i><span class="hide-menu">Verifikasi Pengembalian</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link" href="data-pengembalian.php"><i class="fas fa-file-export"></i><span class="hide-menu">Data Pengembalian</span></a></li>
        <?php endif; ?>

        <?php if ($role === 'peminjam'): ?>
          <!-- Peminjam -->
          <li class="nav-small-cap"><i class="fas fa-folder-open nav-small-cap-icon fs-4"></i><span class="hide-menu">Dosir</span></li>
          <li class="sidebar-item"><a class="sidebar-link" href="daftar-dosir.php"><i class="fas fa-folder"></i><span class="hide-menu">Daftar Dosir</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link" href="ajukan-pengembalian.php"><i class="fas fa-undo-alt"></i><span class="hide-menu">Ajukan Pengembalian</span></a></li>
        <?php endif; ?>

        <?php if ($role === 'admin' || $role === 'pimpinan'): ?>
          <!-- Laporan untuk admin & pimpinan -->
          <li class="nav-small-cap"><i class="fas fa-file-alt nav-small-cap-icon fs-4"></i><span class="hide-menu">Laporan</span></li>
          <li class="sidebar-item"><a class="sidebar-link" href="laporan-peminjaman.php"><i class="fas fa-file-invoice"></i><span class="hide-menu">Laporan Peminjaman</span></a></li>
        <?php endif; ?>

        <!-- Akun -->
        <li class="nav-small-cap"><i class="fas fa-user-circle nav-small-cap-icon fs-4"></i><span class="hide-menu">Akun</span></li>
        <li class="sidebar-item"><a class="sidebar-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i><span class="hide-menu">Logout</span></a></li>
      </ul>
    </nav>
  </div>
</aside>
<!-- Sidebar End -->

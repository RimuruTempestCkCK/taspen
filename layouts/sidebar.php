<?php
require_once __DIR__ . '/../init.php';
$role = $_SESSION['role']; // nilai: 'admin' atau 'peminjam'
?>

<!-- Sidebar Start -->
<aside class="left-sidebar">
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="index.php" class="text-nowrap logo-img">
        <!-- <img src="../assets/images/logos/dark-logo.svg" width="180" alt="Logo" /> -->
        <span style="font-size: 20px; font-weight: bold; color: #0d6efd;">PT. Taspen Padang</span>
      </a>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-8"></i>
      </div>
    </div>
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">
        <!-- Main -->
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Main</span>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="index.php">
            <i class="ti ti-layout-dashboard"></i>
            <span class="hide-menu">Dashboard</span>
          </a>
        </li>

        <?php if ($role == 'admin'): ?>
          <!-- Master Data -->
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Master Data</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="kelola-user.php">
              <i class="ti ti-users"></i>
              <span class="hide-menu">Kelola User</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="kelola-dosir.php">
              <i class="ti ti-archive"></i>
              <span class="hide-menu">Kelola Dosir</span>
            </a>
          </li>

          <!-- Transaksi -->
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Transaksi</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="data-peminjaman.php">
              <i class="ti ti-user-check"></i>
              <span class="hide-menu">Data Peminjaman</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="data-pengembalian.php">
              <i class="ti ti-user-exclamation"></i>
              <span class="hide-menu">Data Pengembalian</span>
            </a>
          </li>

          <!-- Laporan -->
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Laporan</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="laporan.php">
              <i class="ti ti-report"></i>
              <span class="hide-menu">Laporan Peminjaman</span>
            </a>
          </li>

        <?php elseif ($role == 'peminjam'): ?>
          <!-- Fitur Peminjam -->
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Dosir</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="daftar-dosir.php">
              <i class="ti ti-folder"></i>
              <span class="hide-menu">Daftar Dosir</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="ajukan-peminjaman.php">
              <i class="ti ti-folder-plus"></i>
              <span class="hide-menu">Ajukan Peminjaman</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="ajukan-pengembalian.php">
              <i class="ti ti-folder-check"></i>
              <span class="hide-menu">Ajukan Pengembalian</span>
            </a>
          </li>
        <?php endif; ?>

        <!-- Logout -->
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Akun</span>
        </li>
        <li class="sidebar-item">
        <a class="sidebar-link" href="../logout.php">
            <i class="ti ti-logout"></i>
            <span class="hide-menu">Logout</span>
         </a>

        </li>

      </ul>
    </nav>
  </div>
</aside>
<!-- Sidebar End -->

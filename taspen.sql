-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2025 at 09:02 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taspen`
--

-- --------------------------------------------------------

--
-- Table structure for table `dosir`
--

CREATE TABLE `dosir` (
  `id` int(11) NOT NULL,
  `nama_dosir` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dosir`
--

INSERT INTO `dosir` (`id`, `nama_dosir`, `deskripsi`, `created_at`) VALUES
(6, 'Dosir Pegawai Tetap', 'Berkas pengangkatan pegawai tetap tahun 2022', '2025-07-13 14:59:51'),
(7, 'Dosir Cuti Tahunan', 'Dokumen pengajuan cuti tahunan seluruh pegawai', '2025-07-13 14:59:51'),
(8, 'Dosir Pensiun 2025', 'Data pegawai yang akan pensiun di tahun 2025', '2025-07-13 14:59:51'),
(9, 'Dosir SK Jabatan', 'Surat keputusan kenaikan jabatan pegawai', '2025-07-13 14:59:51'),
(10, 'Dosir Kontrak Outsourcing', 'Dokumen kontrak tenaga outsourcing 2024', '2025-07-13 14:59:51'),
(11, 'Dosir Pelatihan', 'Laporan hasil pelatihan internal tahun 2023', '2025-07-13 14:59:51'),
(12, 'Dosir Evaluasi Kinerja', 'Evaluasi kinerja tahunan seluruh divisi', '2025-07-13 14:59:51'),
(13, 'Dosir Kesehatan Pegawai', 'Rekap data medis dan BPJS', '2025-07-13 14:59:51'),
(14, 'Dosir Kehadiran', 'Absensi manual dan digital pegawai sejak 2020', '2025-07-13 14:59:51'),
(15, 'Dosir Audit Internal', 'Laporan audit internal semester 1 2025', '2025-07-13 14:59:51');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_dosir` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('Menunggu Persetujuan','Disetujui','Ditolak','Dipinjam','Dikembalikan','Menunggu Pengembalian') NOT NULL DEFAULT 'Menunggu Persetujuan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `id_user`, `id_dosir`, `tanggal_pinjam`, `tanggal_kembali`, `status`) VALUES
(10, 13, 14, '2025-07-13', NULL, 'Dipinjam'),
(11, 13, 8, '2025-07-13', NULL, 'Dipinjam'),
(12, 13, 12, '2025-07-13', NULL, 'Dipinjam'),
(13, 13, 13, '2025-07-13', NULL, 'Dipinjam'),
(14, 13, 13, '2025-07-13', NULL, 'Dipinjam'),
(15, 13, 12, '2025-07-13', '2025-07-13', 'Dikembalikan'),
(16, 13, 15, '2025-07-14', '2025-07-14', 'Dikembalikan');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','peminjam','pimpinan') DEFAULT 'peminjam',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `created_at`, `nama`) VALUES
(5, 'kontol@email.com', '$2y$10$B5pFj9RSYFXEwJaIl2yQjOQBeT/oNnkHo7APAzCcDCZWxOpif7QTu', 'admin', '2025-04-24 09:35:48', 'Dika KOntol'),
(9, 'dika@gmail.com', '$2y$10$Jz9pDlIzBPCHso3TPWrlgOxJx4sLsWd/Mrm5yP88hYeAsr8wHlNmO', 'admin', '2025-07-04 09:20:50', 'Febri Handika'),
(10, 'diksay@gmail.com', '$2y$10$Nx3WehggRoJ49NQPW/Kte.4vhpCBxfauQsiESAw7m7msmJtSuvbDa', 'peminjam', '2025-07-04 14:53:25', 'Dika Sayang'),
(13, 'febri@gmail.com', '$2y$10$baBfPRAoyVSmC0QdkuWUR.LLKZLTImcEwYaxtd0Xv0OBEX1w0RyTm', 'peminjam', '2025-07-05 12:09:24', 'febri'),
(14, 'pimpinan@gmail.com', '$2y$10$mPY9vsqIMX0qeY/ymdBJM.tgkOctK.5BCa3snE3Go45JHU7rKDohq', 'pimpinan', '2025-07-13 14:44:03', 'Pimpinan Ganteng'),
(15, 'ahah@gmail.com', '$2y$10$hGoom.j0pkeVRFZZ9jf9vOKpf08jW9smEfpyBRgN.rK3Pe1UOHOT6', 'pimpinan', '2025-07-14 05:53:01', 'ah ah');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dosir`
--
ALTER TABLE `dosir`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_dosir` (`id_dosir`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dosir`
--
ALTER TABLE `dosir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_dosir`) REFERENCES `dosir` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

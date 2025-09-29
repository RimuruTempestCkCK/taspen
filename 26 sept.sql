-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 26 Sep 2025 pada 14.08
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

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
-- Struktur dari tabel `dosir`
--

CREATE TABLE `dosir` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_dosir` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_pdf` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dosir`
--

INSERT INTO `dosir` (`id`, `id_user`, `nama_dosir`, `deskripsi`, `file_pdf`, `created_at`) VALUES
(6, 19, 'Dosir Pegawai Tetap', 'Berkas pengangkatan pegawai tetap tahun 2022', '6899c376acd46.pdf', '2025-07-13 14:59:51'),
(7, 19, 'Dosir Cuti Tahunan', 'Dokumen pengajuan cuti tahunan seluruh pegawai', '6899c3713f62b.pdf', '2025-07-13 14:59:51'),
(8, 17, 'Dosir Pensiun 2025', 'Data pegawai yang akan pensiun di tahun 2025', '6899c36c475fe.pdf', '2025-07-13 14:59:51'),
(9, 17, 'Dosir SK Jabatan', 'Surat keputusan kenaikan jabatan pegawai', '6899c366b9675.pdf', '2025-07-13 14:59:51'),
(10, 18, 'Dosir Kontrak Outsourcing', 'Dokumen kontrak tenaga outsourcing 2024', '6899c35d70360.pdf', '2025-07-13 14:59:51'),
(11, 10, 'Dosir Pelatihan', 'Laporan hasil pelatihan internal tahun 2023', '6891a28f6d076.pdf', '2025-07-13 14:59:51'),
(12, 13, 'Dosir Evaluasi Kinerja', 'Evaluasi kinerja tahunan seluruh divisi', '6891a286eb87a.pdf', '2025-07-13 14:59:51'),
(13, 13, 'Dosir Kesehatan Pegawai', 'Rekap data medis dan BPJS', '6891a27eea95a.pdf', '2025-07-13 14:59:51'),
(14, 9, 'Dosir Kehadiran', 'Absensi manual dan digital pegawai sejak 2020', '6891a27673f2e.pdf', '2025-07-13 14:59:51'),
(15, 10, 'Dosir Audit Internal', 'Laporan audit internal semester 1 2025', '6891a26319b74.pdf', '2025-07-13 14:59:51'),
(16, 18, 'AAA', 'adsfsad', '68ab45aad2588.pdf', '2025-08-24 17:02:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
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
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `id_user`, `id_dosir`, `tanggal_pinjam`, `tanggal_kembali`, `status`) VALUES
(18, 13, 15, '2025-08-11', '2025-08-25', 'Dikembalikan'),
(19, 13, 9, '2025-08-12', '2025-08-25', 'Dikembalikan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','peminjam','pimpinan') DEFAULT 'peminjam',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nip`, `email`, `password`, `role`, `created_at`, `nama`) VALUES
(1, '20130001', 'dikabaru@gmail.com', '$2y$10$VrYXJosKpH.s.KgTRYCaWu8yawleZIhGGIRcVEEKyqrrCdAf.RBu2', 'peminjam', '2025-08-16 19:19:38', 'Dika Baru'),
(9, '1212121212121212', 'dika@gmail.com', '$2y$10$Jz9pDlIzBPCHso3TPWrlgOxJx4sLsWd/Mrm5yP88hYeAsr8wHlNmO', 'admin', '2025-07-04 09:20:50', 'Febri Handika'),
(10, '1212121212121212', 'diksay@gmail.com', '$2y$10$Nx3WehggRoJ49NQPW/Kte.4vhpCBxfauQsiESAw7m7msmJtSuvbDa', 'peminjam', '2025-07-04 14:53:25', 'Dika Sayang'),
(13, '898989898989', 'febri@gmail.com', '$2y$10$baBfPRAoyVSmC0QdkuWUR.LLKZLTImcEwYaxtd0Xv0OBEX1w0RyTm', 'peminjam', '2025-07-05 12:09:24', 'febri'),
(14, '90900909090', 'pimpinan@gmail.com', '$2y$10$Jz9pDlIzBPCHso3TPWrlgOxJx4sLsWd/Mrm5yP88hYeAsr8wHlNmO', 'pimpinan', '2025-07-13 14:44:03', 'Pimpinan Ganteng'),
(17, '67676767676767', 'mami@gmail.com', '$2y$10$zWA22K3X/UySMvr/OZD8wOQszaHBAWS5vk56Iv3zZEcwDyV/sUJ.K', 'peminjam', '2025-08-12 04:33:42', 'mami renita'),
(18, '123123112312312', 'dfsdf@sadas.com', '$2y$10$hSPbcFQO987nv3akl2omRuQnGqF/wf.htQnWjOLD67DTf16nPKNxG', 'admin', '2025-08-18 10:14:50', 'AAAAAAAA'),
(19, '123123123', 'iiiii@mail.com', '$2y$10$sh4uYpKu1YVx.1qWG8bjmeojcYhpQyCIAFYmzwSfUOVvZe.0iwf2O', 'peminjam', '2025-08-18 10:15:21', 'fikaaaaa');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dosir`
--
ALTER TABLE `dosir`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_dosir` (`id_dosir`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dosir`
--
ALTER TABLE `dosir`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_dosir`) REFERENCES `dosir` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

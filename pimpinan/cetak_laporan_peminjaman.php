<?php
require_once '../init.php';
require_once '../koneksi.php';
require_once '../fpdf182/fpdf.php'; // pastikan path ini benar

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

if (!$tanggal_awal || !$tanggal_akhir) {
    die("Tanggal tidak valid.");
}

$query = mysqli_query($conn, "
    SELECT u.nama AS nama_user, d.nama_dosir, p.tanggal_pinjam, p.tanggal_kembali, p.status
    FROM peminjaman p
    JOIN users u ON p.id_user = u.id
    JOIN dosir d ON p.id_dosir = d.id
    WHERE p.tanggal_pinjam BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
    ORDER BY p.tanggal_pinjam DESC
");

// Mulai PDF
$pdf = new FPDF();
$pdf->AddPage();

// KOP LEMBAGA
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 6, 'PEMERINTAH KOTA PADANG', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 6, 'DINAS KEARSIPAN DAN PERPUSTAKAAN', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, 'Jl. Contoh No. 123, Padang, Sumatera Barat', 0, 1, 'C');
$pdf->Cell(0, 6, 'Telp: (0751) 123456 - Email: arsip@padang.go.id', 0, 1, 'C');
$pdf->Ln(2);

// GARIS PEMISAH
$pdf->SetLineWidth(0.5);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

// JUDUL LAPORAN
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'Laporan Peminjaman Dosir', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, "Periode: " . date('d-m-Y', strtotime($tanggal_awal)) . " s/d " . date('d-m-Y', strtotime($tanggal_akhir)), 0, 1, 'C');
$pdf->Ln(5);

// TABEL
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 8, 'No', 1);
$pdf->Cell(40, 8, 'Peminjam', 1);
$pdf->Cell(50, 8, 'Dosir', 1);
$pdf->Cell(30, 8, 'Tgl Pinjam', 1);
$pdf->Cell(30, 8, 'Tgl Kembali', 1);
$pdf->Cell(30, 8, 'Status', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$no = 1;
while ($row = mysqli_fetch_assoc($query)) {
    $pdf->Cell(10, 7, $no++, 1);
    $pdf->Cell(40, 7, $row['nama_user'], 1);
    $pdf->Cell(50, 7, $row['nama_dosir'], 1);
    $pdf->Cell(30, 7, date('d-m-Y', strtotime($row['tanggal_pinjam'])), 1);
    $pdf->Cell(30, 7, $row['tanggal_kembali'] ? date('d-m-Y', strtotime($row['tanggal_kembali'])) : '-', 1);
    $pdf->Cell(30, 7, $row['status'], 1);
    $pdf->Ln();
}

// SPASI SEBELUM TANDA TANGAN
$pdf->Ln(15);

// TANDA TANGAN
$tanggal_cetak = date('d-m-Y');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(120, 6, '', 0, 0);
$pdf->Cell(0, 6, "Padang, $tanggal_cetak", 0, 1);
$pdf->Cell(120, 6, '', 0, 0);
$pdf->Cell(0, 6, "Mengetahui,", 0, 1);
$pdf->Cell(120, 20, '', 0, 0);
$pdf->Cell(0, 20, "Pimpinan", 0, 1);
$pdf->Cell(120, 6, '', 0, 0);
$pdf->Cell(0, 6, "(___________________)", 0, 1);

$pdf->Output('I', 'Laporan_Peminjaman.pdf');

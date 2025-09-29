<?php
require_once '../init.php';

if (!isset($_GET['file'])) {
    http_response_code(400);
    echo "Parameter file tidak ditemukan.";
    exit;
}

$filename = basename($_GET['file']);

// Validasi file hanya PDF
if (pathinfo($filename, PATHINFO_EXTENSION) !== 'pdf') {
    http_response_code(403);
    echo "Akses ditolak.";
    exit;
}

// Ubah path ini agar sesuai folder penyimpanan
$filepath = BASE_PATH . "/admin/dosir/" . $filename;

if (!file_exists($filepath)) {
    http_response_code(404);
    echo "File tidak ditemukan.";
    exit;
}

// Header agar file bisa ditampilkan dalam iframe
header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
readfile($filepath);
exit;

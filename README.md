# Sistem Peminjaman Dosir

Aplikasi ini adalah sistem manajemen peminjaman dosir berbasis web, dikembangkan menggunakan PHP dan MySQL. Sistem ini memungkinkan admin untuk mengelola pengguna dan data dosir secara efisien.

## 📌 Fitur

- Login dan otentikasi pengguna (admin & peminjam)
- Manajemen data pengguna
- Tambah/Edit/Hapus data dosir
- Tampilan modern dan responsif menggunakan template admin
- Notifikasi aksi berhasil/gagal
- Desain berbasis Bootstrap 5

## 🛠️ Teknologi

- PHP 8+
- MySQL / MariaDB
- HTML + Bootstrap 5
- JavaScript (jQuery)
- Template Admin modern

## 📥 Cara Install / Menjalankan Aplikasi

### 1. Clone Repository
```bash
git clone https://github.com/username/nama-repo.git
cd nama-repo
```

### 2. Import Database
- Buka `phpMyAdmin` atau tool DB lainnya
- Buat database baru, contoh: `peminjaman_dosir`
- Import file SQL yang disediakan (misalnya `database.sql`)

### 3. Konfigurasi Koneksi Database
Edit file `koneksi.php`:

```php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'peminjaman_dosir';
```

### 4. Jalankan di Browser
Letakkan project ini di folder `htdocs` (jika menggunakan XAMPP), lalu buka di browser:

```
http://localhost/nama-folder/
```

### 5. Login
Gunakan akun admin yang sudah ditambahkan atau bisa register manual melalui database.


---

## 👨‍💻 Developer

- Nama: Firdinal Juliandre
- Universitas Dharma Andalas
- Tahun: 2025

---

## 📄 Lisensi

Proyek ini hanya digunakan untuk kebutuhan pembelajaran / tugas akhir.

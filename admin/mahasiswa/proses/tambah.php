<?php
// Lokasi: admin/mahasiswa/proses/tambah.php

include "../../../auth/cek_login.php";
checkRole('super_admin');

include "../../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// ── Ambil input ───────────────────────────────────────────────────────
$nim                 = trim($_POST['nim'] ?? '');
$nama                = trim($_POST['nama'] ?? '');
$email               = trim($_POST['email'] ?? '');
$no_hp               = trim($_POST['no_hp'] ?? '');
$jenis_kelamin       = $_POST['jenis_kelamin'] ?? '';
$tanggal_lahir       = $_POST['tanggal_lahir'] ?? '';
$prodi               = trim($_POST['prodi'] ?? '');
$angkatan            = (int)($_POST['angkatan'] ?? 0);
$jurusan_id          = !empty($_POST['jurusan_id']) ? (int)$_POST['jurusan_id'] : null;
$dosen_pa_id         = !empty($_POST['dosen_pa_id']) ? (int)$_POST['dosen_pa_id'] : null;
$alamat              = trim($_POST['alamat'] ?? '');
$password            = $_POST['password'] ?? '';
$konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

// ── Validasi ──────────────────────────────────────────────────────────
$errors = [];

if ($nim === '')    $errors[] = 'NIM wajib diisi.';
if ($nama === '')   $errors[] = 'Nama lengkap wajib diisi.';
if ($email === '')  $errors[] = 'Email wajib diisi.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
if ($jenis_kelamin === '') $errors[] = 'Jenis kelamin wajib dipilih.';
if ($prodi === '')         $errors[] = 'Program studi wajib diisi.';
if ($angkatan === 0)       $errors[] = 'Angkatan wajib diisi.';
if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter.';
if ($password !== $konfirmasi_password) $errors[] = 'Password dan konfirmasi password tidak cocok.';

// Cek duplikat NIM
$cek = mysqli_query($conn, "SELECT id FROM mahasiswa WHERE nim = '" . mysqli_real_escape_string($conn, $nim) . "'");
if (mysqli_num_rows($cek) > 0) $errors[] = 'NIM sudah terdaftar.';

// Cek duplikat email
$cek2 = mysqli_query($conn, "SELECT id FROM mahasiswa WHERE email = '" . mysqli_real_escape_string($conn, $email) . "'");
if (mysqli_num_rows($cek2) > 0) $errors[] = 'Email sudah terdaftar.';

if (!empty($errors)) {
    $_SESSION['error'] = implode('<br>', $errors);
    $_SESSION['old']   = $_POST;
    header('Location: ../tambah.php');
    exit;
}

// ── Upload foto ───────────────────────────────────────────────────────
$nama_foto = null;

if (!empty($_FILES['foto']['name'])) {
    $ext    = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $ukuran = $_FILES['foto']['size'];

    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
        $_SESSION['error'] = 'Format foto harus JPG atau PNG.';
        $_SESSION['old']   = $_POST;
        header('Location: ../tambah.php');
        exit;
    }

    if ($ukuran > 2 * 1024 * 1024) {
        $_SESSION['error'] = 'Ukuran foto maksimal 2MB.';
        $_SESSION['old']   = $_POST;
        header('Location: ../tambah.php');
        exit;
    }

    $nama_foto  = time() . '_' . $nim . '.' . $ext;
    $upload_dir = '../../../uploads/mahasiswa/';

    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $nama_foto)) {
        $_SESSION['error'] = 'Gagal mengupload foto.';
        $_SESSION['old']   = $_POST;
        header('Location: ../tambah.php');
        exit;
    }
}

// ── Insert ────────────────────────────────────────────────────────────
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$tgl        = !empty($tanggal_lahir) ? "'" . mysqli_real_escape_string($conn, $tanggal_lahir) . "'" : "NULL";
$j_id       = $jurusan_id  ? $jurusan_id  : "NULL";
$d_id       = $dosen_pa_id ? $dosen_pa_id : "NULL";
$foto_val   = $nama_foto   ? "'" . mysqli_real_escape_string($conn, $nama_foto) . "'" : "NULL";

$sql = "INSERT INTO mahasiswa 
            (nim, nama, email, no_hp, jenis_kelamin, tanggal_lahir, prodi, angkatan, jurusan_id, dosen_pa_id, alamat, foto, password, created_at)
        VALUES (
            '" . mysqli_real_escape_string($conn, $nim) . "',
            '" . mysqli_real_escape_string($conn, $nama) . "',
            '" . mysqli_real_escape_string($conn, $email) . "',
            '" . mysqli_real_escape_string($conn, $no_hp) . "',
            '" . mysqli_real_escape_string($conn, $jenis_kelamin) . "',
            $tgl,
            '" . mysqli_real_escape_string($conn, $prodi) . "',
            $angkatan,
            $j_id,
            $d_id,
            '" . mysqli_real_escape_string($conn, $alamat) . "',
            $foto_val,
            '" . mysqli_real_escape_string($conn, $password_hash) . "',
            NOW()
        )";

if (mysqli_query($conn, $sql)) {
    $_SESSION['success'] = 'Data mahasiswa berhasil ditambahkan.';
    header('Location: ../index.php');
} else {
    $_SESSION['error'] = 'Gagal menyimpan: ' . mysqli_error($conn);
    $_SESSION['old']   = $_POST;
    header('Location: ../tambah.php');
}
exit;

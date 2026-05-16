<?php
include '../../../../auth/cek_login.php';
include '../../../../auth/cek_admin.php';
include '../../../../config/koneksi.php';

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// ── Ambil & sanitasi input ────────────────────────────────────────────
$nim                 = trim($_POST['nim'] ?? '');
$nama                = trim($_POST['nama'] ?? '');
$email               = trim($_POST['email'] ?? '');
$no_hp               = trim($_POST['no_hp'] ?? '');
$jenis_kelamin       = $_POST['jenis_kelamin'] ?? '';
$tanggal_lahir       = $_POST['tanggal_lahir'] ?? null;
$angkatan            = (int)($_POST['angkatan'] ?? 0);
$semester            = (int)($_POST['semester'] ?? 1);
$prodi_id            = (int)($_POST['prodi_id'] ?? 0);
$alamat              = trim($_POST['alamat'] ?? '');
$password            = $_POST['password'] ?? '';
$konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

// ── Validasi wajib ───────────────────────────────────────────────────
$errors = [];

if ($nim === '')           $errors[] = 'NIM wajib diisi.';
if ($nama === '')          $errors[] = 'Nama lengkap wajib diisi.';
if ($email === '')         $errors[] = 'Email wajib diisi.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
if ($jenis_kelamin === '') $errors[] = 'Jenis kelamin wajib dipilih.';
if ($angkatan === 0)       $errors[] = 'Angkatan wajib diisi.';
if ($prodi_id === 0)       $errors[] = 'Program studi wajib dipilih.';
if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter.';
if ($password !== $konfirmasi_password) $errors[] = 'Password dan konfirmasi password tidak cocok.';

// Cek NIM duplikat
$cek_nim = mysqli_query($koneksi, "SELECT id FROM mahasiswa WHERE nim = '" . mysqli_real_escape_string($koneksi, $nim) . "'");
if (mysqli_num_rows($cek_nim) > 0) $errors[] = 'NIM sudah terdaftar.';

// Cek email duplikat
$cek_email = mysqli_query($koneksi, "SELECT id FROM mahasiswa WHERE email = '" . mysqli_real_escape_string($koneksi, $email) . "'");
if (mysqli_num_rows($cek_email) > 0) $errors[] = 'Email sudah terdaftar.';

// ── Jika ada error: simpan old input & kembali ───────────────────────
if (!empty($errors)) {
    $_SESSION['error']  = implode('<br>', $errors);
    $_SESSION['old']    = $_POST;
    header('Location: ../tambah.php');
    exit;
}

// ── Upload foto ───────────────────────────────────────────────────────
$nama_foto = 'default.jpg';

if (!empty($_FILES['foto']['name'])) {
    $allowed_ext  = ['jpg', 'jpeg', 'png'];
    $max_size     = 2 * 1024 * 1024; // 2 MB
    $ext          = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $ukuran       = $_FILES['foto']['size'];

    if (!in_array($ext, $allowed_ext)) {
        $_SESSION['error'] = 'Format foto harus JPG, JPEG, atau PNG.';
        $_SESSION['old']   = $_POST;
        header('Location: ../tambah.php');
        exit;
    }

    if ($ukuran > $max_size) {
        $_SESSION['error'] = 'Ukuran foto maksimal 2MB.';
        $_SESSION['old']   = $_POST;
        header('Location: ../tambah.php');
        exit;
    }

    $nama_foto  = time() . '_' . $nim . '.' . $ext;
    $upload_dir = '../../../../uploads/mahasiswa/';

    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $nama_foto)) {
        $_SESSION['error'] = 'Gagal mengupload foto.';
        $_SESSION['old']   = $_POST;
        header('Location: ../tambah.php');
        exit;
    }
}

// ── Hash password ─────────────────────────────────────────────────────
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// ── Insert ke database ────────────────────────────────────────────────
$tgl = (!empty($tanggal_lahir)) ? "'" . mysqli_real_escape_string($koneksi, $tanggal_lahir) . "'" : "NULL";

$sql = "INSERT INTO mahasiswa 
            (nim, nama, email, no_hp, jenis_kelamin, tanggal_lahir, angkatan, semester, prodi_id, alamat, foto, password, created_at)
        VALUES
            (
                '" . mysqli_real_escape_string($koneksi, $nim) . "',
                '" . mysqli_real_escape_string($koneksi, $nama) . "',
                '" . mysqli_real_escape_string($koneksi, $email) . "',
                '" . mysqli_real_escape_string($koneksi, $no_hp) . "',
                '" . mysqli_real_escape_string($koneksi, $jenis_kelamin) . "',
                $tgl,
                $angkatan,
                $semester,
                $prodi_id,
                '" . mysqli_real_escape_string($koneksi, $alamat) . "',
                '" . mysqli_real_escape_string($koneksi, $nama_foto) . "',
                '" . mysqli_real_escape_string($koneksi, $password_hash) . "',
                NOW()
            )";

if (mysqli_query($koneksi, $sql)) {
    $_SESSION['success'] = 'Data mahasiswa berhasil ditambahkan.';
    header('Location: ../index.php');
} else {
    $_SESSION['error'] = 'Gagal menyimpan data: ' . mysqli_error($koneksi);
    $_SESSION['old']   = $_POST;
    header('Location: ../tambah.php');
}
exit;
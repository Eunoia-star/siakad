<?php
include "../../../auth/cek_login.php";
checkRole('super_admin');
include "../../../config/koneksi.php";

function kembali($pesan, $lokasi = '../tambah.php')
{
    echo "<script>alert('" . addslashes($pesan) . "'); window.location.href='$lokasi';</script>";
    exit;
}

function columnExists($conn, $table, $column)
{
    $table = mysqli_real_escape_string($conn, $table);
    $column = mysqli_real_escape_string($conn, $column);
    $cek = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    return $cek && mysqli_num_rows($cek) > 0;
}

function buatUserDosen($conn, $username, $email, $jurusan_id, $dosen_id)
{
    $password = md5($username);

    $fields = ['username', 'password', 'role'];
    $values = ["'$username'", "'$password'", "'dosen'"];

    if (columnExists($conn, 'users', 'jurusan_id')) {
        $fields[] = 'jurusan_id';
        $values[] = "'$jurusan_id'";
    }
    if (columnExists($conn, 'users', 'dosen_id')) {
        $fields[] = 'dosen_id';
        $values[] = "'$dosen_id'";
    }
    if (columnExists($conn, 'users', 'email')) {
        $fields[] = 'email';
        $values[] = "'$email'";
    }

    $sql = "INSERT INTO users (" . implode(',', $fields) . ") VALUES (" . implode(',', $values) . ")";
    return mysqli_query($conn, $sql);
}

$jurusan_id = (int) ($_POST['jurusan_id'] ?? 0);
$nidn = trim($_POST['nidn'] ?? '');
$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');

if ($jurusan_id == 0 || $nidn == '' || $nama == '') {
    kembali('Data wajib belum lengkap.');
}

$nidn = mysqli_real_escape_string($conn, $nidn);
$nip = mysqli_real_escape_string($conn, $nip);
$nama = mysqli_real_escape_string($conn, $nama);
$email = mysqli_real_escape_string($conn, $email);
$no_hp = mysqli_real_escape_string($conn, $no_hp);
$alamat = mysqli_real_escape_string($conn, $alamat);

$cekNidn = mysqli_query($conn, "SELECT id FROM dosen WHERE nidn = '$nidn'");
if (mysqli_num_rows($cekNidn) > 0) {
    kembali('NIDN sudah digunakan.');
}

if ($nip != '') {
    $cekNip = mysqli_query($conn, "SELECT id FROM dosen WHERE nip = '$nip'");
    if (mysqli_num_rows($cekNip) > 0) {
        kembali('NIP sudah digunakan.');
    }
}

$username = $nip != '' ? $nip : $nidn;
$cekUser = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
if (mysqli_num_rows($cekUser) > 0) {
    kembali('Username login dosen sudah digunakan.');
}

$foto = '';
if (!empty($_FILES['foto']['name'])) {
    $folder = "../../../uploads/dosen/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        kembali('Format foto harus jpg, jpeg, png, atau webp.');
    }

    $foto = 'dsn_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
    move_uploaded_file($_FILES['foto']['tmp_name'], $folder . $foto);
}

$insert = mysqli_query($conn, "
    INSERT INTO dosen
    (jurusan_id, nidn, nip, nama, email, no_hp, alamat, foto)
    VALUES
    ('$jurusan_id', '$nidn', '$nip', '$nama', '$email', '$no_hp', '$alamat', '$foto')
");

if (!$insert) {
    kembali('Gagal menyimpan dosen: ' . mysqli_error($conn));
}

$dosen_id = mysqli_insert_id($conn);
buatUserDosen($conn, $username, $email, $jurusan_id, $dosen_id);

kembali('Data dosen berhasil ditambahkan. Username dan password default adalah NIP/NIDN.', '../index.php');

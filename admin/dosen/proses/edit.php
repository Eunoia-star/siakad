<?php
include "../../../auth/cek_login.php";
checkRole('super_admin');
include "../../../config/koneksi.php";

function kembali($pesan, $lokasi = '../index.php')
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

$id = (int) ($_POST['id'] ?? 0);
$jurusan_id = (int) ($_POST['jurusan_id'] ?? 0);
$nidn = trim($_POST['nidn'] ?? '');
$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$foto_lama = $_POST['foto_lama'] ?? '';
$username_lama = $_POST['username_lama'] ?? '';

if ($id == 0 || $jurusan_id == 0 || $nidn == '' || $nama == '') {
    kembali('Data wajib belum lengkap.', '../edit.php?id=' . $id);
}

$nidn = mysqli_real_escape_string($conn, $nidn);
$nip = mysqli_real_escape_string($conn, $nip);
$nama = mysqli_real_escape_string($conn, $nama);
$email = mysqli_real_escape_string($conn, $email);
$no_hp = mysqli_real_escape_string($conn, $no_hp);
$alamat = mysqli_real_escape_string($conn, $alamat);
$foto_lama = mysqli_real_escape_string($conn, $foto_lama);
$username_lama = mysqli_real_escape_string($conn, $username_lama);

$cekNidn = mysqli_query($conn, "SELECT id FROM dosen WHERE nidn = '$nidn' AND id != '$id'");
if (mysqli_num_rows($cekNidn) > 0) {
    kembali('NIDN sudah digunakan dosen lain.', '../edit.php?id=' . $id);
}

if ($nip != '') {
    $cekNip = mysqli_query($conn, "SELECT id FROM dosen WHERE nip = '$nip' AND id != '$id'");
    if (mysqli_num_rows($cekNip) > 0) {
        kembali('NIP sudah digunakan dosen lain.', '../edit.php?id=' . $id);
    }
}

$username_baru = $nip != '' ? $nip : $nidn;
$cekUser = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username_baru' AND username != '$username_lama'");
if (mysqli_num_rows($cekUser) > 0) {
    kembali('Username login dosen sudah digunakan.', '../edit.php?id=' . $id);
}

$foto = $foto_lama;
if (!empty($_FILES['foto']['name'])) {
    $folder = "../../../uploads/dosen/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        kembali('Format foto harus jpg, jpeg, png, atau webp.', '../edit.php?id=' . $id);
    }

    $foto_baru = 'dsn_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
    move_uploaded_file($_FILES['foto']['tmp_name'], $folder . $foto_baru);

    if ($foto_lama != '' && file_exists($folder . $foto_lama)) {
        unlink($folder . $foto_lama);
    }

    $foto = $foto_baru;
}

$update = mysqli_query($conn, "
    UPDATE dosen SET
        jurusan_id = '$jurusan_id',
        nidn = '$nidn',
        nip = '$nip',
        nama = '$nama',
        email = '$email',
        no_hp = '$no_hp',
        alamat = '$alamat',
        foto = '$foto'
    WHERE id = '$id'
");

if (!$update) {
    kembali('Gagal mengupdate dosen: ' . mysqli_error($conn), '../edit.php?id=' . $id);
}

$setUser = "username = '$username_baru'";
if (columnExists($conn, 'users', 'email')) {
    $setUser .= ", email = '$email'";
}
if (columnExists($conn, 'users', 'jurusan_id')) {
    $setUser .= ", jurusan_id = '$jurusan_id'";
}

$whereUser = "username = '$username_lama'";
if (columnExists($conn, 'users', 'dosen_id')) {
    $whereUser .= " OR dosen_id = '$id'";
}

mysqli_query($conn, "UPDATE users SET $setUser WHERE $whereUser");

kembali('Data dosen berhasil diperbarui.', '../index.php');

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
$nim = trim($_POST['nim'] ?? '');
$nim_lama = trim($_POST['nim_lama'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$jurusan_id = (int) ($_POST['jurusan_id'] ?? 0);
$prodi = trim($_POST['prodi'] ?? '');
$angkatan = trim($_POST['angkatan'] ?? '');
$email = trim($_POST['email'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$dosen_pa_id = !empty($_POST['dosen_pa_id']) ? (int) $_POST['dosen_pa_id'] : 'NULL';
$foto_lama = $_POST['foto_lama'] ?? '';

if ($id == 0 || $nim == '' || $nama == '' || $jurusan_id == 0 || $prodi == '' || $angkatan == '') {
    kembali('Data wajib belum lengkap.', '../edit.php?id=' . $id);
}

$nim = mysqli_real_escape_string($conn, $nim);
$nim_lama = mysqli_real_escape_string($conn, $nim_lama);
$nama = mysqli_real_escape_string($conn, $nama);
$prodi = mysqli_real_escape_string($conn, $prodi);
$angkatan = mysqli_real_escape_string($conn, $angkatan);
$email = mysqli_real_escape_string($conn, $email);
$alamat = mysqli_real_escape_string($conn, $alamat);
$foto_lama = mysqli_real_escape_string($conn, $foto_lama);

$cekNim = mysqli_query($conn, "SELECT id FROM mahasiswa WHERE nim = '$nim' AND id != '$id'");
if (mysqli_num_rows($cekNim) > 0) {
    kembali('NIM sudah digunakan mahasiswa lain.', '../edit.php?id=' . $id);
}

$foto = $foto_lama;
if (!empty($_FILES['foto']['name'])) {
    $folder = "../../../uploads/mahasiswa/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        kembali('Format foto harus jpg, jpeg, png, atau webp.', '../edit.php?id=' . $id);
    }

    $foto_baru = 'mhs_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
    move_uploaded_file($_FILES['foto']['tmp_name'], $folder . $foto_baru);

    if ($foto_lama != '' && file_exists($folder . $foto_lama)) {
        unlink($folder . $foto_lama);
    }

    $foto = $foto_baru;
}

$update = mysqli_query($conn, "
    UPDATE mahasiswa SET
        jurusan_id = '$jurusan_id',
        dosen_pa_id = $dosen_pa_id,
        nim = '$nim',
        nama = '$nama',
        prodi = '$prodi',
        angkatan = '$angkatan',
        email = '$email',
        alamat = '$alamat',
        foto = '$foto'
    WHERE id = '$id'
");

if (!$update) {
    kembali('Gagal mengupdate mahasiswa: ' . mysqli_error($conn), '../edit.php?id=' . $id);
}

$setUser = "username = '$nim'";
if (columnExists($conn, 'users', 'email')) {
    $setUser .= ", email = '$email'";
}
if (columnExists($conn, 'users', 'jurusan_id')) {
    $setUser .= ", jurusan_id = '$jurusan_id'";
}

$whereUser = "username = '$nim_lama'";
if (columnExists($conn, 'users', 'mahasiswa_id')) {
    $whereUser .= " OR mahasiswa_id = '$id'";
}

mysqli_query($conn, "UPDATE users SET $setUser WHERE $whereUser");

kembali('Data mahasiswa berhasil diperbarui.', '../index.php');

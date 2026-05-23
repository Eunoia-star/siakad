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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id = '$id'"));

if (!$data) {
    kembali('Data mahasiswa tidak ditemukan.');
}

$nim = mysqli_real_escape_string($conn, $data['nim']);

$whereUser = "username = '$nim'";
if (columnExists($conn, 'users', 'mahasiswa_id')) {
    $whereUser .= " OR mahasiswa_id = '$id'";
}
mysqli_query($conn, "DELETE FROM users WHERE $whereUser");

$hapus = mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = '$id'");

if ($hapus) {
    $folder = "../../../uploads/mahasiswa/";
    if (!empty($data['foto']) && file_exists($folder . $data['foto'])) {
        unlink($folder . $data['foto']);
    }
    kembali('Data mahasiswa berhasil dihapus.');
}

kembali('Gagal menghapus mahasiswa: ' . mysqli_error($conn));

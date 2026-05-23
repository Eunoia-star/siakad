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
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM dosen WHERE id = '$id'"));

if (!$data) {
    kembali('Data dosen tidak ditemukan.');
}

// Cegah hapus jika dosen masih menjadi PA atau pengampu kelas.
$cekPa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM mahasiswa WHERE dosen_pa_id = '$id'"));
if (($cekPa['total'] ?? 0) > 0) {
    kembali('Dosen tidak bisa dihapus karena masih menjadi Dosen PA mahasiswa.');
}

$cekKelas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM kelas_kuliah WHERE dosen_id = '$id'"));
if (($cekKelas['total'] ?? 0) > 0) {
    kembali('Dosen tidak bisa dihapus karena masih mengampu kelas kuliah.');
}

$username = !empty($data['nip']) ? $data['nip'] : $data['nidn'];
$username = mysqli_real_escape_string($conn, $username);

$whereUser = "username = '$username'";
if (columnExists($conn, 'users', 'dosen_id')) {
    $whereUser .= " OR dosen_id = '$id'";
}
mysqli_query($conn, "DELETE FROM users WHERE $whereUser");

$hapus = mysqli_query($conn, "DELETE FROM dosen WHERE id = '$id'");

if ($hapus) {
    $folder = "../../../uploads/dosen/";
    if (!empty($data['foto']) && file_exists($folder . $data['foto'])) {
        unlink($folder . $data['foto']);
    }
    kembali('Data dosen berhasil dihapus.');
}

kembali('Gagal menghapus dosen: ' . mysqli_error($conn));

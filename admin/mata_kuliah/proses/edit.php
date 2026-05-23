<?php
include '../../../config/koneksi.php';

$id = $_POST['id'];
$jurusan_id = $_POST['jurusan_id'];
$kode_matkul = $_POST['kode_matkul'];
$nama_matkul = $_POST['nama_matkul'];
$sks = $_POST['sks'];

mysqli_query($conn, "

UPDATE mata_kuliah SET

jurusan_id='$jurusan_id',
kode_matkul='$kode_matkul',
nama_matkul='$nama_matkul',
sks='$sks'

WHERE id='$id'

");

header("Location: ../index.php");
?>
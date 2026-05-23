<?php
include '../../../config/koneksi.php';

$jurusan_id = $_POST['jurusan_id'];
$kode_matkul = $_POST['kode_matkul'];
$nama_matkul = $_POST['nama_matkul'];
$sks = $_POST['sks'];

mysqli_query($conn, "

INSERT INTO mata_kuliah
(jurusan_id, kode_matkul, nama_matkul, sks)

VALUES

('$jurusan_id','$kode_matkul',
'$nama_matkul','$sks')

");

header("Location: ../index.php");
?>
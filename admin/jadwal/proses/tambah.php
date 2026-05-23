<?php
include '../../../config/koneksi.php';

$mata_kuliah_id = $_POST['mata_kuliah_id'];
$dosen_id = $_POST['dosen_id'];
$kelas = $_POST['kelas'];
$kuota = $_POST['kuota'];
$hari = $_POST['hari'];
$jam_mulai = $_POST['jam_mulai'];
$jam_selesai = $_POST['jam_selesai'];
$ruangan = $_POST['ruangan'];
$tahun_ajaran = $_POST['tahun_ajaran'];
$semester_aktif = $_POST['semester_aktif'];

mysqli_query($conn, "

INSERT INTO jadwal_perkuliahan

(
mata_kuliah_id,
dosen_id,
kelas,
kuota,
hari,
jam_mulai,
jam_selesai,
ruangan,
tahun_ajaran,
semester_aktif
)

VALUES

(
'$mata_kuliah_id',
'$dosen_id',
'$kelas',
'$kuota',
'$hari',
'$jam_mulai',
'$jam_selesai',
'$ruangan',
'$tahun_ajaran',
'$semester_aktif'
)

");

header("Location: ../index.php");
?>
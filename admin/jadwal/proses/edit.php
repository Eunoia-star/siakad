<?php
include '../../../config/koneksi.php';

$id = $_POST['id'];
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

UPDATE jadwal_perkuliahan SET

mata_kuliah_id='$mata_kuliah_id',
dosen_id='$dosen_id',
kelas='$kelas',
kuota='$kuota',
hari='$hari',
jam_mulai='$jam_mulai',
jam_selesai='$jam_selesai',
ruangan='$ruangan',
tahun_ajaran='$tahun_ajaran',
semester_aktif='$semester_aktif'

WHERE id='$id'

");

header("Location: ../index.php");
?>
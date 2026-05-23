<?php
include '../../config/koneksi.php';
include '../../part/header.php';
include '../../part/sidebar.php';

$id = $_GET['id'];

$data = mysqli_query($conn,
"SELECT * FROM jadwal_perkuliahan WHERE id='$id'");

$row = mysqli_fetch_assoc($data);

$matkul = mysqli_query($conn,
"SELECT * FROM mata_kuliah");
?>

<main class="content">

<header class="topbar">

    <div>
        <h1>Edit Jadwal</h1>
        <p>Perbarui data jadwal kuliah.</p>
    </div>

    <div class="user-badge">
        <?= $_SESSION['username']; ?>
    </div>

</header>

<div class="form-card">

<form action="proses/edit.php" method="POST">

<input type="hidden"
name="id"
value="<?= $row['id'] ?>">

<div class="form-grid">

<div class="form-group">

<label>Mata Kuliah</label>

<select name="mata_kuliah_id">

<?php while($m = mysqli_fetch_assoc($matkul)){ ?>

<option 
value="<?= $m['id'] ?>"

<?= ($m['id'] == $row['mata_kuliah_id']) ? 'selected' : '' ?>

>

<?= $m['nama_matkul'] ?>

</option>

<?php } ?>

</select>

</div>

<div class="form-group">

<label>Dosen ID</label>

<input 
type="number"
name="dosen_id"
value="<?= $row['dosen_id'] ?>"
>

</div>

<div class="form-group">

<label>Kelas</label>

<input 
type="text"
name="kelas"
value="<?= $row['kelas'] ?>"
>

</div>

<div class="form-group">

<label>Kuota</label>

<input 
type="number"
name="kuota"
value="<?= $row['kuota'] ?>"
>

</div>

<div class="form-group">

<label>Hari</label>

<input 
type="text"
name="hari"
value="<?= $row['hari'] ?>"
>

</div>

<div class="form-group">

<label>Ruangan</label>

<input 
type="text"
name="ruangan"
value="<?= $row['ruangan'] ?>"
>

</div>

<div class="form-group">

<label>Jam Mulai</label>

<input 
type="time"
name="jam_mulai"
value="<?= $row['jam_mulai'] ?>"
>

</div>

<div class="form-group">

<label>Jam Selesai</label>

<input 
type="time"
name="jam_selesai"
value="<?= $row['jam_selesai'] ?>"
>

</div>

<div class="form-group">

<label>Tahun Ajaran</label>

<input 
type="text"
name="tahun_ajaran"
value="<?= $row['tahun_ajaran'] ?>"
>

</div>

<div class="form-group">

<label>Semester Aktif</label>

<select name="semester_aktif">

<option 
value="Ganjil"
<?= ($row['semester_aktif'] == 'Ganjil') ? 'selected' : '' ?>
>
Ganjil
</option>

<option 
value="Genap"
<?= ($row['semester_aktif'] == 'Genap') ? 'selected' : '' ?>
>
Genap
</option>

</select>

</div>

<div class="form-group full">

<div class="button-group">

<button type="submit" class="btn-success">
<i class="bi bi-check-circle"></i>
Update
</button>

<a href="index.php" class="btn-secondary">
<i class="bi bi-arrow-left"></i>
Kembali
</a>

</div>

</div>

</div>

</form>

</div>

</main>

<?php include '../../part/footer.php'; ?>
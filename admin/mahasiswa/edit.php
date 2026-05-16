<?php

include "../../auth/cek_admin.php";
include "../../config/koneksi.php";


if(isset($_POST['simpan'])){

$id=$_POST['id'];

$nim=$_POST['nim'];

$nama=$_POST['nama'];

$prodi=$_POST['prodi'];

$angkatan=$_POST['angkatan'];

$email=$_POST['email'];

$alamat=$_POST['alamat'];


/* ambil foto lama */

$fotoLama=mysqli_fetch_assoc(

mysqli_query(

$conn,

"SELECT foto
FROM mahasiswa
WHERE id='$id'"

)

);

$namaFoto=$fotoLama['foto'];


/* upload foto baru */

if($_FILES['foto']['name']!=""){

$namaFoto=time().
$_FILES['foto']['name'];

move_uploaded_file(

$_FILES['foto']['tmp_name'],

"../../uploads/mahasiswa/".$namaFoto

);

}


/* update */

$query=mysqli_query(

$conn,

"UPDATE mahasiswa SET

nim='$nim',

nama='$nama',

prodi='$prodi',

angkatan='$angkatan',

email='$email',

alamat='$alamat',

foto='$namaFoto'

WHERE id='$id'"

);


if($query){

echo "

<script>

alert('Data berhasil diperbarui');

window.location='index.php';

</script>

";

exit;

}

}



$id=$_GET['id'];

$query=mysqli_query(

$conn,

"SELECT *
FROM mahasiswa
WHERE id='$id'"

);

$data=mysqli_fetch_assoc($query);


include "../../part/header.php";

?>

<link rel="stylesheet" href="edit.css">

<?php include "../../part/sidebar.php"; ?>


<div class="content">

<div class="form-card">

<h2>Edit Mahasiswa</h2>

<form
method="POST"
enctype="multipart/form-data"
>

<input
type="hidden"
name="id"
value="<?= $data['id']?>"
>


<label>NIM</label>

<input
type="text"
name="nim"
value="<?= $data['nim']?>"
required
>


<label>Nama</label>

<input
type="text"
name="nama"
value="<?= $data['nama']?>"
required
>


<label>Prodi</label>

<input
type="text"
name="prodi"
value="<?= $data['prodi']?>"
required
>


<label>Angkatan</label>

<input
type="text"
name="angkatan"
value="<?= $data['angkatan']?>"
required
>


<label>Email</label>

<input
type="email"
name="email"
value="<?= $data['email']?>"
>


<label>Alamat</label>

<textarea
name="alamat"
rows="4"
><?= $data['alamat']?></textarea>


<label>Foto Baru</label>

<input
type="file"
name="foto"
>


<button
type="submit"
name="simpan"
class="btn-simpan"
>

Simpan

</button>

</form>

</div>

</div>


<?php
include "../../part/footer.php";
?>
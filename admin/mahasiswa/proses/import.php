<?php
include "../../../auth/cek_login.php";
checkRole('super_admin');

include "../../../config/koneksi.php";

function kembali($pesan, $lokasi)
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

function generateNim($conn, $angkatan, $jurusan_id)
{
    $angkatanKode = substr($angkatan, -2);
    $jurusanKode = str_pad($jurusan_id, 3, "0", STR_PAD_LEFT);
    $prefix = $angkatanKode . $jurusanKode;

    $query = mysqli_query($conn, "
        SELECT nim 
        FROM mahasiswa 
        WHERE nim LIKE '$prefix%' 
        ORDER BY nim DESC 
        LIMIT 1
    ");

    $last = mysqli_fetch_assoc($query);

    if ($last) {
        $urutan = (int) substr($last['nim'], -4) + 1;
    } else {
        $urutan = 1;
    }

    return $prefix . str_pad($urutan, 4, "0", STR_PAD_LEFT);
}

function buatUserMahasiswa($conn, $nim, $email, $jurusan_id, $mahasiswa_id)
{
    $password = md5($nim);

    $fields = ['username', 'password', 'role'];
    $values = ["'$nim'", "'$password'", "'mahasiswa'"];

    if (columnExists($conn, 'users', 'jurusan_id')) {
        $fields[] = 'jurusan_id';
        $values[] = "'$jurusan_id'";
    }
    if (columnExists($conn, 'users', 'mahasiswa_id')) {
        $fields[] = 'mahasiswa_id';
        $values[] = "'$mahasiswa_id'";
    }
    if (columnExists($conn, 'users', 'email')) {
        $fields[] = 'email';
        $values[] = "'$email'";
    }

    $sql = "INSERT INTO users (" . implode(',', $fields) . ") VALUES (" . implode(',', $values) . ")";
    return mysqli_query($conn, $sql);
}

if (empty($_FILES['file_csv']['name'])) {
    kembali('File CSV belum dipilih', '../import_csv.php');
}

$ext = strtolower(pathinfo($_FILES['file_csv']['name'], PATHINFO_EXTENSION));
if ($ext != 'csv') {
    kembali('File harus berformat CSV.', '../import_csv.php');
}

$file = $_FILES['file_csv']['tmp_name'];
$handle = fopen($file, "r");

if (!$handle) {
    kembali('File CSV gagal dibuka.', '../import_csv.php');
}

$baris = 0;
$berhasil = 0;
$gagal = 0;

while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
    $baris++;

    if ($baris == 1) {
        continue;
    }

    if (count($data) < 6) {
        $gagal++;
        continue;
    }

    $nama = mysqli_real_escape_string($conn, trim($data[0] ?? ''));
    $jurusan_id = (int) trim($data[1] ?? 0);
    $prodi = mysqli_real_escape_string($conn, trim($data[2] ?? ''));
    $angkatan = mysqli_real_escape_string($conn, trim($data[3] ?? ''));
    $email = mysqli_real_escape_string($conn, trim($data[4] ?? ''));
    $alamat = mysqli_real_escape_string($conn, trim($data[5] ?? ''));
    $dosen_pa_id = !empty($data[6]) ? (int) trim($data[6]) : "NULL";

    if ($nama == '' || $jurusan_id == 0 || $prodi == '' || $angkatan == '') {
        $gagal++;
        continue;
    }

    $nim = generateNim($conn, $angkatan, $jurusan_id);

    $insert = mysqli_query($conn, "
        INSERT INTO mahasiswa
        (jurusan_id, dosen_pa_id, nim, nama, prodi, angkatan, email, alamat)
        VALUES
        ('$jurusan_id', $dosen_pa_id, '$nim', '$nama', '$prodi', '$angkatan', '$email', '$alamat')
    ");

    if ($insert) {
        $mahasiswa_id = mysqli_insert_id($conn);
        buatUserMahasiswa($conn, $nim, $email, $jurusan_id, $mahasiswa_id);
        $berhasil++;
    } else {
        $gagal++;
    }
}

fclose($handle);

kembali("Import selesai. Berhasil: $berhasil data. Gagal: $gagal data.", '../index.php');

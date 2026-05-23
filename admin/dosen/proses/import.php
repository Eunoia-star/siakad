<?php
include "../../../auth/cek_login.php";
checkRole('super_admin');
include "../../../config/koneksi.php";

function kembali($pesan, $lokasi = '../import_csv.php')
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

function bersihkan($conn, $data)
{
    return mysqli_real_escape_string($conn, trim($data ?? ''));
}

function buatUserDosen($conn, $username, $email, $jurusan_id, $dosen_id)
{
    // Password default = username dosen. Sistem login project ini memakai md5.
    $password = md5($username);

    $fields = ['username', 'password', 'role'];
    $values = ["'$username'", "'$password'", "'dosen'"];

    if (columnExists($conn, 'users', 'jurusan_id')) {
        $fields[] = 'jurusan_id';
        $values[] = "'$jurusan_id'";
    }

    if (columnExists($conn, 'users', 'dosen_id')) {
        $fields[] = 'dosen_id';
        $values[] = "'$dosen_id'";
    }

    if (columnExists($conn, 'users', 'email')) {
        $fields[] = 'email';
        $values[] = "'$email'";
    }

    $sql = "INSERT INTO users (" . implode(',', $fields) . ") VALUES (" . implode(',', $values) . ")";
    return mysqli_query($conn, $sql);
}

if (empty($_FILES['file_csv']['name'])) {
    kembali('File CSV belum dipilih.');
}

$ext = strtolower(pathinfo($_FILES['file_csv']['name'], PATHINFO_EXTENSION));
if ($ext !== 'csv') {
    kembali('File harus berekstensi .csv.');
}

$file = $_FILES['file_csv']['tmp_name'];
$handle = fopen($file, 'r');

if (!$handle) {
    kembali('File CSV gagal dibuka.');
}

$baris = 0;
$berhasil = 0;
$dilewati = 0;
$gagal = 0;

while (($data = fgetcsv($handle, 2000, ',')) !== false) {
    $baris++;

    // Lewati header.
    if ($baris == 1) {
        continue;
    }

    // Lewati baris kosong.
    if (count($data) < 4 || trim(implode('', $data)) == '') {
        $dilewati++;
        continue;
    }

    // Format CSV:
    // jurusan_id,nidn,nip,nama,email,no_hp,alamat
    $jurusan_id = (int) ($data[0] ?? 0);
    $nidn = bersihkan($conn, $data[1] ?? '');
    $nip = bersihkan($conn, $data[2] ?? '');
    $nama = bersihkan($conn, $data[3] ?? '');
    $email = bersihkan($conn, $data[4] ?? '');
    $no_hp = bersihkan($conn, $data[5] ?? '');
    $alamat = bersihkan($conn, $data[6] ?? '');

    if ($jurusan_id == 0 || $nidn == '' || $nama == '') {
        $gagal++;
        continue;
    }

    // Pastikan jurusan ada.
    $cekJurusan = mysqli_query($conn, "SELECT id FROM jurusan WHERE id = '$jurusan_id' LIMIT 1");
    if (!$cekJurusan || mysqli_num_rows($cekJurusan) == 0) {
        $gagal++;
        continue;
    }

    // Cegah data dosen dobel berdasarkan NIDN.
    $cekNidn = mysqli_query($conn, "SELECT id FROM dosen WHERE nidn = '$nidn' LIMIT 1");
    if ($cekNidn && mysqli_num_rows($cekNidn) > 0) {
        $dilewati++;
        continue;
    }

    // Cegah NIP dobel jika NIP diisi.
    if ($nip != '') {
        $cekNip = mysqli_query($conn, "SELECT id FROM dosen WHERE nip = '$nip' LIMIT 1");
        if ($cekNip && mysqli_num_rows($cekNip) > 0) {
            $dilewati++;
            continue;
        }
    }

    $username = $nip != '' ? $nip : $nidn;

    // Cegah username dobel di tabel users.
    $cekUser = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username' LIMIT 1");
    if ($cekUser && mysqli_num_rows($cekUser) > 0) {
        $dilewati++;
        continue;
    }

    $insert = mysqli_query($conn, "
        INSERT INTO dosen
        (jurusan_id, nidn, nip, nama, email, no_hp, alamat, foto)
        VALUES
        ('$jurusan_id', '$nidn', '$nip', '$nama', '$email', '$no_hp', '$alamat', '')
    ");

    if ($insert) {
        $dosen_id = mysqli_insert_id($conn);
        buatUserDosen($conn, $username, $email, $jurusan_id, $dosen_id);
        $berhasil++;
    } else {
        $gagal++;
    }
}

fclose($handle);

kembali(
    "Import selesai. Berhasil: $berhasil data. Dilewati: $dilewati data. Gagal: $gagal data.",
    '../index.php'
);

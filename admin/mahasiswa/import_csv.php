<?php
include "../../../auth/cek_login.php";
checkRole('super_admin');

include "../../../config/koneksi.php";

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

if (!empty($_FILES['file_csv']['name'])) {

    $file = $_FILES['file_csv']['tmp_name'];

    $handle = fopen($file, "r");

    $baris = 0;
    $berhasil = 0;

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        $baris++;

        if ($baris == 1) {
            continue;
        }

        $nama = $data[0];
        $jurusan_id = $data[1];
        $prodi = $data[2];
        $angkatan = $data[3];
        $email = $data[4];
        $alamat = $data[5];
        $dosen_pa_id = !empty($data[6]) ? $data[6] : "NULL";

        $nim = generateNim($conn, $angkatan, $jurusan_id);
        $password = md5($nim);

        $insert = mysqli_query($conn, "
            INSERT INTO mahasiswa
            (
                jurusan_id,
                dosen_pa_id,
                nim,
                nama,
                prodi,
                angkatan,
                email,
                alamat
            )
            VALUES
            (
                '$jurusan_id',
                $dosen_pa_id,
                '$nim',
                '$nama',
                '$prodi',
                '$angkatan',
                '$email',
                '$alamat'
            )
        ");

        if ($insert) {

            $mahasiswa_id = mysqli_insert_id($conn);

            mysqli_query($conn, "
                INSERT INTO users
                (
                    username,
                    password,
                    role,
                    jurusan_id,
                    mahasiswa_id,
                    email
                )
                VALUES
                (
                    '$nim',
                    '$password',
                    'mahasiswa',
                    '$jurusan_id',
                    '$mahasiswa_id',
                    '$email'
                )
            ");

            $berhasil++;
        }
    }

    fclose($handle);

    echo "
        <script>
            alert('Import selesai. Berhasil: $berhasil data');
            window.location.href = '../index.php';
        </script>
    ";

} else {

    echo "
        <script>
            alert('File CSV belum dipilih');
            window.location.href = '../import_csv.php';
        </script>
    ";
}
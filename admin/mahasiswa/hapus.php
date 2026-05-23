<?php

include "../../auth/cek_login.php";
checkRole('super_admin');

$id = $_GET['id'] ?? '';

if ($id == '') {
    echo "
        <script>
            alert('ID mahasiswa tidak ditemukan');
            window.location.href = 'index.php';
        </script>
    ";
    exit;
}

?>

<script>
    let konfirmasi = confirm("Yakin ingin menghapus mahasiswa ini?");

    if (konfirmasi) {
        window.location.href = "proses/hapus.php?id=<?= $id; ?>";
    } else {
        window.location.href = "index.php";
    }
</script>
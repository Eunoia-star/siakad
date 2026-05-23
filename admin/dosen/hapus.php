<?php
include "../../auth/cek_login.php";
checkRole('super_admin');

include "../../config/koneksi.php";
include "../../part/header.php";
include "../../part/sidebar.php";

function aman($data)
{
    return htmlspecialchars($data ?? '-', ENT_QUOTES, 'UTF-8');
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$query = mysqli_query($conn, "SELECT * FROM dosen WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data dosen tidak ditemukan'); window.location.href='index.php';</script>";
    exit;
}
?>

<main class="content">
    <header class="topbar">
        <div>
            <h1>Hapus Dosen</h1>
            <p>Konfirmasi penghapusan data dosen.</p>
        </div>
        <div class="user-badge"><?= aman($_SESSION['username'] ?? 'Admin'); ?></div>
    </header>

    <section class="form-card">
        <h3>Yakin ingin menghapus data ini?</h3>
        <p>Data dosen dan akun login terkait akan dihapus.</p>

        <table class="detail-table">
            <tr><th>NIDN</th><td><?= aman($data['nidn']); ?></td></tr>
            <tr><th>NIP</th><td><?= aman($data['nip']); ?></td></tr>
            <tr><th>Nama</th><td><?= aman($data['nama']); ?></td></tr>
            <tr><th>Email</th><td><?= aman($data['email']); ?></td></tr>
        </table>

        <div class="form-action">
            <a href="index.php" class="btn-secondary">Batal</a>
            <a href="proses/hapus.php?id=<?= $data['id']; ?>" class="btn-action delete" onclick="return confirm('Data ini benar-benar dihapus?')">Ya, Hapus</a>
        </div>
    </section>
</main>

<?php include "../../part/footer.php"; ?>

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
$query = mysqli_query($conn, "
    SELECT
        d.*,
        j.nama_jurusan,
        f.nama_fakultas
    FROM dosen d
    LEFT JOIN jurusan j ON d.jurusan_id = j.id
    LEFT JOIN fakultas f ON j.fakultas_id = f.id
    WHERE d.id = '$id'
");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data dosen tidak ditemukan'); window.location.href='index.php';</script>";
    exit;
}

$totalBimbingan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM mahasiswa WHERE dosen_pa_id = '$id'"));
$totalKelas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM kelas_kuliah WHERE dosen_id = '$id'"));
?>

<main class="content">
    <header class="topbar">
        <div>
            <h1>Detail Dosen</h1>
            <p>Informasi lengkap dosen, dosen PA, dan pengampu mata kuliah.</p>
        </div>
        <div class="user-badge"><?= aman($_SESSION['username'] ?? 'Admin'); ?></div>
    </header>

    <section class="form-card">
        <div class="detail-layout">
            <div class="profile-photo-box">
                <?php if (!empty($data['foto']) && file_exists("../../uploads/dosen/" . $data['foto'])) { ?>
                    <img src="/siakad/uploads/dosen/<?= aman($data['foto']); ?>" alt="Foto Dosen" class="profile-photo-large">
                <?php } else { ?>
                    <div class="profile-photo-empty"><i class="bi bi-person-badge-fill"></i></div>
                <?php } ?>
            </div>

            <div class="detail-table-wrap">
                <table class="detail-table">
                    <tr><th>NIDN</th><td><?= aman($data['nidn']); ?></td></tr>
                    <tr><th>NIP</th><td><?= aman($data['nip']); ?></td></tr>
                    <tr><th>Nama</th><td><?= aman($data['nama']); ?></td></tr>
                    <tr><th>Email</th><td><?= aman($data['email']); ?></td></tr>
                    <tr><th>No HP</th><td><?= aman($data['no_hp']); ?></td></tr>
                    <tr><th>Jurusan</th><td><?= aman($data['nama_jurusan']); ?></td></tr>
                    <tr><th>Fakultas</th><td><?= aman($data['nama_fakultas']); ?></td></tr>
                    <tr><th>Mahasiswa PA</th><td><?= (int) ($totalBimbingan['total'] ?? 0); ?> mahasiswa</td></tr>
                    <tr><th>Kelas Diampu</th><td><?= (int) ($totalKelas['total'] ?? 0); ?> kelas</td></tr>
                    <tr><th>Alamat</th><td><?= nl2br(aman($data['alamat'])); ?></td></tr>
                </table>
            </div>
        </div>

        <div class="form-action">
            <a href="index.php" class="btn-secondary">Kembali</a>
            <a href="edit.php?id=<?= $data['id']; ?>" class="btn-primary">Edit Data</a>
        </div>
    </section>
</main>

<?php include "../../part/footer.php"; ?>

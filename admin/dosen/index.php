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

$cari = $_GET['cari'] ?? '';
$cari = mysqli_real_escape_string($conn, $cari);

$where = "";
if ($cari != '') {
    $where = "
        WHERE
        d.nidn LIKE '%$cari%' OR
        d.nip LIKE '%$cari%' OR
        d.nama LIKE '%$cari%' OR
        d.email LIKE '%$cari%' OR
        d.no_hp LIKE '%$cari%' OR
        j.nama_jurusan LIKE '%$cari%' OR
        f.nama_fakultas LIKE '%$cari%'
    ";
}

$query = mysqli_query($conn, "
    SELECT
        d.*,
        j.nama_jurusan,
        f.nama_fakultas
    FROM dosen d
    LEFT JOIN jurusan j ON d.jurusan_id = j.id
    LEFT JOIN fakultas f ON j.fakultas_id = f.id
    $where
    ORDER BY d.id DESC
");

$total = mysqli_num_rows($query);
?>

<main class="content">
    <header class="topbar">
        <div>
            <h1>Data Dosen</h1>
            <p>Kelola data dosen, akun login, jurusan, dan fakultas.</p>
        </div>
        <div class="user-badge"><?= aman($_SESSION['username'] ?? 'Admin'); ?></div>
    </header>

    <section class="table-card">
        <div class="table-header">
            <div>
                <h3>Daftar Dosen</h3>
                <p>Total data: <strong><?= $total; ?></strong> dosen</p>
            </div>

            <div class="button-group">
                <a href="tambah.php" class="btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Tambah Dosen
                </a>
            </div>
        </div>

        <form method="GET" class="search-form">
            <input
                type="text"
                name="cari"
                value="<?= aman($cari); ?>"
                placeholder="Cari NIDN, NIP, nama, jurusan, fakultas..."
            >
            <button type="submit" class="btn-primary">Cari</button>
            <?php if ($cari != '') { ?>
                <a href="index.php" class="btn-secondary">Reset</a>
            <?php } ?>
        </form>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>NIDN</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Jurusan</th>
                        <th>Fakultas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total > 0) { ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) { ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <?php if (!empty($row['foto']) && file_exists("../../uploads/dosen/" . $row['foto'])) { ?>
                                        <img src="/siakad/uploads/dosen/<?= aman($row['foto']); ?>" class="table-photo" alt="Foto Dosen">
                                    <?php } else { ?>
                                        <div class="table-avatar"><i class="bi bi-person-badge-fill"></i></div>
                                    <?php } ?>
                                </td>
                                <td><?= aman($row['nidn']); ?></td>
                                <td><?= aman($row['nip']); ?></td>
                                <td><?= aman($row['nama']); ?></td>
                                <td><?= aman($row['email']); ?></td>
                                <td><?= aman($row['no_hp']); ?></td>
                                <td><?= aman($row['nama_jurusan']); ?></td>
                                <td><?= aman($row['nama_fakultas']); ?></td>
                                <td>
                                    <div class="action-group">
                                        <a href="detail.php?id=<?= $row['id']; ?>" class="btn-action detail">Detail</a>
                                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn-action edit">Edit</a>
                                        <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-action delete">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="10" class="empty-table">Data dosen tidak ditemukan.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include "../../part/footer.php"; ?>

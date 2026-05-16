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
        m.nim LIKE '%$cari%' OR
        m.nama LIKE '%$cari%' OR
        m.prodi LIKE '%$cari%' OR
        m.angkatan LIKE '%$cari%' OR
        m.email LIKE '%$cari%' OR
        j.nama_jurusan LIKE '%$cari%' OR
        f.nama_fakultas LIKE '%$cari%' OR
        d.nama LIKE '%$cari%'
    ";
}

$query = mysqli_query($conn, "
    SELECT 
        m.*,
        j.nama_jurusan,
        f.nama_fakultas,
        d.nama AS nama_dosen_pa
    FROM mahasiswa m
    LEFT JOIN jurusan j ON m.jurusan_id = j.id
    LEFT JOIN fakultas f ON j.fakultas_id = f.id
    LEFT JOIN dosen d ON m.dosen_pa_id = d.id
    $where
    ORDER BY m.id DESC
");

$total = mysqli_num_rows($query);

?>

<main class="content">

    <header class="topbar">
        <div>
            <h1>Data Mahasiswa</h1>
            <p>Kelola data mahasiswa, akun login, jurusan, dan dosen PA.</p>
        </div>

        <div class="user-badge">
            <?= aman($_SESSION['username']); ?>
        </div>
    </header>

    <section class="table-card">

        <div class="table-header">

            <div>
                <h3>Daftar Mahasiswa</h3>
                <p>Total data: <strong><?= $total; ?></strong> mahasiswa</p>
            </div>

            <div class="button-group">
                <a href="import_csv.php" class="btn-secondary">
                    <i class="bi bi-file-earmark-spreadsheet"></i>
                    Import CSV
                </a>

                <a href="tambah.php" class="btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Tambah Mahasiswa
                </a>
            </div>

        </div>

        <form method="GET" class="search-form">
            <input
                type="text"
                name="cari"
                value="<?= aman($cari); ?>"
                placeholder="Cari NIM, nama, prodi, jurusan, dosen PA..."
            >

            <button type="submit" class="btn-primary">
                Cari
            </button>
        </form>

        <div class="table-responsive">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Angkatan</th>
                        <th>Email</th>
                        <th>Jurusan</th>
                        <th>Fakultas</th>
                        <th>Dosen PA</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($total > 0) { ?>

                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($query)) {
                        ?>

                            <tr>
                                <td><?= $no++; ?></td>

                                <td>
                                    <?php if (!empty($row['foto'])) { ?>
                                        <img
                                            src="/siakad/uploads/mahasiswa/<?= aman($row['foto']); ?>"
                                            class="table-photo"
                                            alt="Foto Mahasiswa"
                                        >
                                    <?php } else { ?>
                                        <div class="table-avatar">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                    <?php } ?>
                                </td>

                                <td><?= aman($row['nim']); ?></td>
                                <td><?= aman($row['nama']); ?></td>
                                <td><?= aman($row['prodi']); ?></td>
                                <td><?= aman($row['angkatan']); ?></td>
                                <td><?= aman($row['email']); ?></td>
                                <td><?= aman($row['nama_jurusan']); ?></td>
                                <td><?= aman($row['nama_fakultas']); ?></td>
                                <td><?= aman($row['nama_dosen_pa']); ?></td>

                                <td>
                                    <div class="action-group">
                                        <a href="detail.php?id=<?= $row['id']; ?>" class="btn-action detail">
                                            Detail
                                        </a>

                                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn-action edit">
                                            Edit
                                        </a>

                                        <a
                                            href="hapus.php?id=<?= $row['id']; ?>"
                                            class="btn-action delete"
                                        >
                                            Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="11" class="empty-table">
                                Data mahasiswa tidak ditemukan.
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </section>

</main>

<?php include "../../part/footer.php"; ?>
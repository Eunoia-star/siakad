<?php
include '../../config/koneksi.php';
include '../../part/header.php';
include '../../part/sidebar.php';

$data = mysqli_query($conn, "

SELECT 
jadwal_perkuliahan.*,
mata_kuliah.nama_matkul

FROM jadwal_perkuliahan

JOIN mata_kuliah
ON jadwal_perkuliahan.mata_kuliah_id = mata_kuliah.id

");
?>

<main class="content">

    <header class="topbar">

        <div>
            <h1>Jadwal Perkuliahan</h1>
            <p>Kelola seluruh jadwal perkuliahan.</p>
        </div>

        <div class="user-badge">
            <?= $_SESSION['username']; ?>
        </div>

    </header>

    <div class="table-card">

        <div class="table-header">

            <div>
                <h3>Daftar Jadwal</h3>
                <p>Data jadwal kuliah semester aktif</p>
            </div>

            <a href="tambah.php" class="btn-primary">
                <i class="bi bi-plus-circle"></i>
                Tambah Jadwal
            </a>

        </div>

        <div class="table-responsive">

            <table class="data-table">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Mata Kuliah</th>
                        <th>Dosen ID</th>
                        <th>Kelas</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Ruangan</th>
                        <th>Semester</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    while($row = mysqli_fetch_assoc($data)){
                    ?>

                    <tr>

                        <td><?= $no++ ?></td>

                        <td><?= $row['nama_matkul'] ?></td>

                        <td><?= $row['dosen_id'] ?></td>

                        <td><?= $row['kelas'] ?></td>

                        <td><?= $row['hari'] ?></td>

                        <td>
                            <?= $row['jam_mulai'] ?>
                            -
                            <?= $row['jam_selesai'] ?>
                        </td>

                        <td><?= $row['ruangan'] ?></td>

                        <td><?= $row['semester_aktif'] ?></td>

                        <td>

                            <div class="action-group">

                                <a 
                                    href="edit.php?id=<?= $row['id'] ?>"
                                    class="btn-action edit"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                    Edit
                                </a>

                                <a 
                                    href="hapus.php?id=<?= $row['id'] ?>"
                                    class="btn-action delete"
                                    onclick="return confirm('Yakin hapus data?')"
                                >
                                    <i class="bi bi-trash"></i>
                                    Hapus
                                </a>

                            </div>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</main>

<?php include '../../part/footer.php'; ?>
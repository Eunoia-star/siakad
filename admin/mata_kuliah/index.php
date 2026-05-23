<?php
include '../../config/koneksi.php';
include '../../part/header.php';
include '../../part/sidebar.php';

$data = mysqli_query($conn, "
SELECT * FROM mata_kuliah
");
?>

<main class="content">

    <header class="topbar">

        <div>
            <h1>Data Mata Kuliah</h1>
            <p>Kelola data mata kuliah sistem akademik.</p>
        </div>

        <div class="user-badge">
            <?= $_SESSION['username']; ?>
        </div>

    </header>

    <div class="table-card">

        <div class="table-header">

            <div>
                <h3>Daftar Mata Kuliah</h3>
                <p>Semua data mata kuliah tersedia di sini</p>
            </div>

            <a href="tambah.php" class="btn-primary">
                <i class="bi bi-plus-circle"></i>
                Tambah Mata Kuliah
            </a>

        </div>

        <div class="table-responsive">

            <table class="data-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jurusan ID</th>
                        <th>Kode</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
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

                        <td><?= $row['jurusan_id'] ?></td>

                        <td><?= $row['kode_matkul'] ?></td>

                        <td><?= $row['nama_matkul'] ?></td>

                        <td><?= $row['sks'] ?></td>

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
<?php
include '../../config/koneksi.php';
include '../../part/header.php';
include '../../part/sidebar.php';

$id = $_GET['id'];

$data = mysqli_query($conn,
"SELECT * FROM mata_kuliah WHERE id='$id'");

$row = mysqli_fetch_assoc($data);
?>

<main class="content">

    <header class="topbar">

        <div>
            <h1>Edit Mata Kuliah</h1>
            <p>Perbarui data mata kuliah.</p>
        </div>

        <div class="user-badge">
            <?= $_SESSION['username']; ?>
        </div>

    </header>

    <div class="form-card">

        <form action="proses/edit.php" method="POST">

            <input 
                type="hidden"
                name="id"
                value="<?= $row['id'] ?>"
            >

            <div class="form-grid">

                <div class="form-group">

                    <label>Jurusan ID</label>

                    <input 
                        type="number"
                        name="jurusan_id"
                        value="<?= $row['jurusan_id'] ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Kode Mata Kuliah</label>

                    <input 
                        type="text"
                        name="kode_matkul"
                        value="<?= $row['kode_matkul'] ?>"
                        required
                    >

                </div>

                <div class="form-group full">

                    <label>Nama Mata Kuliah</label>

                    <input 
                        type="text"
                        name="nama_matkul"
                        value="<?= $row['nama_matkul'] ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>SKS</label>

                    <input 
                        type="number"
                        name="sks"
                        value="<?= $row['sks'] ?>"
                        required
                    >

                </div>

                <div class="form-group full">

                    <div class="button-group">

                        <button type="submit" class="btn-success">
                            <i class="bi bi-check-circle"></i>
                            Update
                        </button>

                        <a href="index.php" class="btn-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Kembali
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</main>

<?php include '../../part/footer.php'; ?>
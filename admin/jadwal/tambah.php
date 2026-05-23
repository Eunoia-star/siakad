<?php
include '../../config/koneksi.php';
include '../../part/header.php';
include '../../part/sidebar.php';

$matkul = mysqli_query($conn,
"SELECT * FROM mata_kuliah");
?>

<main class="content">

    <header class="topbar">

        <div>
            <h1>Tambah Jadwal</h1>
            <p>Tambahkan jadwal perkuliahan baru.</p>
        </div>

        <div class="user-badge">
            <?= $_SESSION['username']; ?>
        </div>

    </header>

    <div class="form-card">

        <form action="proses/tambah.php" method="POST">

            <div class="form-grid">

                <div class="form-group">

                    <label>Mata Kuliah</label>

                    <select name="mata_kuliah_id" required>

                        <option value="">
                            -- Pilih Mata Kuliah --
                        </option>

                        <?php while($m = mysqli_fetch_assoc($matkul)){ ?>

                        <option value="<?= $m['id'] ?>">
                            <?= $m['nama_matkul'] ?>
                        </option>

                        <?php } ?>

                    </select>

                </div>

                <div class="form-group">

                    <label>Dosen ID</label>

                    <input 
                        type="number"
                        name="dosen_id"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Kelas</label>

                    <input 
                        type="text"
                        name="kelas"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Kuota</label>

                    <input 
                        type="number"
                        name="kuota"
                        value="40"
                    >

                </div>

                <div class="form-group">

                    <label>Hari</label>

                    <input 
                        type="text"
                        name="hari"
                    >

                </div>

                <div class="form-group">

                    <label>Ruangan</label>

                    <input 
                        type="text"
                        name="ruangan"
                    >

                </div>

                <div class="form-group">

                    <label>Jam Mulai</label>

                    <input 
                        type="time"
                        name="jam_mulai"
                    >

                </div>

                <div class="form-group">

                    <label>Jam Selesai</label>

                    <input 
                        type="time"
                        name="jam_selesai"
                    >

                </div>

                <div class="form-group">

                    <label>Tahun Ajaran</label>

                    <input 
                        type="text"
                        name="tahun_ajaran"
                    >

                </div>

                <div class="form-group">

                    <label>Semester Aktif</label>

                    <select name="semester_aktif">

                        <option value="Ganjil">
                            Ganjil
                        </option>

                        <option value="Genap">
                            Genap
                        </option>

                    </select>

                </div>

                <div class="form-group full">

                    <div class="button-group">

                        <button type="submit" class="btn-primary">
                            <i class="bi bi-save"></i>
                            Simpan
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
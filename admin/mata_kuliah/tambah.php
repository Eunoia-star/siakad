<?php
include '../../part/header.php';
include '../../part/sidebar.php';
?>

<main class="content">

    <header class="topbar">

        <div>
            <h1>Tambah Mata Kuliah</h1>
            <p>Tambahkan data mata kuliah baru.</p>
        </div>

        <div class="user-badge">
            <?= $_SESSION['username']; ?>
        </div>

    </header>

    <div class="form-card">

        <form action="proses/tambah.php" method="POST">

            <div class="form-grid">

                <div class="form-group">

                    <label>Jurusan ID</label>

                    <input 
                        type="number"
                        name="jurusan_id"
                        placeholder="Masukkan ID jurusan"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Kode Mata Kuliah</label>

                    <input 
                        type="text"
                        name="kode_matkul"
                        placeholder="Contoh: IF101"
                        required
                    >

                </div>

                <div class="form-group full">

                    <label>Nama Mata Kuliah</label>

                    <input 
                        type="text"
                        name="nama_matkul"
                        placeholder="Masukkan nama mata kuliah"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>SKS</label>

                    <input 
                        type="number"
                        name="sks"
                        placeholder="Jumlah SKS"
                        required
                    >

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
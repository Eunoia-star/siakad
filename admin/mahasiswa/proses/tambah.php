<?php
include "../../auth/cek_login.php";
checkRole('super_admin');

include "../../config/koneksi.php";
include "../../part/header.php";
include "../../part/sidebar.php";

$jurusan = mysqli_query($conn, "
    SELECT j.*, f.nama_fakultas
    FROM jurusan j
    LEFT JOIN fakultas f ON j.fakultas_id = f.id
    ORDER BY f.nama_fakultas ASC, j.nama_jurusan ASC
");

$dosen = mysqli_query($conn, "SELECT id, nama FROM dosen ORDER BY nama ASC");
?>

<main class="content">

    <header class="topbar">
        <div>
            <h1>Tambah Mahasiswa</h1>
            <p>Tambah data mahasiswa dan buat akun login otomatis.</p>
        </div>

        <div class="user-badge">
            <?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
        </div>
    </header>

    <section class="form-card">
        <form action="proses/tambah.php" method="POST" enctype="multipart/form-data" class="form-grid">

            <div class="form-group">
                <label>NIM</label>
                <input type="text" name="nim" placeholder="Kosongkan jika ingin generate otomatis">
                <small>Jika dikosongkan, NIM dibuat otomatis berdasarkan angkatan dan jurusan.</small>
            </div>

            <div class="form-group">
                <label>Nama Mahasiswa <span>*</span></label>
                <input type="text" name="nama" required placeholder="Masukkan nama mahasiswa">
            </div>

            <div class="form-group">
                <label>Jurusan <span>*</span></label>
                <select name="jurusan_id" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <?php while ($j = mysqli_fetch_assoc($jurusan)) { ?>
                        <option value="<?= $j['id']; ?>">
                            <?= htmlspecialchars($j['nama_jurusan']); ?> - <?= htmlspecialchars($j['nama_fakultas'] ?? '-'); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Prodi <span>*</span></label>
                <input type="text" name="prodi" required placeholder="Contoh: Sistem Informasi">
            </div>

            <div class="form-group">
                <label>Angkatan <span>*</span></label>
                <input type="number" name="angkatan" required min="2000" max="2099" placeholder="Contoh: 2024">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="nama@email.com">
            </div>

            <div class="form-group">
                <label>Dosen PA</label>
                <select name="dosen_pa_id">
                    <option value="">-- Belum Dipilih --</option>
                    <?php while ($d = mysqli_fetch_assoc($dosen)) { ?>
                        <option value="<?= $d['id']; ?>"><?= htmlspecialchars($d['nama']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Foto</label>
                <input type="file" name="foto" accept="image/*">
            </div>

            <div class="form-group form-full">
                <label>Alamat</label>
                <textarea name="alamat" rows="4" placeholder="Masukkan alamat mahasiswa"></textarea>
            </div>

            <div class="form-action form-full">
                <a href="index.php" class="btn-secondary">Kembali</a>
                <button type="submit" class="btn-primary">Simpan Mahasiswa</button>
            </div>

        </form>
    </section>

</main>

<?php include "../../part/footer.php"; ?>

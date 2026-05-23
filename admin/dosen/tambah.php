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
?>

<main class="content">
    <header class="topbar">
        <div>
            <h1>Tambah Dosen</h1>
            <p>Tambah data dosen dan buat akun login otomatis.</p>
        </div>
        <div class="user-badge"><?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></div>
    </header>

    <section class="form-card">
        <form action="proses/tambah.php" method="POST" enctype="multipart/form-data" class="form-grid">

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
                <label>NIDN <span>*</span></label>
                <input type="text" name="nidn" required placeholder="Masukkan NIDN">
            </div>

            <div class="form-group">
                <label>NIP</label>
                <input type="text" name="nip" placeholder="Masukkan NIP jika ada">
                <small>Username login dosen memakai NIP. Jika NIP kosong, username memakai NIDN.</small>
            </div>

            <div class="form-group">
                <label>Nama Dosen <span>*</span></label>
                <input type="text" name="nama" required placeholder="Masukkan nama dosen">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="dosen@email.com">
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" placeholder="08xxxxxxxxxx">
            </div>

            <div class="form-group">
                <label>Foto</label>
                <input type="file" name="foto" accept="image/*">
            </div>

            <div class="form-group form-full">
                <label>Alamat</label>
                <textarea name="alamat" rows="4" placeholder="Masukkan alamat dosen"></textarea>
            </div>

            <div class="form-action form-full">
                <a href="index.php" class="btn-secondary">Kembali</a>
                <button type="submit" class="btn-primary">Simpan Dosen</button>
            </div>
        </form>
    </section>
</main>

<?php include "../../part/footer.php"; ?>

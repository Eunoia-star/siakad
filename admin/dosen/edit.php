<?php
include "../../auth/cek_login.php";
checkRole('super_admin');

include "../../config/koneksi.php";
include "../../part/header.php";
include "../../part/sidebar.php";

function aman($data)
{
    return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$query = mysqli_query($conn, "SELECT * FROM dosen WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data dosen tidak ditemukan'); window.location.href='index.php';</script>";
    exit;
}

$jurusan = mysqli_query($conn, "
    SELECT j.*, f.nama_fakultas
    FROM jurusan j
    LEFT JOIN fakultas f ON j.fakultas_id = f.id
    ORDER BY f.nama_fakultas ASC, j.nama_jurusan ASC
");

$username_lama = !empty($data['nip']) ? $data['nip'] : $data['nidn'];
?>

<main class="content">
    <header class="topbar">
        <div>
            <h1>Edit Dosen</h1>
            <p>Perbarui data dosen dan akun login.</p>
        </div>
        <div class="user-badge"><?= aman($_SESSION['username'] ?? 'Admin'); ?></div>
    </header>

    <section class="form-card">
        <form action="proses/edit.php" method="POST" enctype="multipart/form-data" class="form-grid">
            <input type="hidden" name="id" value="<?= $data['id']; ?>">
            <input type="hidden" name="foto_lama" value="<?= aman($data['foto']); ?>">
            <input type="hidden" name="username_lama" value="<?= aman($username_lama); ?>">

            <div class="form-group">
                <label>Jurusan <span>*</span></label>
                <select name="jurusan_id" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <?php while ($j = mysqli_fetch_assoc($jurusan)) { ?>
                        <option value="<?= $j['id']; ?>" <?= $j['id'] == $data['jurusan_id'] ? 'selected' : ''; ?>>
                            <?= aman($j['nama_jurusan']); ?> - <?= aman($j['nama_fakultas']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>NIDN <span>*</span></label>
                <input type="text" name="nidn" value="<?= aman($data['nidn']); ?>" required>
            </div>

            <div class="form-group">
                <label>NIP</label>
                <input type="text" name="nip" value="<?= aman($data['nip']); ?>">
            </div>

            <div class="form-group">
                <label>Nama Dosen <span>*</span></label>
                <input type="text" name="nama" value="<?= aman($data['nama']); ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= aman($data['email']); ?>">
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" value="<?= aman($data['no_hp']); ?>">
            </div>

            <div class="form-group">
                <label>Foto Baru</label>
                <input type="file" name="foto" accept="image/*">
                <?php if (!empty($data['foto'])) { ?>
                    <small>Foto saat ini: <?= aman($data['foto']); ?></small>
                <?php } ?>
            </div>

            <div class="form-group form-full">
                <label>Alamat</label>
                <textarea name="alamat" rows="4"><?= aman($data['alamat']); ?></textarea>
            </div>

            <div class="form-action form-full">
                <a href="index.php" class="btn-secondary">Kembali</a>
                <button type="submit" class="btn-primary">Update Dosen</button>
            </div>
        </form>
    </section>
</main>

<?php include "../../part/footer.php"; ?>

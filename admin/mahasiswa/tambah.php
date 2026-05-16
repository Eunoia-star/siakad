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

// Ambil data jurusan untuk dropdown
$jurusan_list = mysqli_query($conn, "SELECT * FROM jurusan ORDER BY nama_jurusan ASC");

// Ambil data dosen untuk dropdown dosen PA
$dosen_list = mysqli_query($conn, "SELECT * FROM dosen ORDER BY nama ASC");

?>

<main class="content">

    <header class="topbar">
        <div>
            <h1>Tambah Mahasiswa</h1>
            <p>Isi form berikut untuk menambahkan data mahasiswa baru.</p>
        </div>

        <div class="user-badge">
            <?= aman($_SESSION['username']); ?>
        </div>
    </header>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span><?= $_SESSION['error']; ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <section class="table-card">

        <div class="table-header">
            <div>
                <h3>Form Tambah Mahasiswa</h3>
                <p>Kolom bertanda <span style="color:#ef4444">*</span> wajib diisi</p>
            </div>

            <a href="index.php" class="btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>
        </div>

        <form action="proses/tambah.php" method="POST" enctype="multipart/form-data" class="form-grid">

            <!-- NIM -->
            <div class="form-group">
                <label>NIM <span class="required">*</span></label>
                <input type="text" name="nim" class="form-control"
                    placeholder="Contoh: 12345678"
                    value="<?= aman($_SESSION['old']['nim'] ?? '') ?>" required>
            </div>

            <!-- Nama -->
            <div class="form-group">
                <label>Nama Lengkap <span class="required">*</span></label>
                <input type="text" name="nama" class="form-control"
                    placeholder="Nama lengkap mahasiswa"
                    value="<?= aman($_SESSION['old']['nama'] ?? '') ?>" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" class="form-control"
                    placeholder="email@example.com"
                    value="<?= aman($_SESSION['old']['email'] ?? '') ?>" required>
            </div>

            <!-- No HP -->
            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" class="form-control"
                    placeholder="08xxxxxxxxxx"
                    value="<?= aman($_SESSION['old']['no_hp'] ?? '') ?>">
            </div>

            <!-- Jenis Kelamin -->
            <div class="form-group">
                <label>Jenis Kelamin <span class="required">*</span></label>
                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="L" <?= (($_SESSION['old']['jenis_kelamin'] ?? '') == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="P" <?= (($_SESSION['old']['jenis_kelamin'] ?? '') == 'P') ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>

            <!-- Tanggal Lahir -->
            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control"
                    value="<?= aman($_SESSION['old']['tanggal_lahir'] ?? '') ?>">
            </div>

            <!-- Prodi -->
            <div class="form-group">
                <label>Program Studi <span class="required">*</span></label>
                <input type="text" name="prodi" class="form-control"
                    placeholder="Contoh: Teknik Informatika"
                    value="<?= aman($_SESSION['old']['prodi'] ?? '') ?>" required>
            </div>

            <!-- Angkatan -->
            <div class="form-group">
                <label>Angkatan <span class="required">*</span></label>
                <input type="number" name="angkatan" class="form-control"
                    placeholder="Contoh: 2023" min="2000" max="2099"
                    value="<?= aman($_SESSION['old']['angkatan'] ?? date('Y')) ?>" required>
            </div>

            <!-- Jurusan -->
            <div class="form-group">
                <label>Jurusan</label>
                <select name="jurusan_id" class="form-control">
                    <option value="">-- Pilih Jurusan --</option>
                    <?php while ($j = mysqli_fetch_assoc($jurusan_list)): ?>
                        <option value="<?= $j['id'] ?>"
                            <?= (($_SESSION['old']['jurusan_id'] ?? '') == $j['id']) ? 'selected' : '' ?>>
                            <?= aman($j['nama_jurusan']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Dosen PA -->
            <div class="form-group">
                <label>Dosen PA</label>
                <select name="dosen_pa_id" class="form-control">
                    <option value="">-- Pilih Dosen PA --</option>
                    <?php while ($d = mysqli_fetch_assoc($dosen_list)): ?>
                        <option value="<?= $d['id'] ?>"
                            <?= (($_SESSION['old']['dosen_pa_id'] ?? '') == $d['id']) ? 'selected' : '' ?>>
                            <?= aman($d['nama']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label>Password <span class="required">*</span></label>
                <div class="input-password">
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Minimal 6 karakter" required>
                    <i class="bi bi-eye toggle-pw" onclick="togglePw('password', this)"></i>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="form-group">
                <label>Konfirmasi Password <span class="required">*</span></label>
                <div class="input-password">
                    <input type="password" name="konfirmasi_password" id="konfirmasi_password"
                        class="form-control" placeholder="Ulangi password" required>
                    <i class="bi bi-eye toggle-pw" onclick="togglePw('konfirmasi_password', this)"></i>
                </div>
                <small id="pw-msg"></small>
            </div>

            <!-- Alamat -->
            <div class="form-group full-width">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="3"
                    placeholder="Alamat lengkap mahasiswa"><?= aman($_SESSION['old']['alamat'] ?? '') ?></textarea>
            </div>

            <!-- Foto -->
            <div class="form-group full-width">
                <label>Foto Mahasiswa</label>
                <input type="file" name="foto" class="form-control"
                    accept="image/jpg,image/jpeg,image/png"
                    onchange="previewFoto(this)">
                <small>Format: JPG, PNG. Maks: 2MB</small>
                <div id="preview-wrap" style="display:none; margin-top:8px">
                    <img id="preview-img" src="#" alt="Preview"
                        style="max-height:140px; border-radius:8px; border:1px solid #e2e8f0">
                </div>
            </div>

            <!-- Tombol -->
            <div class="form-actions full-width">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="index.php" class="btn-secondary">
                    <i class="bi bi-x-lg"></i> Batal
                </a>
            </div>

        </form>

        <?php if (isset($_SESSION['old'])) unset($_SESSION['old']); ?>

    </section>

</main>

<style>
.alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 8px;
    margin: 0 0 16px 0;
    font-size: 0.875rem;
}
.alert-error {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    padding: 8px 0 0 0;
}
.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.form-group.full-width {
    grid-column: 1 / -1;
}
.form-group label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}
.required { color: #ef4444; }
.form-control {
    padding: 9px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.875rem;
    font-family: inherit;
    outline: none;
    width: 100%;
    box-sizing: border-box;
    background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}
textarea.form-control { resize: vertical; }
.input-password { position: relative; }
.input-password .form-control { padding-right: 38px; }
.toggle-pw {
    position: absolute;
    right: 11px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #94a3b8;
    font-size: 1rem;
    transition: color 0.15s;
}
.toggle-pw:hover { color: #374151; }
.form-group small { font-size: 0.78rem; color: #94a3b8; }
.form-actions {
    display: flex;
    gap: 10px;
    padding-top: 4px;
}
</style>

<script>
function previewFoto(input) {
    const wrap = document.getElementById('preview-wrap');
    const img  = document.getElementById('preview-img');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; wrap.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}

function togglePw(id, icon) {
    const f = document.getElementById(id);
    const show = f.type === 'password';
    f.type = show ? 'text' : 'password';
    icon.classList.toggle('bi-eye', !show);
    icon.classList.toggle('bi-eye-slash', show);
}

document.getElementById('konfirmasi_password').addEventListener('input', function () {
    const msg = document.getElementById('pw-msg');
    if (!this.value) { msg.textContent = ''; return; }
    const match = this.value === document.getElementById('password').value;
    msg.textContent = match ? '✓ Password cocok' : '✗ Password tidak cocok';
    msg.style.color  = match ? '#16a34a' : '#dc2626';
});

document.querySelector('form').addEventListener('submit', function (e) {
    if (document.getElementById('password').value !== document.getElementById('konfirmasi_password').value) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok!');
    }
});
</script>

<?php include "../../part/footer.php"; ?>

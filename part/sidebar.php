<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$role = $_SESSION['role'] ?? '';
?>

<aside class="sidebar">

    <div class="brand">
        SIAKAD
    </div>

    <nav class="sidebar-menu">

        <?php if ($role == 'super_admin') { ?>

            <a href="../admin/dashboard.php" class="menu-item" >
                <i class="bi bi-grid-fill"></i>
                Dashboard
            </a>

            <a href="../admin/mahasiswa/index.php" class="menu-item">
                <i class="bi bi-mortarboard-fill"></i>
                Mahasiswa
            </a>

            <a href="../admin/dosen.php" class="menu-item">
                <i class="bi bi-person-badge-fill"></i>
                Dosen
            </a>

            <a href="../admin/mata_kuliah.php" class="menu-item">
                <i class="bi bi-book-fill"></i>
                Mata Kuliah
            </a>

            <a href="../admin/jadwal.php" class="menu-item">
                <i class="bi bi-calendar-week-fill"></i>
                Jadwal Perkuliahan
            </a>

        <?php } ?>

        <?php if ($role == 'dosen') { ?>

            <a href="../dosen/dashboard.php" class="menu-item">
                <i class="bi bi-grid-fill"></i>
                Dashboard
            </a>

            <a href="../dosen/profil.php" class="menu-item">
                <i class="bi bi-person-circle"></i>
                Profil Dosen
            </a>

            <a href="../dosen/mahasiswa.php" class="menu-item">
                <i class="bi bi-people-fill"></i>
                Mahasiswa
            </a>

            <div class="menu-group">
                <span>
                    <i class="bi bi-journal-bookmark-fill"></i>
                    Perkuliahan
                </span>

                <a href="../dosen/mata_kuliah.php">
                    Mata Kuliah
                </a>
            </div>

        <?php } ?>

        <?php if ($role == 'mahasiswa') { ?>

            <a href="../mahasiswa/dashboard.php" class="menu-item">
                <i class="bi bi-grid-fill"></i>
                Dashboard
            </a>

            <a href="../mahasiswa/profil.php" class="menu-item">
                <i class="bi bi-person-circle"></i>
                Profil Mahasiswa
            </a>

            <div class="menu-group">
                <span>
                    <i class="bi bi-journal-bookmark-fill"></i>
                    Perkuliahan
                </span>

                <a href="../mahasiswa/krs.php">KRS</a>
                <a href="../mahasiswa/jadwal.php">Jadwal Perkuliahan</a>
                <a href="../mahasiswa/khs.php">KHS</a>
            </div>

        <?php } ?>

        <a href="../auth/logout.php" class="menu-item logout">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </a>

    </nav>

</aside>
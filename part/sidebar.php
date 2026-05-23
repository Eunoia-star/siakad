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

        <!-- =========================
             SUPER ADMIN
        ========================== -->
        <?php if ($role == 'super_admin') { ?>

            <a href="/siakad/admin/dashboard.php" class="menu-item">
                <i class="bi bi-grid-fill"></i>
                Dashboard
            </a>

            <a href="/siakad/admin/mahasiswa/index.php" class="menu-item">
                <i class="bi bi-mortarboard-fill"></i>
                Mahasiswa
            </a>

            <a href="/siakad/admin/dosen/index.php" class="menu-item">
                <i class="bi bi-person-badge-fill"></i>
                Dosen
            </a>

            <a href="/siakad/admin/mata_kuliah/index.php" class="menu-item">
                <i class="bi bi-book-fill"></i>
                Mata Kuliah
            </a>

            <a href="/siakad/admin/jadwal/index.php" class="menu-item">
                <i class="bi bi-calendar-week-fill"></i>
                Jadwal Perkuliahan
            </a>

        <?php } ?>



        <!-- =========================
             DOSEN
        ========================== -->
        <?php if ($role == 'dosen') { ?>

            <a href="/siakad/dosen/dashboard.php" class="menu-item">
                <i class="bi bi-grid-fill"></i>
                Dashboard
            </a>

            <a href="/siakad/dosen/profil.php" class="menu-item">
                <i class="bi bi-person-circle"></i>
                Profil Dosen
            </a>

            <a href="/siakad/dosen/mahasiswa/index.php" class="menu-item">
                <i class="bi bi-people-fill"></i>
                Mahasiswa
            </a>

            <div class="menu-group">

                <span>
                    <i class="bi bi-journal-bookmark-fill"></i>
                    Perkuliahan
                </span>

                <a href="/siakad/dosen/mata_kuliah/index.php">
                    Mata Kuliah
                </a>

            </div>

        <?php } ?>



        <!-- =========================
             MAHASISWA
        ========================== -->
        <?php if ($role == 'mahasiswa') { ?>

            <a href="/siakad/mahasiswa/dashboard.php" class="menu-item">
                <i class="bi bi-grid-fill"></i>
                Dashboard
            </a>

            <a href="/siakad/mahasiswa/profil.php" class="menu-item">
                <i class="bi bi-person-circle"></i>
                Profil Mahasiswa
            </a>

            <div class="menu-group">

                <span>
                    <i class="bi bi-journal-bookmark-fill"></i>
                    Perkuliahan
                </span>

                <a href="/siakad/mahasiswa/krs/index.php">
                    KRS
                </a>

                <a href="/siakad/mahasiswa/jadwal/index.php">
                    Jadwal Perkuliahan
                </a>

                <a href="/siakad/mahasiswa/khs/index.php">
                    KHS
                </a>

            </div>

        <?php } ?>



        <!-- =========================
             LOGOUT
        ========================== -->
        <a href="/siakad/auth/logout.php" class="menu-item logout">

            <i class="bi bi-box-arrow-right"></i>

            Logout

        </a>

    </nav>

</aside>
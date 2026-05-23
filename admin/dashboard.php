<?php
include_once "../auth/cek_login.php";
checkRole('super_admin');

include_once "../part/header.php";
include_once "../part/sidebar.php";
?>

<style>
    .info-card {
        text-decoration: none;
        color: inherit;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
    }

    .info-card:visited,
    .info-card:active,
    .info-card:focus {
        color: inherit;
        text-decoration: none;
    }
</style>

<main class="content">

    <header class="topbar">
        <div>
            <h1>Dashboard Super Admin</h1>
            <p>Kelola data akademik seluruh sistem.</p>
        </div>

        <div class="user-badge">
            <?= htmlspecialchars($_SESSION['username'] ?? 'admin', ENT_QUOTES, 'UTF-8'); ?>
        </div>
    </header>

    <section class="card-grid">

        <a href="mahasiswa/index.php" class="info-card">
            <i class="bi bi-mortarboard-fill"></i>
            <div>
                <h3>Mahasiswa</h3>
                <p>Kelola data mahasiswa</p>
            </div>
        </a>

        <a href="dosen/index.php" class="info-card">
            <i class="bi bi-person-badge-fill"></i>
            <div>
                <h3>Dosen</h3>
                <p>Kelola data dosen</p>
            </div>
        </a>

        <a href="mata_kuliah/index.php" class="info-card">
            <i class="bi bi-book-fill"></i>
            <div>
                <h3>Mata Kuliah</h3>
                <p>Kelola mata kuliah dan jadwal</p>
            </div>
        </a>

    </section>

</main>

<?php include_once "../part/footer.php"; ?>
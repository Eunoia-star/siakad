<?php include "../part/header.php"; ?>
<?php include "../part/sidebar.php"; ?>

<main class="content">

    <header class="topbar">
        <div>
            <h1>Dashboard Super Admin</h1>
            <p>Kelola data akademik seluruh sistem.</p>
        </div>

        <div class="user-badge">
            <?= $_SESSION['username']; ?>
        </div>
    </header>

    <section class="card-grid">

        <div class="info-card">
            <i class="bi bi-mortarboard-fill"></i>
            <div>
                <h3>Mahasiswa</h3>
                <p>Kelola data mahasiswa</p>
            </div>
        </div>

        <div class="info-card">
            <i class="bi bi-person-badge-fill"></i>
            <div>
                <h3>Dosen</h3>
                <p>Kelola data dosen</p>
            </div>
        </div>

        <div class="info-card">
            <i class="bi bi-book-fill"></i>
            <div>
                <h3>Mata Kuliah</h3>
                <p>Kelola mata kuliah dan jadwal</p>
            </div>
        </div>

    </section>

</main>

<?php include "../part/footer.php"; ?>
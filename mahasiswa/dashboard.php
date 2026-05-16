<?php include "../part/header.php"; ?>
<?php include "../part/sidebar.php"; ?>

<main class="content">

    <header class="topbar">
        <div>
            <h1>Dashboard Mahasiswa</h1>
            <p>Informasi akademik mahasiswa.</p>
        </div>

        <div class="user-badge">
            <?= $_SESSION['username']; ?>
        </div>
    </header>

    <section class="profile-card">

        <div class="avatar">
            <i class="bi bi-person-fill"></i>
        </div>

        <div>
            <h2>Selamat Datang</h2>
            <p>Dashboard ini akan menampilkan profil, KRS, jadwal perkuliahan, dan KHS.</p>
        </div>

    </section>

</main>

<?php include "../part/footer.php"; ?>
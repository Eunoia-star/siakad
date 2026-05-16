<?php include "../part/header.php"; ?>
<?php include "../part/sidebar.php"; ?>

<main class="content">

    <header class="topbar">
        <div>
            <h1>Dashboard Dosen</h1>
            <p>Kelola perkuliahan dan mahasiswa bimbingan.</p>
        </div>

        <div class="user-badge">
            <?= $_SESSION['username']; ?>
        </div>
    </header>

    <section class="card-grid">

        <div class="info-card">
            <i class="bi bi-person-circle"></i>
            <div>
                <h3>Profil Dosen</h3>
                <p>Lihat dan ubah data profil</p>
            </div>
        </div>

        <div class="info-card">
            <i class="bi bi-book-fill"></i>
            <div>
                <h3>Mata Kuliah</h3>
                <p>Lihat kelas yang diampu</p>
            </div>
        </div>

        <div class="info-card">
            <i class="bi bi-people-fill"></i>
            <div>
                <h3>Mahasiswa</h3>
                <p>Lihat mahasiswa PA</p>
            </div>
        </div>

    </section>

</main>

<?php include "../part/footer.php"; ?>
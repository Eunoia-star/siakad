<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIAKAD</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="login-body">

    <div class="login-wrapper">

        <section class="login-panel">

            <div class="login-left">

                <div class="login-logo">
                    <div class="logo-icon">
                        <i class="bi bi-book"></i>
                    </div>

                    <div>
                        <h2>SIAKAD</h2>
                        <p>Sistem Informasi Akademik</p>
                    </div>
                </div>

                <div class="login-badge">
                    <i class="bi bi-shield-check"></i>
                    Portal Akademik
                </div>

                <h1>Selamat Datang Kembali</h1>

                <p class="login-desc">
                    Masuk untuk mengakses sistem akademik Universitas Riau.
                </p>

                <form action="auth/login_proses.php" method="POST">

                    <label>Username</label>
                    <div class="login-input">
                        <i class="bi bi-person"></i>
                        <input
                            type="text"
                            name="login"
                            placeholder="Username/Email"
                            required>
                    </div>

                    <label>Password</label>
                    <div class="login-input">
                        <i class="bi bi-lock"></i>
                        <input
                            type="password"
                            name="password"
                            placeholder="Masukkan password"
                            required>
                    </div>

                    <button type="submit" class="login-button">
                        <i class="bi bi-lock"></i>
                        Masuk
                    </button>

                </form>

                <p class="login-footer">
                    © 2026 Sistem Informasi Akademik
                </p>

            </div>

            <div class="login-right">

            </div>

        </section>

    </div>

</body>
</html>
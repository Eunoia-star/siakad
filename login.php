<!doctype html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Login SIAKAD</title>

    <link rel="stylesheet"
    href="assets/css/style.css">

</head>

<body class="login-body">

    <div class="login-card">

        <h1>SIAKAD</h1>

        <p>
            Sistem Informasi Akademik
        </p>

        <form
        action="auth/login_proses.php"
        method="POST">

            <input
            type="text"
            name="login"
            placeholder="Email / Username"
            required>

            <input
            type="password"
            name="password"
            placeholder="Password"
            required>

            <button type="submit">
                Login
            </button>

        </form>

    </div>

</body>
</html>
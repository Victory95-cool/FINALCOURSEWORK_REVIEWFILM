<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Sign In(IMDB refernce)</title>
    <link rel="stylesheet" href="../../admin_style.css">

</head>

<body class="login-page">

    <div class="form-wrapper">

        <div class="imdb-logo-header">
            <button class="btn-back" onclick="window.history.back()">⬅ Back</button>
            <span class="imdb-logo-text">RMW</span>
            <span class="admin-text">Admin</span>
        </div>

        <form method="post" action="" autocomplete="off">
            <h2>Sign in</h2>

            <?php if (!empty($error)): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>

            <label for="username">Name</label>
            <input type="text" id="username" name="admin_user" required autocomplete="new-password">

            <label for="password">Password</label>
            <div class="password-wrapper">
                <input type="password" name="admin_password" id="password" required autocomplete="new-password">
                <span onclick="togglePassword()" class="toggle-password" id="eye-icon">👁</span>
            </div>

            <input type="submit" value="Sign In" class="btn-send">
        </form>
    </div>


    <script>
        function togglePassword() {
            const passInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eye-icon");
            if (passInput.type === "password") {
                passInput.type = "text";
                eyeIcon.textContent = "🙈";
            } else {
                passInput.type = "password";
                eyeIcon.textContent = "👁";
            }
        }
    </script>

</body>

</html>
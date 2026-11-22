<section class="auth-page">
    <div class="auth-heading">
        <span class="auth-logo">RMW</span>
        <span class="auth-heading__divider" aria-hidden="true"></span>
        <h2>Reviewer Sign In</h2>
    </div>

    <?php if ($error !== ''): ?>
        <p class="form-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form class="auth-card auth-card--signin" method="post" action="signin.php" autocomplete="on">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf->token(), ENT_QUOTES, 'UTF-8') ?>">
        <label for="identifier">Username or email</label>
        <input type="text" id="identifier" name="identifier" autocomplete="username" required>

        <label for="password">Password</label>
        <div class="password-wrapper">
            <input type="password" name="password" id="password" autocomplete="current-password" required>
            <span onclick="togglePassword()" class="toggle-password" id="eye-icon">👁</span>
        </div>

        <button type="submit" class="auth-submit">Sign In</button>
        <p class="auth-help"><a href="password_reset.php">Forgot password?</a></p>
    </form>

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
</section>
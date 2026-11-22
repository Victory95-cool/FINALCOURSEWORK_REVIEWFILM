<section class="auth-page">
    <div class="auth-heading">
        <span class="auth-logo">RMW</span>
        <span class="auth-heading__divider" aria-hidden="true"></span>
        <h2>Password Reset</h2>
    </div>

    <?php if ($error !== ''): ?>
        <p class="form-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <?php if ($message !== ''): ?>
        <p class="auth-message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <?php if ($resetLink !== ''): ?>
        <p class="auth-message">Reset link (valid for one hour):
            <a href="<?= htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8') ?>">Reset password</a>
        </p>
    <?php endif; ?>

    <?php if ($reset): ?>
        <form class="auth-card auth-card--signin" method="post" action="password_reset.php">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf->token(), ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
            <label for="new_password">New password (at least 8 characters)</label>
            <div class="password-wrapper">
                <input type="password" name="new_password" id="new_password" minlength="8" autocomplete="new-password" required>
                <span onclick="togglePassword('new_password', this)" class="toggle-password">👁</span>
            </div>

            <label for="password_confirmation">Confirm new password</label>
            <div class="password-wrapper">
                <input type="password" name="password_confirmation" id="password_confirmation" minlength="8" autocomplete="new-password" required>
                <span onclick="togglePassword('password_confirmation', this)" class="toggle-password">👁</span>
            </div>

            <button type="submit" class="auth-submit">Reset Password</button>
        </form>
    <?php elseif ($token === ''): ?>
        <form class="auth-card auth-card--signin" method="post" action="password_reset.php">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf->token(), ENT_QUOTES, 'UTF-8') ?>">
            <label for="email">Account email</label>
            <input type="email" id="email" name="email" autocomplete="email" required>
            <button type="submit" class="auth-submit">Request Reset Link</button>
        </form>
    <?php endif; ?>

    <p class="auth-help"><a href="signin.php">Back to Sign In</a></p>
    <script>
        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                iconElement.textContent = "🙈";
            } else {
                input.type = "password";
                iconElement.textContent = "👁";
            }
        }
    </script>
</section>
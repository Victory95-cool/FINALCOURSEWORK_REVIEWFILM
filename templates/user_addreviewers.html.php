<section class="auth-page">
    <div class="auth-heading">
        <span class="auth-logo">RMW</span>
        <span class="auth-heading__divider" aria-hidden="true"></span>
        <h2>Create Reviewer Account</h2>
    </div>

    <?php if (!empty($error)): ?>
        <p class="form-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form class="auth-card auth-card--signup" action="addreviewers.php" method="post" autocomplete="on">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf->token(), ENT_QUOTES, 'UTF-8') ?>">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" maxlength="100" autocomplete="username" required>

        <label for="phone_number">Phone Number</label>
        <input type="text" id="phone_number" name="phone_number" pattern="^\d{10}$" title="Please enter a valid 10-digit phone number" autocomplete="tel" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email"
            pattern="[a-zA-Z0-9._%+-]+@gmail\.com$"
            title="Please enter a valid @gmail.com address"
            autocomplete="email"
            required>

        <label for="password">Password (at least 8 characters)</label>
        <div class="password-wrapper">
            <input type="password" name="password" id="password" minlength="8" autocomplete="new-password" required>
            <!-- Truyền id của input và context (this) vào hàm -->
            <span onclick="togglePassword('password', this)" class="toggle-password">👁</span>
        </div>

        <label for="password_confirmation">Confirm Password</label>
        <div class="password-wrapper">
            <input type="password" name="password_confirmation" id="password_confirmation" minlength="8" autocomplete="new-password" required>
            <!-- Truyền id của input confirm và context (this) vào hàm -->
            <span onclick="togglePassword('password_confirmation', this)" class="toggle-password">👁</span>
        </div>

        <button type="submit" class="auth-submit">Sign Up</button>
    </form>

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
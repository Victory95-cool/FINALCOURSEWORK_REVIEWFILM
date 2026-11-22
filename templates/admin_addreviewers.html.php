<h2>Add New Reviewer</h2>
<?php if ($error !== ''): ?><p class="form-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form action="addreviewers.php" method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf->token(), ENT_QUOTES, 'UTF-8') ?>">
    <label>Username</label>
    <input type="text" name="username" maxlength="100" value="<?= htmlspecialchars($formValues['username'], ENT_QUOTES, 'UTF-8') ?>" required>

    <label>Phone Number</label>
    <input type="text" name="phone_number" pattern="[0-9]{10}" maxlength="10" value="<?= htmlspecialchars($formValues['phone_number'], ENT_QUOTES, 'UTF-8') ?>" title="Please enter a valid 10-digit phone number" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($formValues['email'], ENT_QUOTES, 'UTF-8') ?>"
        pattern="[a-zA-Z0-9._%+-]+@gmail\.com$"
        title="Please enter a valid @gmail.com address"
        required>

    <input type="submit" value="Add Reviewer" class="btn-add">
</form>
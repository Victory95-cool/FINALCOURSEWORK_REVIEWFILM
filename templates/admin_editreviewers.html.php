<h2>Edit Reviewer: <?= htmlspecialchars($reviewer['username'], ENT_QUOTES, 'UTF-8') ?></h2>
<?php if ($error !== ''): ?><p class="form-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form action="" method="post">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf->token(), ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="id" value="<?= $reviewer['id'] ?>">

    <label>Username</label>
    <input type="text" name="username" maxlength="100" value="<?= htmlspecialchars($reviewer['username'], ENT_QUOTES, 'UTF-8') ?>" required>

    <label>Phone Number</label>
    <input type="text" name="phone_number" pattern="[0-9]{10}" maxlength="10" value="<?= htmlspecialchars($reviewer['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($reviewer['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

    <input type="submit" value="Update Reviewer" class="btn-update">
</form>
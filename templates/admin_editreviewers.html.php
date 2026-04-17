<h2>Edit Reviewer: <?= htmlspecialchars($reviewer['username'], ENT_QUOTES, 'UTF-8') ?></h2>
<form action="" method="post">
    <input type="hidden" name="id" value="<?= $reviewer['id'] ?>">

    <label>Username</label>
    <input type="text" name="username" value="<?= htmlspecialchars($reviewer['username'], ENT_QUOTES, 'UTF-8') ?>" required>

    <label>Phone Number</label>
    <input type="text" name="phone_number" value="<?= htmlspecialchars($reviewer['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($reviewer['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

    <input type="submit" value="Update Reviewer" class="btn-update">
</form>
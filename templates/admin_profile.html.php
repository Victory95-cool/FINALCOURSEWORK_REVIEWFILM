<section class="profile-page admin-profile-page">
    <?php if ($error !== ''): ?><p class="profile-feedback profile-feedback--error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <?php if ($notice !== ''): ?><p class="profile-feedback" role="status"><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <div class="profile-hero">
        <img class="profile-avatar" src="<?= htmlspecialchars(!empty($profile['avatar_path']) ? '../' . $profile['avatar_path'] : '../images/Avatar.jpg', ENT_QUOTES, 'UTF-8') ?>" alt="Admin avatar">
        <div class="profile-identity">
            <h2><?= htmlspecialchars($profile['username'], ENT_QUOTES, 'UTF-8') ?></h2>
            <p><?= htmlspecialchars($profile['email'] ?? 'No email set', ENT_QUOTES, 'UTF-8') ?></p>
            <p>Admin · Joined <?= htmlspecialchars(date('M Y', strtotime($profile['created_at'])), ENT_QUOTES, 'UTF-8') ?></p>
            <button type="button" class="profile-edit-button" id="open-admin-profile-edit">Edit</button>
        </div>
    </div>

    <dialog class="profile-edit-dialog" id="admin-profile-edit-dialog" aria-labelledby="admin-profile-edit-title">
        <div class="profile-edit-dialog__header">
            <h3 id="admin-profile-edit-title">Edit admin profile</h3>
            <button type="button" class="profile-dialog-close" id="close-admin-profile-edit" aria-label="Close edit profile">&times;</button>
        </div>
        <form method="post" enctype="multipart/form-data" class="profile-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_profile_csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            <label for="admin-avatar">Avatar (JPG, PNG, WebP; up to 5 MB)</label>
            <input type="file" id="admin-avatar" name="avatar" accept="image/jpeg,image/png,image/webp">
            <label for="admin-username">Name</label>
            <input type="text" id="admin-username" name="username" maxlength="255" required value="<?= htmlspecialchars($formValues['username'], ENT_QUOTES, 'UTF-8') ?>">
            <label for="admin-email">Email</label>
            <input type="email" id="admin-email" name="email" required value="<?= htmlspecialchars($formValues['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <div class="profile-dialog-actions">
                <button type="button" class="profile-dialog-cancel" id="cancel-admin-profile-edit">Cancel</button>
                <button type="submit" class="profile-save-button">Save profile</button>
            </div>
        </form>
    </dialog>
</section>
<script>
    (() => {
        const dialog = document.getElementById('admin-profile-edit-dialog');
        document.getElementById('open-admin-profile-edit').addEventListener('click', () => dialog.showModal());
        document.getElementById('close-admin-profile-edit').addEventListener('click', () => dialog.close());
        document.getElementById('cancel-admin-profile-edit').addEventListener('click', () => dialog.close());
        <?php if ($editOpen): ?>dialog.showModal();<?php endif; ?>
    })();
</script>

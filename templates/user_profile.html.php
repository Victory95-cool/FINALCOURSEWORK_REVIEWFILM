<section class="profile-page">
    <?php if ($error !== ''): ?><p class="profile-feedback profile-feedback--error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <?php if ($notice !== ''): ?><p class="profile-feedback" role="status"><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <div class="profile-hero">
        <img class="profile-avatar" src="<?= htmlspecialchars($profile['avatar_path'] ?: 'images/avatars/unknown avatar.jpg', ENT_QUOTES, 'UTF-8') ?>" alt="Profile avatar">
        <div class="profile-identity">
            <h2><?= htmlspecialchars($profile['username'], ENT_QUOTES, 'UTF-8') ?></h2>
            <p><?= htmlspecialchars($profile['email'] ?? 'No email set', ENT_QUOTES, 'UTF-8') ?></p>
            <p>Joined <?= htmlspecialchars(date('M Y', strtotime($profile['created_at'])), ENT_QUOTES, 'UTF-8') ?></p>
            <span class="profile-status <?= !empty($profile['is_banned']) ? 'profile-status--banned' : '' ?>">
                Status: <?= !empty($profile['is_banned']) ? 'Banned' : 'Active' ?>
            </span>
            <button type="button" class="profile-edit-button" id="open-profile-edit">Edit</button>
        </div>
        <div class="profile-counts">
            <div><strong><?= count($reviews) ?></strong><span>Ratings</span></div>
            <div><strong><?= count($watchlistItems) ?></strong><span>Watchlist</span></div>
        </div>
    </div>

    <div class="profile-content">
        <section class="profile-panel" id="ratings">
            <h3>Ratings</h3>
            <?php if (!$reviews): ?>
                <p>You have not rated any films yet.</p>
            <?php else: ?>
                <div class="profile-film-grid">
                    <?php foreach ($reviews as $item): ?>
                        <article class="profile-film-card">
                            <a href="filmdetail.php?id=<?= (int) $item['film_id'] ?>">
                                <img src="<?= htmlspecialchars($item['poster'] ?: 'images/Avatar.jpg', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>">
                                <h4><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h4>
                            </a>
                            <p class="profile-rating">★ <?= (int) $item['rating'] ?>/5</p>
                            <p><?= htmlspecialchars(date('d M Y', strtotime($item['review_date'])), ENT_QUOTES, 'UTF-8') ?></p>
                            <p><?= nl2br(htmlspecialchars($item['review_text'], ENT_QUOTES, 'UTF-8')) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="profile-panel" id="watchlist">
            <h3>Watchlist</h3>
            <?php if (!$watchlistItems): ?>
                <p>Your watchlist is empty. Add films from their detail pages.</p>
            <?php else: ?>
                <div class="profile-film-grid">
                    <?php foreach ($watchlistItems as $item): ?>
                        <article class="profile-film-card">
                            <a href="filmdetail.php?id=<?= (int) $item['id'] ?>">
                                <img src="<?= htmlspecialchars($item['poster'] ?: 'images/Avatar.jpg', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>">
                                <h4><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h4>
                            </a>
                            <p><?= htmlspecialchars((string) $item['released_year'], ENT_QUOTES, 'UTF-8') ?></p>
                            <form method="post" action="watchlist.php">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['watchlist_csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="film_id" value="<?= (int) $item['id'] ?>">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="return_to" value="user_profile.php">
                                <button type="submit" class="profile-remove-button">Remove</button>
                            </form>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <dialog class="profile-edit-dialog" id="profile-edit-dialog" aria-labelledby="profile-edit-title">
        <div class="profile-edit-dialog__header">
            <h3 id="profile-edit-title">Edit profile</h3>
            <button type="button" class="profile-dialog-close" id="close-profile-edit" aria-label="Close edit profile">&times;</button>
        </div>
        <form method="post" enctype="multipart/form-data" class="profile-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['profile_csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            <label for="profile-avatar">Avatar (JPG, PNG, WebP; up to 5 MB)</label>
            <input type="file" id="profile-avatar" name="avatar" accept="image/jpeg,image/png,image/webp">

            <label for="profile-username">Username</label>
            <input type="text" id="profile-username" name="username" maxlength="100" required value="<?= htmlspecialchars($formValues['username'], ENT_QUOTES, 'UTF-8') ?>">

            <label for="profile-email">Email</label>
            <input type="email" id="profile-email" name="email" required value="<?= htmlspecialchars($formValues['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <label for="profile-phone">Phone number</label>

            <input type="text" id="phone_number" name="phone_number" pattern="^\d{10}$" minlength="10" maxlength="10" title="Please enter a valid 10-digit phone number" autocomplete="tel" required value="<?= htmlspecialchars($formValues['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <div class="profile-dialog-actions">
                <button type="button" class="profile-dialog-cancel" id="cancel-profile-edit">Cancel</button>
                <button type="submit" class="profile-save-button">Save profile</button>
            </div>
        </form>
    </dialog>
</section>
<script>
    (() => {
        const dialog = document.getElementById('profile-edit-dialog');
        document.getElementById('open-profile-edit').addEventListener('click', () => dialog.showModal());
        document.getElementById('close-profile-edit').addEventListener('click', () => dialog.close());
        document.getElementById('cancel-profile-edit').addEventListener('click', () => dialog.close());
        <?php if ($editOpen): ?>dialog.showModal();
    <?php endif; ?>
    })();
</script>
<?php if (!empty($error)): ?><p class="form-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf->token(), ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="id" value="<?= $review['id'] ?>">
    <label for="review_text">Edit your comment:</label>
    <textarea name="review_text" id="review_text" rows="3" cols="40" required><?= htmlspecialchars($review['review_text'], ENT_QUOTES, 'UTF-8'); ?></textarea>

    <label for="film">Reselect Your Film:</label>
    <select name="film" id="film" required>
        <?php foreach ($films as $film): ?>
            <option value="<?= htmlspecialchars($film['id'], ENT_QUOTES, 'UTF-8'); ?>"
                <?= ($film['id'] == $current_film_id) ? 'selected' : ''; ?>>

                <?= htmlspecialchars($film['title'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <div style="margin: 15px 0;">
        <label>Current Film Poster:</label>
        <?php if (!empty($review['poster'])): ?>
            <div style="margin-top: 10px;">
                <img src="<?= htmlspecialchars($review['poster'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="Film Poster"
                    style="width: 150px; border-radius: 8px; border: 1px solid var(--accent);">
            </div>
        <?php else: ?>
            <p style="color: #666; font-style: italic;">(No poster available for this film)</p>
        <?php endif; ?>
    </div>

    <label for="review_text">Reselect Your Rating:</label>
    <select name="rating">
        <option value="1" <?= ($review['rating'] == 1) ? 'selected' : '' ?>>1⭐</option>
        <option value="2" <?= ($review['rating'] == 2) ? 'selected' : '' ?>>2⭐</option>
        <option value="3" <?= ($review['rating'] == 3) ? 'selected' : '' ?>>3⭐</option>
        <option value="4" <?= ($review['rating'] == 4) ? 'selected' : '' ?>>4⭐</option>
        <option value="5" <?= ($review['rating'] == 5) ? 'selected' : '' ?>>5⭐</option>
    </select>

    <label for="meme-image">Replace meme image (JPG, PNG, or WebP; up to 5 MB):</label>
    <input type="file" id="meme-image" name="meme_image" accept="image/jpeg,image/png,image/webp">
    <?php if (!empty($review['image_path'])): ?>
        <div style="margin: 12px 0;">
            <label>Current meme image:</label>
            <img src="<?= htmlspecialchars($review['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="Current meme attached to review" style="display:block; max-width:min(100%,420px); max-height:420px; margin-top:8px;">
        </div>
    <?php endif; ?>

    <button type="submit" class="btn-update">Update Your Review</button>
</form>
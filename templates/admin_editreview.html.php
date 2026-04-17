<form action="" method="post">
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

    <div style="margin: 15px 0; display: flex; flex-direction: column;">
        <label>Current Film Poster:</label>

        <?php if (!empty($review['poster'])): ?>
            <div style="margin-top: 10px; display: flex; justify-content: center; align-items: center;">
                <img src="../<?= htmlspecialchars($review['poster'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="Film Poster"
                    style="width: 150px; border-radius: 8px; border: 2px solid var(--accent); display: block;">
            </div>
        <?php else: ?>
            <p style="color: var(--text-secondary); text-align: center;">No poster available</p>
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

    <button type="submit" class="btn-update">Update</button>
</form>
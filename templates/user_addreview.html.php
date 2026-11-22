<main>
    <h2>Add New Review</h2>
    <p>Logged in as: <strong><?= htmlspecialchars($savedName) ?></strong></p>

    <section class="form-wrapper">
        <?php if ($error !== ''): ?><p class="form-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf->token(), ENT_QUOTES, 'UTF-8') ?>">
            <label for="film">Select Film:</label>
            <select name="film" id="film" required>
                <option value="">---Select a Film---</option>
                <?php foreach ($films as $film): ?>
                    <option value="<?= htmlspecialchars($film['id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?= htmlspecialchars($film['title'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="review_text">Add a new comment:</label>
            <textarea name="review_text" id="review_text" rows="3" cols="40" required></textarea>

            <label for="meme-image">Attach a meme image (JPG, PNG, or WebP; up to 5 MB, optional):</label>
            <input type="file" id="meme-image" name="meme_image" accept="image/jpeg,image/png,image/webp">

            <label for="rating">Rating:</label>
            <select name="rating" required>
                <option value="1">1⭐</option>
                <option value="2">2⭐</option>
                <option value="3">3⭐</option>
                <option value="4">4⭐</option>
                <option value="5">5⭐</option>
            </select>

            <button type="submit" class="btn-add">Add Your Review</button>
        </form>
    </section>
</main>
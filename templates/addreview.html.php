<main>
    <h2>Add New Review</h2>
    <p>Logged in as: <strong><?= htmlspecialchars($savedName) ?></strong></p>

    <section class="form-wrapper">
        <form action="" method="post">
            <input type="hidden" name="reviewers_name" value="<?= htmlspecialchars($savedName) ?>">

            <label for="existing_reviewers">Post as (Select Name):</label>
            <select name="existing_reviewers" id="existing_reviewers">
                <option value=""><?= htmlspecialchars($savedName) ?> (Yourself)</option>

                <?php
                $approvedFriend = ['Charlie Pham', 'Brian Tran', 'Frank Hoang'];

                foreach ($reviewers as $reviewer):
                    if (in_array($reviewer['username'], $approvedFriend) && $reviewer['username'] !== $savedName):
                ?>
                        <option value="<?= htmlspecialchars($reviewer['username'], ENT_QUOTES, 'UTF-8') ?>">
                            Post for a friend: <?= htmlspecialchars($reviewer['username'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                <?php
                    endif;
                endforeach;
                ?>
            </select>

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
<main>
    <form action="addreview.php" method="post">
        <h2>Add New Review</h2>
        <label for="reviewers_name">Reviewer</label>
        <input type="text" id="reviewers_name" name="reviewers_name" required>

        <label for="existing_reviewers">Select Reviewer:</label>
        <select name="existing_reviewers" id="existing_reviewers">
            <option value="">---Select Your Name---</option>
            <?php foreach ($reviewers as $reviewer): ?>
                <option value="<?= htmlspecialchars($reviewer['username'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?= htmlspecialchars($reviewer['username'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
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

        <label for="rating">Rating:</label>
        <select name="rating">
            <option value="1">1⭐</option>
            <option value="2">2⭐</option>
            <option value="3">3⭐</option>
            <option value="4">4⭐</option>
            <option value="5">5⭐</option>
        </select>

        <label for="review_text">Add a new comment:</label>
        <textarea name="review_text" id="review_text" rows="3" cols="40" required></textarea>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn-add">Add Your Review</button>
        </div>
    </form>

    <script>
        document.getElementById('existing_reviewers').addEventListener('change', function() {
            if (this.value) {
                document.getElementById('reviewers_name').value = this.value;
            }
        });
    </script>
    </div>
</main>
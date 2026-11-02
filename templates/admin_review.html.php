<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="admin_style.css">

<h2>Manage Reviews List</h2>

<?php if (isset($error)): ?>
    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php else: ?>
    <p><?= $totalReview ?> reviews have been submitted to Review Film</p>

    <div style="margin-top: 20px;">
        <a href="addreview.php" class="btn-add" style="text-decoration: none; display: inline-block;">
            Add New Review
        </a>
    </div>
    <br>

    <div class="search-filter-wrapper">
        <form action="" method="get" class="search-bar-container">
            <input type="text" name="search" placeholder="Search film, user or review text..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort'] ?? 'r.review_date DESC') ?>">
            <button type="submit" class="search-icon-btn">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <button id="openFilter" class="btn-filter-icon">
            <i class="fa-solid fa-filter"></i>
        </button>
    </div>

    <div id="filterModal" class="modal-overlay">
        <div class="modal-content">
            <span id="closeFilter" class="close-btn">&times;</span>
            <h3 style="color: var(--accent); margin-bottom: 20px;">Sort Reviews</h3>

            <form action="" method="get">
                <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

                <div class="sort-options-list">
                    <div class="filter-group">
                        <label>Sort By</label>
                        <select name="sort" style="width: 100%; padding: 10px; border-radius: 5px;">
                            <option value="r.review_date DESC">Latest Reviews</option>
                            <option value="r.rating DESC" <?= ($_GET['sort'] ?? '') == 'r.rating DESC' ? 'selected' : '' ?>>Rating: High to Low</option>
                            <option value="r.rating ASC" <?= ($_GET['sort'] ?? '') == 'r.rating ASC' ? 'selected' : '' ?>>Rating: Low to High</option>
                            <option value="r.review_date ASC" <?= ($_GET['sort'] ?? '') == 'r.review_date ASC' ? 'selected' : '' ?>>Oldest Reviews</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    <button type="submit" class="btn-add" style="flex: 1;">Apply Sort</button>
                    <a href="?" class="btn-delete" style="flex: 1; text-decoration:none;">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <table border="1">
        <tr>
            <th>Text</th>
            <th>Date</th>
            <th>Film</th>
            <th>Reviewer</th>
            <th>Action</th>
        </tr>

        <?php foreach ($reviews as $review): ?>

            <tr>
                <td><?= htmlspecialchars($review['review_text']) ?></td>

                <td><?= htmlspecialchars($review['review_date']) ?></td>

                <td><?= htmlspecialchars($review['title']) ?></td>

                <td><?= htmlspecialchars($review['username']) ?></td>

                <td>
                    <form action="editreview.php" method="get" style="display:inline-block">
                        <input type="hidden" name="id" value="<?= $review['id'] ?>">
                        <button type="submit" class="btn-edit">Edit</button>
                    </form>

                    <form action="delete.php" method="post" style="display:inline-block" onsubmit="return confirm('Are you sure you want to delete this review?');">
                        <input type="hidden" name="id" value="<?= $review['id'] ?>">
                        <input type="hidden" name="type" value="review">
                        <button type="submit" class="btn-delete">Delete</button>
                    </form>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>
    <?php include __DIR__ . '/pagination.html.php'; ?>
<?php endif; ?>

<script>
    const modal = document.getElementById("filterModal");
    const openBtn = document.getElementById("openFilter");
    const closeBtn = document.getElementById("closeFilter");

    openBtn.onclick = function() {
        modal.style.display = "block";
    }
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="admin_style.css">

<h2>Reviewers List</h2>

<p>Total Reviewers: <?= $totalReviewers ?></p>

<div style="margin-top: 20px;">
    <a href="addreviewers.php" class="btn-add" style="text-decoration: none; display: inline-block;">
        Add New Reviewer
    </a>
</div>
<br>

<div class="search-filter-wrapper">
    <form action="" method="get" class="search-bar-container">
        <input type="text" name="search" placeholder="Search username or email..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort'] ?? 'created_at DESC') ?>">
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
        <h3 style="color: var(--accent); margin-bottom: 20px;">Sort Reviewers</h3>

        <form action="" method="get" class="filter-form-vertical">
            <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

            <div class="filter-group">
                <label>Sort By</label>
                <select name="sort">
                    <option value="created_at DESC">Newest Reviewers</option>
                    <option value="username ASC" <?= ($_GET['sort'] ?? '') == 'username ASC' ? 'selected' : '' ?>>Username: A - Z</option>
                    <option value="username DESC" <?= ($_GET['sort'] ?? '') == 'username DESC' ? 'selected' : '' ?>>Username: Z - A</option>
                    <option value="created_at ASC" <?= ($_GET['sort'] ?? '') == 'created_at ASC' ? 'selected' : '' ?>>Oldest Reviewers</option>
                </select>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" class="btn-add" style="flex: 1;">Apply Sort</button>
                <a href="?" class="btn-delete" style="flex: 1; text-decoration:none;">Reset</a>
            </div>
        </form>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reviewers as $reviewer): ?>
            <tr>
                <td><?= htmlspecialchars($reviewer['username'], ENT_QUOTES, 'UTF-8') ?></td>

                <td>
                    <?php if (!empty($reviewer['phone_number'])): ?>
                        <?= htmlspecialchars($reviewer['phone_number'], ENT_QUOTES, 'UTF-8') ?>
                    <?php else: ?>
                        <em style="color: #777;">None</em>
                    <?php endif; ?>
                </td>

                <td>
                    <?php if (!empty($reviewer['email'])): ?>
                        <a href="mailto:<?= htmlspecialchars($reviewer['email'], ENT_QUOTES, 'UTF-8') ?>" style="color: var(--accent);">
                            <?= htmlspecialchars($reviewer['email'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    <?php else: ?>
                        <em style="color: #777;">NULL</em>
                    <?php endif; ?>
                </td>

                <td>
                    <div style="display: flex; gap: 10px;">
                        <a href="editreviewers.php?id=<?= $reviewer['id'] ?>" class="btn-edit">Edit</a>

                        <form action="delete.php" method="post" onsubmit="return confirm('Are you sure you want to delete this reviewer?');">
                            <input type="hidden" name="id" value="<?= $reviewer['id'] ?>">
                            <input type="hidden" name="type" value="reviewers"> <input type="submit" value="Delete" class="btn-delete">
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include __DIR__ . '/pagination.html.php'; ?>

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
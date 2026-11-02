<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="style.css">

<div class="films-container">
    <section class="films-list-section">
        <h2>Films List (Total: <?= $pagination['totalItems'] ?> films)</h2>

        <div style="margin-bottom: 20px;">
            <button id="openFilter" class="btn-filter-icon">
                <i class="fa-solid fa-filter"></i>
            </button>
        </div>

        <div id="filterModal" class="modal-overlay">
            <div class="modal-content">
                <span id="closeFilter" class="close-btn">&times;</span>

                <form action="" method="get" class="filter-form-vertical">
                    <div class="filter-group">
                        <label>Title</label>
                        <input type="text" name="search" placeholder="Search film title..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>

                    <div class="filter-group">
                        <label>Director</label>
                        <input type="text" name="director_name" placeholder="Search director..." value="<?= htmlspecialchars($_GET['director_name'] ?? '') ?>">
                    </div>

                    <div class="filter-group">
                        <label>Genre</label>
                        <select name="genre_id">
                            <option value="">-- All Genres --</option>
                            <?php foreach ($genres as $genre): ?>
                                <option value="<?= $genre['id'] ?>" <?= ($_GET['genre_id'] ?? '') == $genre['id'] ? 'selected' : '' ?>><?= $genre['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Year Range</label>
                        <select name="year_range">
                            <option value="">-- All Years --</option>
                            <option value="2020-2029" <?= ($_GET['year_range'] ?? '') == '2020-2029' ? 'selected' : '' ?>>2020 - 2029</option>
                            <option value="2010-2019" <?= ($_GET['year_range'] ?? '') == '2010-2019' ? 'selected' : '' ?>>2010 - 2019</option>
                            <option value="0-2009" <?= ($_GET['year_range'] ?? '') == '0-2009' ? 'selected' : '' ?>>Before 2010</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Sort By</label>
                        <select name="sort">
                            <option value="f.id DESC">Newest</option>
                            <option value="f.title ASC" <?= ($_GET['sort'] ?? '') == 'f.title ASC' ? 'selected' : '' ?>>A - Z</option>
                            <option value="f.title DESC" <?= ($_GET['sort'] ?? '') == 'f.title DESC' ? 'selected' : '' ?>>Z - A</option>
                        </select>
                    </div>

                    <div style="margin-top: 25px; display: flex; gap: 10px;">
                        <button type="submit" class="btn-add" style="flex: 2;">Apply Filters</button>
                        <a href="?" class="btn-delete" style="flex: 1; text-decoration:none; text-align:center; line-height: 40px;">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <?php if (empty($films)): ?>
            <p class="no-films">No films found.</p>
        <?php else: ?>
            <table class="films-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Poster</th>
                        <th>Genre</th>
                        <th>Director</th>
                        <th>Description</th>
                        <th>Released Year</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($films as $film): ?>
                        <tr class="film-row">
                            <td><strong><?= htmlspecialchars($film['title']) ?></strong></td>
                            <td><img src="<?= htmlspecialchars($film['poster']) ?>" width="150" height="220" alt="<?= htmlspecialchars($film['title']) ?>"></td>
                            <td><?= htmlspecialchars($film['genre'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($film['director_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($film['description'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($film['released_year']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <?php include __DIR__ . '/pagination.html.php'; ?>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("filterModal");
        const openBtn = document.getElementById("openFilter");
        const closeBtn = document.getElementById("closeFilter");

        if (openBtn && modal && closeBtn) {
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
        }
    });
</script>
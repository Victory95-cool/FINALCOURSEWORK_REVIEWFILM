<link rel="stylesheet" href="admin_style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="films-container">
    <section class="films-list-section">
        <h2>Films List (Total: <?= $pagination['totalItems'] ?> films)</h2>

        <div style="margin-bottom: 15px;">
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

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn-add">Apply Filters</button>
                        <a href="?" class="btn-delete" style="text-decoration:none;">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <a href="addfilms.php" class="btn-add" style="text-decoration: none; display: inline-block;">
                Add New Film
            </a>
        </div>
        <br>

        <?php if (empty($films)): ?>
            <p class="no-films">No films in database.</p>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($films as $film): ?>
                        <tr class="film-row">
                            <td><strong><?= htmlspecialchars($film['title'], ENT_QUOTES, 'UTF-8') ?></strong></td>

                            <td>
                                <?php if (!empty($film['poster'])): ?>
                                    <img src="../<?= htmlspecialchars($film['poster'], ENT_QUOTES, 'UTF-8') ?>"
                                        width="200" height="300"
                                        alt="<?= htmlspecialchars($film['title'], ENT_QUOTES, 'UTF-8') ?>">
                                <?php else: ?>
                                    <span style="color: #666;">No Image</span>
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($film['genre'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($film['director_name'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($film['description'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($film['released_year'], ENT_QUOTES, 'UTF-8') ?></td>

                            <td>
                                <a href="editfilms.php?id=<?= $film['id'] ?>" class="btn-edit">Edit</a>

                                <form action="delete.php" method="post" onsubmit="return confirm('Are you sure you want to delete this film?');">
                                    <input type="hidden" name="id" value="<?= $film['id'] ?>">
                                    <input type="hidden" name="type" value="film">
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <?php include __DIR__ . '/pagination.html.php'; ?>
    </section>
</div>

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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $film['id'] ?>">

    <label>Film Title</label>
    <input type="text" name="title" value="<?= htmlspecialchars($film['title'], ENT_QUOTES, 'UTF-8') ?>" required>

    <label>Genre</label>
    <select name="genre_id">
        <?php foreach ($genres as $genre): ?>
            <option value="<?= $genre['id'] ?>" <?= ($genre['id'] == $film['genre_id']) ? 'selected' : '' ?>>
                <?= $genre['name'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Description</label>
    <textarea name="description" rows="4"><?= htmlspecialchars($film['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

    <label>Director (Select or type new)</label>
    <input list="dir-list" name="director_name" value="<?= htmlspecialchars($film['director_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    <datalist id="dir-list">
        <?php foreach ($directors as $d): ?>
            <option value="<?= htmlspecialchars($d['name']) ?>">
            <?php endforeach; ?>
    </datalist>

    <label>Cast (Select multiple or type new)</label>
    <select name="cast_names[]" id="cast-select" multiple="multiple" style="width: 100%">
        <?php foreach ($stars as $s): ?>
            <option value="<?= htmlspecialchars($s['name']) ?>"
                <?= in_array(trim($s['name']), $currentStars) ? 'selected' : '' ?>>
                <?= htmlspecialchars($s['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Released Year</label>
    <input type="number" name="released_year" value="<?= $film['released_year'] ?>" required>

    <div style="margin: 15px 0;">
        <label>Current Poster</label>
        <div style="display:flex; justify-content: center; margin-bottom: 10px;">
            <img src="../<?= htmlspecialchars($film['poster'], ENT_QUOTES, 'UTF-8') ?>" width="150" style="border-radius: 8px; border:2px solid var(--accent);">
        </div>
    </div>

    <label>Change Poster Image</label>
    <input type="file" name="poster_file" accept="image/*">
    <input type="hidden" name="existing_poster" value="<?= htmlspecialchars($film['poster'], ENT_QUOTES, 'UTF-8') ?>">
    <br>

    <input type="submit" value="Update Film" class="btn-update">
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#cast-select').select2({
            tags: true,
            tokenSeparators: [',', ';'],
            placeholder: "Select or type stars..."
        });
    });
</script>
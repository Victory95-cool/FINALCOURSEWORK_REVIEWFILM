<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="admin_style.css">

<h2>Add New Film</h2>
<form action="" method="post" enctype="multipart/form-data">
    <label>Film Title</label>
    <input type="text" name="title" required>

    <label>Genre</label>
    <select name="genre_id">
        <?php foreach ($genres as $genre): ?>
            <option value="<?= $genre['id'] ?>"><?= $genre['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Description</label>
    <textarea name="description" rows="4" placeholder="Enter movie summary..."></textarea>

    <label>Director (Select existing or type new)</label>
    <input list="dir-list" name="director_name" placeholder="Search director..." required>
    <datalist id="dir-list">
        <?php foreach ($directors as $d): ?>
            <option value="<?= htmlspecialchars($d['name']) ?>">
            <?php endforeach; ?>
    </datalist>

    <label>Cast (Select multiple or type new)</label>
    <select name="cast_names[]" id="cast-select" multiple="multiple" style="width: 100%">
        <?php foreach ($stars as $s): ?>
            <option value="<?= htmlspecialchars($s['name']) ?>">
                <?= htmlspecialchars($s['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Released Year</label>
    <input type="number" name="released_year" required>

    <label>Upload Poster</label>
    <input type="file" name="poster_file" accept="image/*" required>

    <input type="submit" value="Add Film" class="btn-add">
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#cast-select').select2({
            tags: true,
            tokenSeparators: [',', ';'],
            placeholder: "Type name and press Enter...",
            width: '100%'
        });
    });
</script>
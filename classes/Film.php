<?php

class Film
{
    // Lấy một bộ phim theo id
    public function getFilm($pdo, $id)
    {
        $parameters = [':id' => $id];
        $query = Database::query($pdo, 'SELECT id, title, released_year, created_at, genre_id, poster FROM film WHERE id = :id', $parameters);
        return $query->fetch();
    }

    // Lấy danh sách genre
    public function allGenres($pdo)
    {
        $query = Database::query($pdo, 'SELECT id, name FROM genre ORDER BY name ASC');
        return $query->fetchAll();
    }

    public function duplicateExists($pdo, $title, $releasedYear, $excludeId = null)
    {
        $sql = 'SELECT EXISTS(
                    SELECT 1 FROM film
                    WHERE LOWER(TRIM(title)) = LOWER(TRIM(:title))
                      AND released_year = :released_year';
        $parameters = [':title' => trim($title), ':released_year' => $releasedYear];
        if ($excludeId !== null) {
            $sql .= ' AND id <> :exclude_id';
            $parameters[':exclude_id'] = $excludeId;
        }
        $sql .= ')';

        return (bool) Database::query($pdo, $sql, $parameters)->fetchColumn();
    }

    public function duplicatePosterExists($pdo, $posterPath, $excludeId = null)
    {
        if (!is_string($posterPath) || trim($posterPath) === '') {
            return false;
        }

        $uploadedFile = $this->resolvePosterFile($posterPath);
        if ($uploadedFile === null) {
            return false;
        }

        $uploadedHash = hash_file('sha256', $uploadedFile);
        if ($uploadedHash === false) {
            return false;
        }

        $sql = 'SELECT poster FROM film';
        $parameters = [];
        if ($excludeId !== null) {
            $sql .= ' WHERE id <> :exclude_id';
            $parameters[':exclude_id'] = $excludeId;
        }

        foreach (Database::query($pdo, $sql, $parameters)->fetchAll() as $row) {
            $existingFile = $this->resolvePosterFile($row['poster']);
            if ($existingFile !== null) {
                $existingHash = hash_file('sha256', $existingFile);
                if ($existingHash !== false && hash_equals($uploadedHash, $existingHash)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function duplicateUploadedPosterExists($pdo, $file, $excludeId = null)
    {
        if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return false;
        }
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK
            || !isset($file['tmp_name'])
            || !is_file($file['tmp_name'])
        ) {
            return false;
        }

        $uploadedHash = hash_file('sha256', $file['tmp_name']);
        if ($uploadedHash === false) {
            return false;
        }

        $sql = 'SELECT poster FROM film';
        $parameters = [];
        if ($excludeId !== null) {
            $sql .= ' WHERE id <> :exclude_id';
            $parameters[':exclude_id'] = $excludeId;
        }

        foreach (Database::query($pdo, $sql, $parameters)->fetchAll() as $row) {
            $existingFile = $this->resolvePosterFile($row['poster']);
            if ($existingFile !== null) {
                $existingHash = hash_file('sha256', $existingFile);
                if ($existingHash !== false && hash_equals($uploadedHash, $existingHash)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function resolvePosterFile($posterPath)
    {
        if (!is_string($posterPath) || trim($posterPath) === '') {
            return null;
        }

        $normalized = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, trim($posterPath));
        $normalized = ltrim($normalized, DIRECTORY_SEPARATOR);
        $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . $normalized;
        return is_file($file) ? $file : null;
    }

    // Thêm phim mới (kèm đạo diễn và diễn viên)
    public function insertFilm($pdo, $title, $genre_id, $released_year, $poster, $description, $director_name, $cast_array)
    {
        if ($this->duplicateExists($pdo, $title, $released_year)) {
            throw new InvalidArgumentException('A film with this title and release year already exists.');
        }
        if ($this->duplicatePosterExists($pdo, $poster)) {
            throw new InvalidArgumentException('This poster is already used by another film.');
        }
        $director_id = $this->getOrCreateEntity($pdo, 'directors', $director_name);

        $sql = 'INSERT INTO film (title, genre_id, released_year, poster, description, director_id)
                VALUES (:title, :genre_id, :released_year, :poster, :description, :director_id)';

        Database::query($pdo, $sql, [
            ':title' => $title,
            ':genre_id' => $genre_id,
            ':released_year' => $released_year,
            ':poster' => $poster,
            ':description' => $description,
            ':director_id' => $director_id
        ]);

        $filmId = $pdo->lastInsertId();

        if (!empty($cast_array)) {
            foreach ($cast_array as $name) {
                $star_id = $this->getOrCreateEntity($pdo, 'stars', $name);
                Database::query($pdo, 'INSERT INTO film_stars (film_id, star_id) VALUES (:fid, :sid)', [
                    ':fid' => $filmId,
                    ':sid' => $star_id
                ]);
            }
        }
    }

    // Liệt kê phim, có tìm kiếm theo nhiều tiêu chí
    public function allFilms($pdo, $search = '', $genre_id = '', $year_range = '', $sort = 'f.id DESC', $director = '', $limit = null, $offset = 0)
    {
        [$where, $parameters] = $this->filmFilterQuery($search, $genre_id, $year_range, $director);
        $sql = 'SELECT f.id, f.title, g.name AS genre, f.released_year, f.poster, f.description, d.name AS director_name ' . $where;

        $allowedSorts = ['f.title ASC', 'f.title DESC', 'f.released_year DESC', 'f.released_year ASC', 'f.id DESC'];
        $order = in_array($sort, $allowedSorts) ? $sort : 'f.id DESC';
        $sql .= " ORDER BY $order";

        if ($limit !== null) {
            $sql .= ' LIMIT ' . max(1, (int) $limit) . ' OFFSET ' . max(0, (int) $offset);
        }

        return Database::query($pdo, $sql, $parameters)->fetchAll();
    }

    public function totalFilms($pdo, $search = '', $genre_id = '', $year_range = '', $director = '')
    {
        [$where, $parameters] = $this->filmFilterQuery($search, $genre_id, $year_range, $director);
        return Database::query($pdo, 'SELECT COUNT(*) ' . $where, $parameters)->fetchColumn();
    }

    private function filmFilterQuery($search, $genre_id, $year_range, $director)
    {
        $where = 'FROM film f
                  LEFT JOIN genre g ON f.genre_id = g.id
                  LEFT JOIN directors d ON f.director_id = d.id
                  WHERE 1=1';
        $parameters = [];

        if (!empty($search)) {
            $where .= ' AND f.title LIKE :search';
            $parameters['search'] = '%' . $search . '%';
        }

        if (!empty($director)) {
            $where .= ' AND d.name LIKE :director_name';
            $parameters['director_name'] = '%' . $director . '%';
        }

        if (!empty($genre_id)) {
            $where .= ' AND f.genre_id = :genre_id';
            $parameters['genre_id'] = $genre_id;
        }

        if (!empty($year_range)) {
            $years = explode('-', $year_range);
            if (count($years) == 2) {
                $where .= ' AND f.released_year BETWEEN :year_from AND :year_to';
                $parameters['year_from'] = $years[0];
                $parameters['year_to'] = $years[1];
            }
        }

        return [$where, $parameters];
    }

    // Cập nhật phim
    public function updateFilm($pdo, $id, $title, $genre_id, $released_year, $poster, $description, $director_name, $cast_array)
    {
        if ($this->duplicateExists($pdo, $title, $released_year, $id)) {
            throw new InvalidArgumentException('A film with this title and release year already exists.');
        }
        if ($this->duplicatePosterExists($pdo, $poster, $id)) {
            throw new InvalidArgumentException('This poster is already used by another film.');
        }
        $director_id = $this->getOrCreateEntity($pdo, 'directors', $director_name);

        $sql = 'UPDATE film 
                SET title = :title, 
                    genre_id = :genre_id, 
                    released_year = :released_year, 
                    poster = :poster,
                    description = :description,
                    director_id = :director_id
                WHERE id = :id';

        $parameters = [
            ':title' => $title,
            ':genre_id' => $genre_id,
            ':released_year' => $released_year,
            ':poster' => $poster,
            ':description' => $description,
            ':director_id' => $director_id,
            ':id' => $id
        ];
        Database::query($pdo, $sql, $parameters);

        Database::query($pdo, 'DELETE FROM film_stars WHERE film_id = :id', [':id' => $id]);

        if (!empty($cast_array)) {
            foreach ($cast_array as $name) {
                $star_id = $this->getOrCreateEntity($pdo, 'stars', $name);
                Database::query($pdo, 'INSERT INTO film_stars (film_id, star_id) VALUES (:fid, :sid)', [
                    ':fid' => $id,
                    ':sid' => $star_id
                ]);
            }
        }
    }

    // Xóa phim và các review liên quan
    public function deleteFilm($pdo, $id)
    {
        Database::query($pdo, 'DELETE FROM review WHERE film_id = :id', [':id' => $id]);
        Database::query($pdo, 'DELETE FROM film WHERE id = :id', [':id' => $id]);
    }

    // Lấy chi tiết phim (kèm genre, đạo diễn, diễn viên)
    public function getFilmDetails($pdo, $id)
    {
        $parameters = [':id' => $id];
        $sql = 'SELECT f.*, g.name AS genre_name, d.name AS director_name,
                (SELECT GROUP_CONCAT(s.name SEPARATOR ", ") 
                 FROM film_stars fs 
                 JOIN stars s ON fs.star_id = s.id 
                 WHERE fs.film_id = f.id) AS cast_names
                FROM film f 
                LEFT JOIN genre g ON f.genre_id = g.id 
                LEFT JOIN directors d ON f.director_id = d.id
                WHERE f.id = :id';
        $query = Database::query($pdo, $sql, $parameters);
        return $query->fetch();
    }

    // Lấy các review của một phim
    public function totalReviewsForFilm($pdo, $filmId)
    {
        return (int) Database::query(
            $pdo,
            'SELECT COUNT(*) FROM review WHERE film_id = :film_id',
            [':film_id' => $filmId]
        )->fetchColumn();
    }

    public function getReviewsByFilmId($pdo, $film_id, $limit = 3, $offset = 0)
    {
        $sql = 'SELECT r.*, rev.username 
                FROM review r 
                INNER JOIN reviewers rev ON r.reviewers_id = rev.id 
                WHERE r.film_id = :film_id 
                ORDER BY r.review_date DESC, r.id DESC
                LIMIT ' . max(1, (int) $limit) . ' OFFSET ' . max(0, (int) $offset);
        return Database::query($pdo, $sql, [':film_id' => $film_id])->fetchAll();
    }

    // Lấy điểm trung bình và tổng số review của một phim
    public function getFilmStats($pdo, $film_id)
    {
        $sql = 'SELECT AVG(rating) as avg_rating, COUNT(*) as total_count 
                FROM review 
                WHERE film_id = :film_id';
        $stmt = Database::query($pdo, $sql, [':film_id' => $film_id]);
        return $stmt->fetch();
    }

    // Tìm entity (director/star) theo tên, tạo mới nếu chưa có
    public function getOrCreateEntity($pdo, $tableName, $name)
    {
        $name = trim($name);
        if (empty($name)) return null;

        $query = Database::query($pdo, "SELECT id FROM $tableName WHERE name = :name", [':name' => $name]);
        $existing = $query->fetch();

        if ($existing) return $existing['id'];

        Database::query($pdo, "INSERT INTO $tableName (name) VALUES (:name)", [':name' => $name]);
        return $pdo->lastInsertId();
    }

    // Lấy danh sách đạo diễn
    public function allDirectors($pdo)
    {
        return Database::query($pdo, 'SELECT name FROM directors ORDER BY name DESC')->fetchAll();
    }

    // Lấy danh sách diễn viên
    public function allStars($pdo)
    {
        return Database::query($pdo, 'SELECT name FROM stars ORDER BY name DESC')->fetchAll();
    }
}

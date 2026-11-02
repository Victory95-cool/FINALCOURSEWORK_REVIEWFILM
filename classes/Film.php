<?php

class Film
{
    // Lấy một bộ phim theo id
    public static function getFilm($pdo, $id)
    {
        $parameters = [':id' => $id];
        $query = Database::query($pdo, 'SELECT id, title, released_year, created_at, genre_id, poster FROM film WHERE id = :id', $parameters);
        return $query->fetch();
    }

    // Lấy danh sách genre
    public static function allGenres($pdo)
    {
        $query = Database::query($pdo, 'SELECT id, name FROM genre ORDER BY name ASC');
        return $query->fetchAll();
    }

    // Thêm phim mới (kèm đạo diễn và diễn viên)
    public static function insertFilm($pdo, $title, $genre_id, $released_year, $poster, $description, $director_name, $cast_array)
    {
        $director_id = self::getOrCreateEntity($pdo, 'directors', $director_name);

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
                $star_id = self::getOrCreateEntity($pdo, 'stars', $name);
                Database::query($pdo, 'INSERT INTO film_stars (film_id, star_id) VALUES (:fid, :sid)', [
                    ':fid' => $filmId,
                    ':sid' => $star_id
                ]);
            }
        }
    }

    // Liệt kê phim, có tìm kiếm theo nhiều tiêu chí
    public static function allFilms($pdo, $search = '', $genre_id = '', $year_range = '', $sort = 'f.id DESC', $director = '', $limit = null, $offset = 0)
    {
        [$where, $parameters] = self::filmFilterQuery($search, $genre_id, $year_range, $director);
        $sql = 'SELECT f.id, f.title, g.name AS genre, f.released_year, f.poster, f.description, d.name AS director_name ' . $where;

        $allowedSorts = ['f.title ASC', 'f.title DESC', 'f.released_year DESC', 'f.released_year ASC', 'f.id DESC'];
        $order = in_array($sort, $allowedSorts) ? $sort : 'f.id DESC';
        $sql .= " ORDER BY $order";

        if ($limit !== null) {
            $sql .= ' LIMIT ' . max(1, (int) $limit) . ' OFFSET ' . max(0, (int) $offset);
        }

        return Database::query($pdo, $sql, $parameters)->fetchAll();
    }

    public static function totalFilms($pdo, $search = '', $genre_id = '', $year_range = '', $director = '')
    {
        [$where, $parameters] = self::filmFilterQuery($search, $genre_id, $year_range, $director);
        return Database::query($pdo, 'SELECT COUNT(*) ' . $where, $parameters)->fetchColumn();
    }

    private static function filmFilterQuery($search, $genre_id, $year_range, $director)
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
    public static function updateFilm($pdo, $id, $title, $genre_id, $released_year, $poster, $description, $director_name, $cast_array)
    {
        $director_id = self::getOrCreateEntity($pdo, 'directors', $director_name);

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
                $star_id = self::getOrCreateEntity($pdo, 'stars', $name);
                Database::query($pdo, 'INSERT INTO film_stars (film_id, star_id) VALUES (:fid, :sid)', [
                    ':fid' => $id,
                    ':sid' => $star_id
                ]);
            }
        }
    }

    // Xóa phim và các review liên quan
    public static function deleteFilm($pdo, $id)
    {
        Database::query($pdo, 'DELETE FROM review WHERE film_id = :id', [':id' => $id]);
        Database::query($pdo, 'DELETE FROM film WHERE id = :id', [':id' => $id]);
    }

    // Lấy chi tiết phim (kèm genre, đạo diễn, diễn viên)
    public static function getFilmDetails($pdo, $id)
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
    public static function getReviewsByFilmId($pdo, $film_id)
    {
        $sql = 'SELECT r.*, rev.username 
                FROM review r 
                INNER JOIN reviewers rev ON r.reviewers_id = rev.id 
                WHERE r.film_id = :film_id 
                ORDER BY r.review_date DESC';
        return Database::query($pdo, $sql, [':film_id' => $film_id])->fetchAll();
    }

    // Lấy điểm trung bình và tổng số review của một phim
    public static function getFilmStats($pdo, $film_id)
    {
        $sql = 'SELECT AVG(rating) as avg_rating, COUNT(*) as total_count 
                FROM review 
                WHERE film_id = :film_id';
        $stmt = Database::query($pdo, $sql, [':film_id' => $film_id]);
        return $stmt->fetch();
    }

    // Tìm entity (director/star) theo tên, tạo mới nếu chưa có
    public static function getOrCreateEntity($pdo, $tableName, $name)
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
    public static function allDirectors($pdo)
    {
        return Database::query($pdo, 'SELECT name FROM directors ORDER BY name DESC')->fetchAll();
    }

    // Lấy danh sách diễn viên
    public static function allStars($pdo)
    {
        return Database::query($pdo, 'SELECT name FROM stars ORDER BY name DESC')->fetchAll();
    }
}

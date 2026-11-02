<?php

class Review
{
    // Đếm tổng số review
    public static function totalReview($pdo)
    {
        $query = Database::query($pdo, "SELECT COUNT(*) FROM review");
        $row = $query->fetch();
        return $row[0];
    }

    public static function totalFilteredReviews($pdo, $search = '')
    {
        $sql = 'SELECT COUNT(*)
                FROM review r
                INNER JOIN reviewers rev ON r.reviewers_id = rev.id
                INNER JOIN film f ON r.film_id = f.id
                INNER JOIN genre g ON f.genre_id = g.id
                WHERE (f.title LIKE :film_search OR rev.username LIKE :reviewer_search OR r.review_text LIKE :text_search)';

        $searchTerm = '%' . $search . '%';
        return Database::query($pdo, $sql, [
            ':film_search' => $searchTerm,
            ':reviewer_search' => $searchTerm,
            ':text_search' => $searchTerm,
        ])->fetchColumn();
    }

    // Cập nhật nội dung và điểm của một review
    public static function updateReview($pdo, $reviewid, $reviewtext, $rating, $film_id = null)
    {
        $sql = 'UPDATE review
                SET review_text = :reviewtext,
                    rating = :rating';

        $parameters = [
            ':reviewtext' => $reviewtext,
            ':rating' => $rating,
            ':id' => $reviewid
        ];

        if ($film_id !== null) {
            $sql .= ', film_id = :film_id';
            $parameters[':film_id'] = $film_id;
        }

        $sql .= ' WHERE id = :id';

        Database::query($pdo, $sql, $parameters);
    }

    // Thêm review mới (tự tìm hoặc tạo reviewer theo tên)
    public static function insertReview($pdo, $review_text, $rating, $reviewers_name, $film_id)
    {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("SELECT id, email FROM reviewers WHERE username = :username");
            $stmt->execute([':username' => $reviewers_name]);
            $reviewer = $stmt->fetch();

            if ($reviewer) {
                $reviewers_id = $reviewer['id'];
            } else {
                $stmt = $pdo->prepare("INSERT INTO reviewers (username) VALUES (:username)");
                $stmt->execute([':username' => $reviewers_name]);
                $reviewers_id = $pdo->lastInsertId();
            }

            $sql = "INSERT INTO review 
                    (review_text, rating, reviewers_id, film_id, review_date)
                    VALUES 
                    (:review_text, :rating, :reviewers_id, :film_id, NOW())";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':review_text' => $review_text,
                ':rating' => $rating,
                ':reviewers_id' => $reviewers_id,
                ':film_id' => $film_id,
            ]);

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    // Lấy một review theo id (kèm tên phim và poster)
    public static function getReview($pdo, $id)
    {
        $parameters = [':id' => $id];
        $sql = 'SELECT review.*, film.poster, film.title 
                FROM review 
                INNER JOIN film ON review.film_id = film.id 
                WHERE review.id = :id';
        $query = Database::query($pdo, $sql, $parameters);
        return $query->fetch();
    }

    // Liệt kê review, có tìm kiếm và sắp xếp
    public static function allReviewsFiltered($pdo, $search = '', $sort = 'r.review_date DESC', $limit = null, $offset = 0)
    {
        $sql = 'SELECT r.id, r.review_text, r.rating, r.review_date, rev.username, f.title, g.name AS genre, f.poster
                FROM review r
                INNER JOIN reviewers rev ON r.reviewers_id = rev.id
                INNER JOIN film f ON r.film_id = f.id
                INNER JOIN genre g ON f.genre_id = g.id
                WHERE (f.title LIKE :film_search OR rev.username LIKE :reviewer_search OR r.review_text LIKE :text_search)';

        $searchTerm = '%' . $search . '%';
        $parameters = [
            ':film_search' => $searchTerm,
            ':reviewer_search' => $searchTerm,
            ':text_search' => $searchTerm,
        ];

        $allowedSorts = ['r.rating DESC', 'r.rating ASC', 'r.review_date DESC', 'r.review_date ASC'];
        $order = in_array($sort, $allowedSorts) ? $sort : 'r.review_date DESC';
        $sql .= " ORDER BY $order";

        if ($limit !== null) {
            $sql .= ' LIMIT ' . max(1, (int) $limit) . ' OFFSET ' . max(0, (int) $offset);
        }

        return Database::query($pdo, $sql, $parameters)->fetchAll();
    }

    // Xóa một review
    public static function deleteReview($pdo, $id)
    {
        Database::query($pdo, 'DELETE FROM review WHERE id = :id', [':id' => $id]);
    }
}

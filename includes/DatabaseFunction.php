<?php

function query($pdo, $sql, $parameters = [])
{
    $query = $pdo->prepare($sql);
    $query->execute($parameters);
    return $query;
}

// ===========================================USER FUNCTIONS============================================ //

// =============================== Review =============================== //

function totalReview($pdo)
{
    $query = query($pdo, "SELECT COUNT(*) FROM review");
    $row = $query->fetch();
    return $row[0];
}

function updateReview($pdo, $reviewid, $reviewtext, $rating, $film_id = null)
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

    query($pdo, $sql, $parameters);
}

function insertReview($pdo, $review_text, $rating, $reviewers_name, $film_id)
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

function getReview($pdo, $id)
{
    $parameters = [':id' => $id];
    $sql = 'SELECT review.*, film.poster, film.title 
            FROM review 
            INNER JOIN film ON review.film_id = film.id 
            WHERE review.id = :id';
    $query = query($pdo, $sql, $parameters);
    return $query->fetch();
}

function allReviewsFiltered($pdo, $search = '', $sort = 'r.review_date DESC')
{
    $sql = 'SELECT r.id, r.review_text, r.rating, r.review_date, rev.username, f.title, g.name AS genre, f.poster
            FROM review r
            INNER JOIN reviewers rev ON r.reviewers_id = rev.id
            INNER JOIN film f ON r.film_id = f.id
            INNER JOIN genre g ON f.genre_id = g.id
            WHERE (f.title LIKE :search OR rev.username LIKE :search OR r.review_text LIKE :search)';

    $parameters = ['search' => '%' . $search . '%'];

    $allowedSorts = ['r.rating DESC', 'r.rating ASC', 'r.review_date DESC', 'r.review_date ASC'];
    $order = in_array($sort, $allowedSorts) ? $sort : 'r.review_date DESC';
    $sql .= " ORDER BY $order";

    return query($pdo, $sql, $parameters)->fetchAll();
}
// =============================== Reviewer =============================== //

function totalReviewers($pdo)
{
    $query = query($pdo, "SELECT COUNT(*) FROM reviewers");
    $row = $query->fetch();
    return $row[0];
}

function allReviewers($pdo, $search = '', $sort = 'created_at DESC')
{
    $sql = 'SELECT id, username, email, phone_number, created_at
         FROM reviewers
         WHERE (username LIKE :search OR email LIKE :search)';

    $parameters = [':search' => '%' . $search . '%'];

    $allowedSorts = ['username ASC', 'username DESC', 'created_at ASC', 'created_at DESC'];
    $order = in_array($sort, $allowedSorts) ? $sort : 'created_at DESC';
    $sql .= "ORDER BY $order";

    return query($pdo, $sql, $parameters)->fetchAll();
}

function getReviewers($pdo, $id)
{
    $parameters = [':id' => $id];
    return query($pdo, 'SELECT id, username, email, phone_number FROM reviewers WHERE id = :id', $parameters)->fetch();
}

// Modified version of your function in DatabaseFunction.php
function insertReviewer($pdo, $username, $phone, $email)
{
    // 1. Check if the username already exists
    $stmt = $pdo->prepare("SELECT id FROM reviewers WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        // Option A: Stop and tell the user (Recommended)
        throw new Exception("The username '$username' is already taken.");
    } else {
        // 2. Only insert if it doesn't exist
        $sql = 'INSERT INTO reviewers (username, phone_number, email) 
                VALUES (:username, :phone, :email)';
        $parameters = [
            ':username' => $username,
            ':phone' => $phone,
            ':email' => $email
        ];
        query($pdo, $sql, $parameters);
    }
}

function updateReviewer($pdo, $id, $username, $phone, $email)
{
    $sql = 'UPDATE reviewers 
            SET username = :username, 
                phone_number = :phone, 
                email = :email 
            WHERE id = :id';
    $parameters = [
        ':username' => $username,
        ':phone' => $phone,
        ':email' => $email,
        ':id' => $id
    ];
    query($pdo, $sql, $parameters);
}

// ============================== Film =============================== //

function getFilm($pdo, $id)
{
    $parameters = [':id' => $id];
    $query = query($pdo, 'SELECT id, title, released_year, created_at, genre_id, poster FROM film WHERE id = :id', $parameters);
    return $query->fetch();
}

function allGenres($pdo)
{
    $query = query(
        $pdo,
        'SELECT id, name FROM genre ORDER BY name ASC'
    );
    return $query->fetchAll();
}

function insertFilm($pdo, $title, $genre_id, $released_year, $poster, $description, $director_name, $cast_array)
{
    $director_id = getOrCreateEntity($pdo, 'directors', $director_name);

    $sql = 'INSERT INTO film (title, genre_id, released_year, poster, description, director_id)
            VALUES (:title, :genre_id, :released_year, :poster, :description, :director_id)';

    query($pdo, $sql, [
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
            $star_id = getOrCreateEntity($pdo, 'stars', $name);
            query($pdo, 'INSERT INTO film_stars (film_id, star_id) VALUES (:fid, :sid)', [
                ':fid' => $filmId,
                ':sid' => $star_id
            ]);
        }
    }
}

function allFilms($pdo, $search = '', $genre_id = '', $year_range = '', $sort = 'f.id DESC', $description = '', $director = '')
{
    $sql = 'SELECT f.id, f.title, g.name AS genre, f.released_year, f.poster, f.description, d.name AS director_name
            FROM film f
            LEFT JOIN genre g ON f.genre_id = g.id
            LEFT JOIN directors d ON f.director_id = d.id
            WHERE 1=1';

    $parameters = [];

    if (!empty($search)) {
        $sql .= ' AND f.title LIKE :search';
        $parameters['search'] = '%' . $search . '%';
    }

    if (!empty($director)) {
        $sql .= ' AND d.name LIKE :director_name';
        $parameters['director_name'] = '%' . $director . '%';
    }

    if (!empty($genre_id)) {
        $sql .= ' AND f.genre_id = :genre_id';
        $parameters['genre_id'] = $genre_id;
    }

    if (!empty($year_range)) {
        $years = explode('-', $year_range);
        if (count($years) == 2) {
            $sql .= ' AND f.released_year BETWEEN :year_from AND :year_to';
            $parameters['year_from'] = $years[0];
            $parameters['year_to'] = $years[1];
        }
    }

    $allowedSorts = ['f.title ASC', 'f.title DESC', 'f.released_year DESC', 'f.released_year ASC', 'f.id DESC'];
    $order = in_array($sort, $allowedSorts) ? $sort : 'f.id DESC';
    $sql .= " ORDER BY $order";

    return query($pdo, $sql, $parameters)->fetchAll();
}

// ===========================================ADMIN FUNCTIONS============================================ //

// Xóa Review
function deleteReview($pdo, $id)
{
    query($pdo, 'DELETE FROM review WHERE id = :id', [':id' => $id]);
}

function addReviewer($pdo, $name, $email)
{
    $stmt = $pdo->prepare("INSERT INTO reviewers (username, email) VALUES (:username, :email)");
    $stmt->execute([
        ':username' => $name,
        ':email' => $email
    ]);
    return $pdo->lastInsertId();
}

function getReviewerEmail($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT email FROM reviewers WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetchColumn();
}

function deleteFilm($pdo, $id)
{
    // 1. Xóa các review liên quan đến phim này trước
    query($pdo, 'DELETE FROM review WHERE film_id = :id', [':id' => $id]);
    // 2. Sau đó mới xóa phim
    query($pdo, 'DELETE FROM film WHERE id = :id', [':id' => $id]);
}

function deleteReviewers($pdo, $id)
{
    query($pdo, 'DELETE FROM mails_box WHERE reviewers_id = :id', [':id' => $id]);

    query($pdo, 'DELETE FROM review WHERE reviewers_id = :id', [':id' => $id]);

    query($pdo, 'DELETE FROM reviewers WHERE id = :id', [':id' => $id]);
}

function getAdminByUsername($pdo, $username)
{
    $parameters = [':username' => $username];
    $query = query($pdo, 'SELECT * FROM admins WHERE username = :username', $parameters);
    return $query->fetch();
}

function updateAdminPassword($pdo, $adminId, $newPassword)
{
    $parameters = [
        ':id' => $adminId,
        ':password' => $newPassword
    ];
    query($pdo, 'UPDATE admins SET password = :password WHERE id = :id', $parameters);
}

// Update Film
function updateFilm($pdo, $id, $title, $genre_id, $released_year, $poster, $description, $director_name, $cast_array)
{
    $director_id = getOrCreateEntity($pdo, 'directors', $director_name);

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
    query($pdo, $sql, $parameters);

    query($pdo, 'DELETE FROM film_stars WHERE film_id = :id', [':id' => $id]);

    if (!empty($cast_array)) {
        foreach ($cast_array as $name) {
            $star_id = getOrCreateEntity($pdo, 'stars', $name);
            query($pdo, 'INSERT INTO film_stars (film_id, star_id) VALUES (:fid, :sid)', [
                ':fid' => $id,
                ':sid' => $star_id
            ]);
        }
    }
}

// ======================================== Email ========================================== //

function createConversation($pdo, $subject, $reviewer_id, $body)
{
    query($pdo, "INSERT INTO conversations (subject, created_at) VALUES (:subject, NOW())", [
        ':subject' => $subject
    ]);
    $cid = $pdo->lastInsertId();

    query($pdo, "INSERT INTO mails_box (conversation_id, sender, reviewers_id, subject, body, created_at, is_read)
                 VALUES (:cid, 'reviewer', :rid, :subject, :body, NOW(), 0)", [
        ':cid' => $cid,
        ':rid' => $reviewer_id,
        ':subject' => $subject,
        ':body' => $body
    ]);
    return $cid;
}

function getConversation($pdo, $conversation_id)
{
    $parameters = [':cid' => $conversation_id];
    $sql = "SELECT 
                m.*, 
                r.username 
            FROM mails_box m
            LEFT JOIN reviewers r ON m.reviewers_id = r.id
            WHERE m.conversation_id = :cid
            ORDER BY m.created_at ASC";

    $query = query($pdo, $sql, $parameters);
    return $query->fetchAll();
}

function deleteConversation($pdo, $id)
{
    query($pdo, "DELETE FROM mails_box WHERE conversation_id = :id", [':id' => $id]);
    query($pdo, "DELETE FROM conversations WHERE id = :id", [':id' => $id]);
}

function deleteEmail($pdo, $email_id)
{
    query($pdo, "DELETE FROM mails_box WHERE id = :id", [':id' => $email_id]);
}

function getReviewerMailbox($pdo, $reviewer_id)
{
    if (!$reviewer_id) {
        return [];
    }

    $sql = "SELECT c.id, c.subject, MAX(m.created_at) as created_at, 
            SUM(CASE WHEN m.sender = 'admin' AND m.is_read = 0 THEN 1 ELSE 0 END) as unread_count 
            FROM conversations c 
            JOIN mails_box m ON c.id = m.conversation_id 
            WHERE m.reviewers_id = :rid 
            GROUP BY c.id 
            ORDER BY created_at DESC";

    return query($pdo, $sql, [':rid' => $reviewer_id])->fetchAll();
}

function adminCreateConversation($pdo, $subject, $reviewer_id, $body)
{
    $stmt = $pdo->prepare("INSERT INTO conversations (subject, created_at) VALUES (:subject, NOW())");
    $stmt->execute([':subject' => $subject]);
    $conversation_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO mails_box (conversation_id, sender, reviewers_id, subject, body, created_at, is_read) 
                           VALUES (:cid, 'admin', :rid, :subject, :body, NOW(), 0)");
    $stmt->execute([
        ':cid' => $conversation_id,
        ':rid' => $reviewer_id,
        ':subject' => $subject,
        ':body' => $body
    ]);

    return $conversation_id;
}

function markAsRead($pdo, $conversation_id, $viewer_role)
{
    $target_sender = ($viewer_role === 'admin') ? 'reviewer' : 'admin';
    $sql = "UPDATE mails_box SET is_read = 1 
            WHERE conversation_id = :cid AND sender = :sender";
    query($pdo, $sql, [':cid' => $conversation_id, ':sender' => $target_sender]);
}

function getContactReviewerList($pdo)
{
    $query = query($pdo, 'SELECT id, username FROM reviewers ORDER BY username ASC');
    return $query->fetchAll();
}

function replyToConversation($pdo, $conversation_id, $sender_role, $reviewer_id, $subject, $body) {
    $sql = "INSERT INTO mails_box (conversation_id, sender, reviewers_id, subject, body, created_at, is_read)
            VALUES (:cid, :sender, :rid, :subject, :body, NOW(), 0)";
    
    query($pdo, $sql, [
        ':cid' => $conversation_id,    
        ':sender' => $sender_role,    
        ':rid' => $reviewer_id,       
        ':subject' => $subject,       
        ':body' => $body              
    ]);
}

// ======================================= Film Details ===================================//
function getFilmDetails($pdo, $id)
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
    $query = query($pdo, $sql, $parameters);
    return $query->fetch();
}

function getReviewsByFilmId($pdo, $film_id)
{
    $sql = 'SELECT r.*, rev.username 
            FROM review r 
            INNER JOIN reviewers rev ON r.reviewers_id = rev.id 
            WHERE r.film_id = :film_id 
            ORDER BY r.review_date DESC';
    return query($pdo, $sql, [':film_id' => $film_id])->fetchAll();
}

function getFilmStats($pdo, $film_id)
{
    $sql = 'SELECT AVG(rating) as avg_rating, COUNT(*) as total_count 
            FROM review 
            WHERE film_id = :film_id';
    $stmt = query($pdo, $sql, [':film_id' => $film_id]);
    return $stmt->fetch();
}

function getOrCreateEntity($pdo, $tableName, $name)
{
    $name = trim($name);
    if (empty($name)) return null;

    $stmt = $pdo->prepare("SELECT id FROM $tableName WHERE name = :name");
    $stmt->execute([':name' => $name]);
    $existing = $stmt->fetch();

    if ($existing) return $existing['id'];

    $stmt = $pdo->prepare("INSERT INTO $tableName (name) VALUES (:name)");
    $stmt->execute([':name' => $name]);
    return $pdo->lastInsertId();
}

function allDirectors($pdo)
{
    return query($pdo, 'SELECT name FROM directors ORDER BY name DESC')->fetchAll();
}

function allStars($pdo)
{
    return query($pdo, 'SELECT name FROM stars ORDER BY name DESC')->fetchAll();
}

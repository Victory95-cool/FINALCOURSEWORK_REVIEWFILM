<?php

class Reviewer extends User
{
    // Đếm tổng số reviewer
    public static function totalReviewers($pdo)
    {
        $query = Database::query($pdo, "SELECT COUNT(*) FROM reviewers");
        $row = $query->fetch();
        return $row[0];
    }

    public static function totalFilteredReviewers($pdo, $search = '')
    {
        $query = Database::query(
            $pdo,
            'SELECT COUNT(*) FROM reviewers WHERE (username LIKE :search OR email LIKE :search)',
            [':search' => '%' . $search . '%']
        );
        return $query->fetchColumn();
    }

    // Liệt kê reviewer, có tìm kiếm và sắp xếp
    public static function allReviewers($pdo, $search = '', $sort = 'created_at DESC', $limit = null, $offset = 0)
    {
        $sql = 'SELECT id, username, email, phone_number, created_at
             FROM reviewers
             WHERE (username LIKE :search OR email LIKE :search)';

        $parameters = [':search' => '%' . $search . '%'];

        $allowedSorts = ['username ASC', 'username DESC', 'created_at ASC', 'created_at DESC'];
        $order = in_array($sort, $allowedSorts) ? $sort : 'created_at DESC';
        $sql .= " ORDER BY $order";

        if ($limit !== null) {
            $sql .= ' LIMIT ' . max(1, (int) $limit) . ' OFFSET ' . max(0, (int) $offset);
        }

        return Database::query($pdo, $sql, $parameters)->fetchAll();
    }

    // Lấy một reviewer theo id
    public static function getReviewers($pdo, $id)
    {
        $parameters = [':id' => $id];
        return Database::query($pdo, 'SELECT id, username, email, phone_number FROM reviewers WHERE id = :id', $parameters)->fetch();
    }

    // Thêm reviewer mới (báo lỗi nếu username đã tồn tại)
    public static function insertReviewer($pdo, $username, $phone, $email)
    {
        $existingUser = self::fetchByUsername($pdo, 'reviewers', $username);

        if ($existingUser) {
            throw new Exception("The username '$username' is already taken.");
        }

        $sql = 'INSERT INTO reviewers (username, phone_number, email) 
                VALUES (:username, :phone, :email)';
        $parameters = [
            ':username' => $username,
            ':phone' => $phone,
            ':email' => $email
        ];
        Database::query($pdo, $sql, $parameters);
    }

    // Cập nhật thông tin reviewer
    public static function updateReviewer($pdo, $id, $username, $phone, $email)
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
        Database::query($pdo, $sql, $parameters);
    }

    // Kiểm tra email đã tồn tại chưa (dùng ở trang đăng ký)
    public static function emailExists($pdo, $email)
    {
        $query = Database::query(
            $pdo,
            "SELECT COUNT(*) FROM reviewers WHERE email = :email",
            [':email' => $email]
        );
        return $query->fetchColumn() > 0;
    }

    // Thêm reviewer nhanh (admin gửi mail), trả về id mới
    public static function addReviewer($pdo, $name, $email)
    {
        $query = Database::query(
            $pdo,
            "INSERT INTO reviewers (username, email) VALUES (:username, :email)",
            [':username' => $name, ':email' => $email]
        );
        return $pdo->lastInsertId();
    }

    // Lấy email của một reviewer
    public static function getReviewerEmail($pdo, $id)
    {
        $query = Database::query($pdo, 'SELECT email FROM reviewers WHERE id = :id', [':id' => $id]);
        return $query->fetchColumn();
    }

    // Xóa reviewer và các dữ liệu liên quan
    public static function deleteReviewers($pdo, $id)
    {
        Database::query($pdo, 'DELETE FROM mails_box WHERE reviewers_id = :id', [':id' => $id]);
        Database::query($pdo, 'DELETE FROM review WHERE reviewers_id = :id', [':id' => $id]);
        Database::query($pdo, 'DELETE FROM reviewers WHERE id = :id', [':id' => $id]);
    }
}

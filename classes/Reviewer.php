<?php

class Reviewer extends User
{
    // Đếm tổng số reviewer
    public function totalReviewers($pdo)
    {
        $query = Database::query($pdo, "SELECT COUNT(*) FROM reviewers");
        $row = $query->fetch();
        return $row[0];
    }

    public function totalBannedReviewers($pdo)
    {
        return Database::query($pdo, 'SELECT COUNT(*) FROM reviewers WHERE is_banned = 1')->fetchColumn();
    }

    public function totalFilteredReviewers($pdo, $search = '')
    {
        $query = Database::query(
            $pdo,
            'SELECT COUNT(*) FROM reviewers WHERE (username LIKE :search OR email LIKE :search)',
            [':search' => '%' . $search . '%']
        );
        return $query->fetchColumn();
    }

    // Liệt kê reviewer, có tìm kiếm và sắp xếp
    public function allReviewers($pdo, $search = '', $sort = 'created_at DESC', $limit = null, $offset = 0)
    {
        $sql = 'SELECT id, username, email, phone_number, created_at, is_banned, ban_message
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
    public function getReviewers($pdo, $id)
    {
        $parameters = [':id' => $id];
        return Database::query($pdo, 'SELECT id, username, email, phone_number FROM reviewers WHERE id = :id', $parameters)->fetch();
    }

    public function getProfile($pdo, $id)
    {
        return Database::query(
            $pdo,
            'SELECT id, username, email, phone_number, created_at, is_banned, avatar_path
             FROM reviewers WHERE id = :id',
            [':id' => $id]
        )->fetch();
    }

    public function emailUsedByOther($pdo, $email, $reviewerId)
    {
        return (bool) Database::query(
            $pdo,
            'SELECT EXISTS(SELECT 1 FROM reviewers WHERE email = :email AND id <> :id)',
            [':email' => $email, ':id' => $reviewerId]
        )->fetchColumn();
    }

    public function phoneUsedByOther($pdo, $phone, $reviewerId)
    {
        return (bool) Database::query(
            $pdo,
            'SELECT EXISTS(SELECT 1 FROM reviewers WHERE phone_number = :phone AND id <> :id)',
            [':phone' => $phone, ':id' => $reviewerId]
        )->fetchColumn();
    }

    public function usernameUsedByOther($pdo, $username, $reviewerId)
    {
        return (bool) Database::query(
            $pdo,
            'SELECT EXISTS(SELECT 1 FROM reviewers WHERE username = :username AND id <> :id)',
            [':username' => $username, ':id' => $reviewerId]
        )->fetchColumn();
    }

    public function updateProfile($pdo, $id, $username, $email, $phone, $avatarPath = null)
    {
        $sql = 'UPDATE reviewers SET username = :username, email = :email, phone_number = :phone';
        $parameters = [
            ':username' => $username,
            ':email' => $email,
            ':phone' => $phone,
            ':id' => $id,
        ];

        if ($avatarPath !== null) {
            $sql .= ', avatar_path = :avatar_path';
            $parameters[':avatar_path'] = $avatarPath;
        }

        $sql .= ' WHERE id = :id';
        Database::query($pdo, $sql, $parameters);
    }

    // Thêm reviewer mới (báo lỗi nếu username đã tồn tại)
    public function insertReviewer($pdo, $username, $phone, $email, $passwordHash = null)
    {
        if (!is_string($username) || !is_string($phone) || !is_string($email)) {
            throw new InvalidArgumentException('Username, phone number, and email must be text.');
        }
        $username = trim($username);
        $phone = trim($phone);
        $email = trim($email);
        if ($username === '' || strlen($username) > 100) {
            throw new InvalidArgumentException('Username is required and must be 100 characters or fewer.');
        }
        if (!preg_match('/^\d{10}$/', $phone)) {
            throw new InvalidArgumentException('Phone number must contain exactly 10 digits.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Enter a valid email address.');
        }
        if ($this->usernameExists($pdo, $username)) {
            throw new InvalidArgumentException('That username is already taken.');
        }
        if ($this->emailExists($pdo, $email)) {
            throw new InvalidArgumentException('That email address is already registered.');
        }
        if ($this->phoneExists($pdo, $phone)) {
            throw new InvalidArgumentException('That phone number is already registered.');
        }

        $sql = 'INSERT INTO reviewers (username, phone_number, email, password_hash, avatar_path)
                VALUES (:username, :phone, :email, :password_hash, :avatar_path)';
        $parameters = [
            ':username' => $username,
            ':phone' => $phone,
            ':email' => $email,
            ':password_hash' => $passwordHash,
            ':avatar_path' => 'images/avatars/unknown avatar.jpg',
        ];
        Database::query($pdo, $sql, $parameters);
        return (int) $pdo->lastInsertId();
    }

    public function findByLogin($pdo, $identifier)
    {
        return Database::query(
            $pdo,
            'SELECT id, username, email, password_hash, is_banned, ban_message FROM reviewers
             WHERE username = :username OR email = :email LIMIT 1',
            [':username' => $identifier, ':email' => $identifier]
        )->fetch();
    }

    public function findByEmail($pdo, $email)
    {
        return Database::query(
            $pdo,
            'SELECT id, username, email FROM reviewers WHERE email = :email LIMIT 1',
            [':email' => $email]
        )->fetch();
    }

    public function setPassword($pdo, $reviewerId, $passwordHash)
    {
        Database::query(
            $pdo,
            'UPDATE reviewers SET password_hash = :password_hash WHERE id = :id',
            [':password_hash' => $passwordHash, ':id' => $reviewerId]
        );
    }

    public function createPasswordReset($pdo, $reviewerId, $tokenHash)
    {
        Database::query($pdo, 'DELETE FROM password_reset WHERE reviewer_id = :id', [':id' => $reviewerId]);
        Database::query(
            $pdo,
            'INSERT INTO password_reset (reviewer_id, token_hash, expires_at)
             VALUES (:reviewer_id, :token_hash, DATE_ADD(NOW(), INTERVAL 1 HOUR))',
            [':reviewer_id' => $reviewerId, ':token_hash' => $tokenHash]
        );
    }

    public function findValidPasswordReset($pdo, $tokenHash)
    {
        return Database::query(
            $pdo,
            'SELECT reviewer_id FROM password_reset
             WHERE token_hash = :token_hash AND used_at IS NULL AND expires_at > NOW()',
            [':token_hash' => $tokenHash]
        )->fetch();
    }

    public function completePasswordReset($pdo, $tokenHash, $reviewerId, $passwordHash)
    {
        $pdo->beginTransaction();
        try {
            $reset = Database::query(
                $pdo,
                'SELECT reviewer_id FROM password_reset
                 WHERE token_hash = :token_hash AND reviewer_id = :reviewer_id
                   AND used_at IS NULL AND expires_at > NOW() FOR UPDATE',
                [':token_hash' => $tokenHash, ':reviewer_id' => $reviewerId]
            )->fetch();

            if (!$reset) {
                $pdo->rollBack();
                return false;
            }

            $this->setPassword($pdo, $reviewerId, $passwordHash);
            Database::query(
                $pdo,
                'DELETE FROM password_reset WHERE token_hash = :token_hash',
                [':token_hash' => $tokenHash]
            );
            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    // Cập nhật thông tin reviewer
    public function updateReviewer($pdo, $id, $username, $phone, $email)
    {
        if (!is_string($username) || !is_string($phone) || !is_string($email)) {
            throw new InvalidArgumentException('Username, phone number, and email must be text.');
        }
        $username = trim($username);
        $phone = trim($phone);
        $email = trim($email);

        if ($username === '' || strlen($username) > 100) {
            throw new InvalidArgumentException('Username is required and must be 100 characters or fewer.');
        }
        if (!preg_match('/^\d{10}$/', $phone)) {
            throw new InvalidArgumentException('Phone number must contain exactly 10 digits.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Enter a valid email address.');
        }
        if ($this->usernameUsedByOther($pdo, $username, $id)) {
            throw new InvalidArgumentException('That username is already in use.');
        }
        if ($this->emailUsedByOther($pdo, $email, $id)) {
            throw new InvalidArgumentException('That email address is already registered.');
        }
        if ($this->phoneUsedByOther($pdo, $phone, $id)) {
            throw new InvalidArgumentException('That phone number is already registered.');
        }

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
    public function emailExists($pdo, $email)
    {
        $query = Database::query(
            $pdo,
            "SELECT COUNT(*) FROM reviewers WHERE email = :email",
            [':email' => $email]
        );
        return $query->fetchColumn() > 0;
    }

    public function phoneExists($pdo, $phone)
    {
        return (bool) Database::query(
            $pdo,
            'SELECT EXISTS(SELECT 1 FROM reviewers WHERE phone_number = :phone)',
            [':phone' => $phone]
        )->fetchColumn();
    }

    public function usernameExists($pdo, $username)
    {
        $query = Database::query(
            $pdo,
            'SELECT COUNT(*) FROM reviewers WHERE username = :username',
            [':username' => $username]
        );
        return $query->fetchColumn() > 0;
    }

    public function banReviewer($pdo, $id, $message)
    {
        return Database::query(
            $pdo,
            'UPDATE reviewers
             SET is_banned = 1, ban_message = :message, banned_at = NOW()
             WHERE id = :id',
            [':message' => $message, ':id' => $id]
        )->rowCount() > 0;
    }

    public function unbanReviewer($pdo, $id)
    {
        return Database::query(
            $pdo,
            'UPDATE reviewers
             SET is_banned = 0, ban_message = NULL, banned_at = NULL
             WHERE id = :id AND is_banned = 1',
            [':id' => $id]
        )->rowCount() > 0;
    }

    // Xóa reviewer và các dữ liệu liên quan
    public function deleteReviewers($pdo, $id)
    {
        Database::query($pdo, 'DELETE FROM mails_box WHERE reviewers_id = :id', [':id' => $id]);
        Database::query($pdo, 'DELETE FROM review WHERE reviewers_id = :id', [':id' => $id]);
        Database::query($pdo, 'DELETE FROM reviewers WHERE id = :id', [':id' => $id]);
    }
}

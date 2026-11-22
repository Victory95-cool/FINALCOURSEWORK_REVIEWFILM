<?php

class Admin extends User
{
    // Tìm admin theo tên đăng nhập
    public function getAdminByUsername($pdo, $username)
    {
        return $this->fetchByUsername($pdo, 'admins', $username);
    }

    public function getById($pdo, $id)
    {
        return Database::query(
            $pdo,
            'SELECT id, username, email, avatar_path, created_at FROM admins WHERE id = :id',
            [':id' => $id]
        )->fetch();
    }

    public function emailUsedByOther($pdo, $email, $adminId)
    {
        return (bool) Database::query(
            $pdo,
            'SELECT EXISTS(SELECT 1 FROM admins WHERE email = :email AND id <> :id)',
            [':email' => $email, ':id' => $adminId]
        )->fetchColumn();
    }

    public function usernameUsedByOther($pdo, $username, $adminId)
    {
        return (bool) Database::query(
            $pdo,
            'SELECT EXISTS(SELECT 1 FROM admins WHERE username = :username AND id <> :id)',
            [':username' => $username, ':id' => $adminId]
        )->fetchColumn();
    }

    public function updateProfile($pdo, $id, $username, $email, $avatarPath = null)
    {
        $sql = 'UPDATE admins SET username = :username, email = :email';
        $parameters = [':username' => $username, ':email' => $email, ':id' => $id];

        if ($avatarPath !== null) {
            $sql .= ', avatar_path = :avatar_path';
            $parameters[':avatar_path'] = $avatarPath;
        }

        $sql .= ' WHERE id = :id';
        Database::query($pdo, $sql, $parameters);
    }
}

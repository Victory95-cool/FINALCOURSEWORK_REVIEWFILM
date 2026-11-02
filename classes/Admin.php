<?php

class Admin extends User
{
    // Tìm admin theo tên đăng nhập
    public static function getAdminByUsername($pdo, $username)
    {
        return self::fetchByUsername($pdo, 'admins', $username);
    }

    // Đổi mật khẩu cho admin
    public static function updateAdminPassword($pdo, $adminId, $newPassword)
    {
        Database::query(
            $pdo,
            'UPDATE admins SET password = :password WHERE id = :id',
            [':id' => $adminId, ':password' => $newPassword]
        );
    }
}

<?php

class Database
{
    // Giữ một kết nối PDO duy nhất cho cả trang
    private static $pdo = null;

    // Trả về kết nối PDO, tự tạo lần đầu tiên được gọi
    public static function getConnection()
    {
        if (self::$pdo === null) {
            self::$pdo = new PDO(
                'mysql:host=localhost; dbname=finalcoursework; charset=utf8mb4',
                'root',
                ''
            );
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }

    // Chuẩn bị và thực thi một câu SQL (thay cho hàm query() cũ)
    public static function query($pdo, $sql, $parameters = [])
    {
        $query = $pdo->prepare($sql);
        $query->execute($parameters);
        return $query;
    }
}

<?php

// Lớp cơ sở chung cho Admin và Reviewer
abstract class User
{
    protected $id;
    protected $username;
    protected $email;
    protected $phoneNumber;

    public function __construct($id, $username, $email = null, $phoneNumber = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    // Hàm dùng chung: tìm một người dùng theo tên đăng nhập
    // $table = 'admins' hoặc 'reviewers'
    protected static function fetchByUsername($pdo, $table, $username)
    {
        $query = Database::query(
            $pdo,
            "SELECT * FROM $table WHERE username = :username",
            [':username' => $username]
        );
        $row = $query->fetch();
        return $row ? $row : null;
    }
}

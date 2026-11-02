<?php
session_start();

require_once __DIR__ . '/../../includes/bootstrap.php';
$database = Database::getConnection();

$username = $_POST['admin_user'] ?? '';
$password = $_POST['admin_password'] ?? '';

if ($username === '' || $password === '') {
    header("Location: Login.php?error=empty");
    exit();
}

$admin = Admin::getAdminByUsername($database, $username);

if ($admin && password_verify($password, $admin['password'])) {

    $_SESSION['Authorised'] = "Y";
    $_SESSION['username'] = $admin['username'];
    $_SESSION['admin_id'] = $admin['id'];

    header("Location: ../admin_review.php");
    exit();
} else {
    header("Location: Login.php?error=invalid");
    exit();
}

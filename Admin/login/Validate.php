<?php
session_start();

require __DIR__ . '/../../includes/DatabaseConnection.php';
require __DIR__ . '/../../includes/DatabaseFunction.php';

$username = $_POST['admin_user'] ?? '';
$password = $_POST['admin_password'] ?? '';

if ($username === '' || $password === '') {
    header("Location: Login.php?error=empty");
    exit();
}

$admin = getAdminByUsername($pdo, $username);

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

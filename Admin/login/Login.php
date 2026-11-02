<?php
session_start();

require_once __DIR__ . '/../../includes/bootstrap.php';
$database = Database::getConnection();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['admin_user'] ?? '');
    $password = trim($_POST['admin_password'] ?? '');

    $admin = Admin::getAdminByUsername($database, $username);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['Authorised'] = "Y";
        $_SESSION['username'] = $admin['username'];
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: ../admin_review.php");
        exit();
    } else {
        $error = "Invalid username or password";

        $username = '';
        $password = '';
    }
}

include __DIR__ . '/../../templates/login.html.php';

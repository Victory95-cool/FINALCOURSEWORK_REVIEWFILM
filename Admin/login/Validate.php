<?php
session_start();

require_once __DIR__ . '/../../includes/bootstrap.php';
$database = Database::getConnection();

$usernameInput = $_POST['admin_user'] ?? '';
$csrf->assertValid($_POST['csrf_token'] ?? null);
$password = $_POST['admin_password'] ?? '';
$username = is_string($usernameInput) ? trim($usernameInput) : '';

if ($username === '' || !is_string($password) || $password === '') {
    header("Location: Login.php?error=empty");
    exit();
}

$admin = $adminModel->getAdminByUsername($database, $username);

if ($admin && password_verify($password, $admin['password'])) {
    $adminAuth->signIn($admin);

    header("Location: ../admin_review.php");
    exit();
} else {
    header("Location: Login.php?error=invalid");
    exit();
}

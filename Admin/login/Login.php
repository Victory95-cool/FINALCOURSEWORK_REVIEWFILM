<?php
session_start();

require_once __DIR__ . '/../../includes/bootstrap.php';
$database = Database::getConnection();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$csrf->isValid($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid request token. Reload this sign-in page and try again.';
    } else {
    $usernameInput = $_POST['admin_user'] ?? '';
    $password = $_POST['admin_password'] ?? '';
    $username = is_string($usernameInput) ? trim($usernameInput) : '';

    $admin = $username !== '' ? $adminModel->getAdminByUsername($database, $username) : false;

    if ($admin && is_string($password) && password_verify($password, $admin['password'])) {
        $adminAuth->signIn($admin);
        header("Location: ../admin_review.php");
        exit();
    } else {
        $error = "Invalid username or password";

        $username = '';
    }
    }
}

include __DIR__ . '/../../templates/admin_login.html.php';

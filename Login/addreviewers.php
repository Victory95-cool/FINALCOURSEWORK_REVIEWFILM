<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$reviewerAuth->startSession();
$database = Database::getConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf->assertValid($_POST['csrf_token'] ?? null);
    $emailInput = $_POST['email'] ?? '';
    $usernameInput = $_POST['username'] ?? '';
    $phoneInput = $_POST['phone_number'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordConfirmation = $_POST['password_confirmation'] ?? '';

    try {
        $userController->signUp($database, $usernameInput, $emailInput, $phoneInput, $password, $passwordConfirmation);
        header('Location: ../index.php');
        exit();
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            $error = 'That username or email address is already registered.';
        } else {
            throw $e;
        }
    }
}

$title = 'Reviewer Sign Up';
ob_start();
include __DIR__ . '/../templates/user_addreviewers.html.php';
$output = ob_get_clean();
include __DIR__ . '/../templates/user_layout.html.php';

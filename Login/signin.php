<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$reviewerAuth->startSession();
$database = Database::getConnection();
$error = '';
$banMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$csrf->isValid($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid request token. Reload this sign-in page and try again.';
    } else {
    $identifierInput = $_POST['identifier'] ?? '';
    $password = $_POST['password'] ?? '';
    $identifier = is_string($identifierInput) ? trim($identifierInput) : '';

    $signInResult = $userController->signIn($database, $identifier, $password);
    if ($signInResult === true) {
        header('Location: ../index.php');
        exit();
    }

    if (is_array($signInResult) && !empty($signInResult['banned'])) {
        $banMessage = $signInResult['message'];
    } else {
        $error = 'Invalid username/email or password. Existing accounts must set a password using Reset password first.';
    }
    }
}

$title = 'Reviewer Sign In';
ob_start();
if ($banMessage !== null) {
    include __DIR__ . '/../templates/user_banned.html.php';
} else {
    include __DIR__ . '/../templates/user_signin.html.php';
}
$output = ob_get_clean();
include __DIR__ . '/../templates/user_layout.html.php';

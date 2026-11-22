<?php
require 'login/Check.php';
require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: reviewers.php');
    exit();
}

$csrfToken = $_POST['csrf_token'] ?? '';
if (!is_string($csrfToken) || !hash_equals($_SESSION['admin_csrf_token'] ?? '', $csrfToken)) {
    http_response_code(403);
    exit('Invalid request token. Reload the reviewers page and try again.');
}

$idInput = $_POST['id'] ?? '';
$id = filter_var($idInput, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$action = $_POST['action'] ?? '';
if ($id === false || !is_string($action)) {
    http_response_code(400);
    exit('Invalid reviewer action.');
}

$database = Database::getConnection();
if ($action === 'ban') {
    $messageInput = $_POST['message'] ?? '';
    $message = is_string($messageInput) ? trim($messageInput) : '';
    if ($message === '' || strlen($message) > 1000) {
        $_SESSION['admin_notice'] = 'Ban message is required and must be 1000 characters or fewer.';
        header('Location: reviewers.php');
        exit();
    }

    $updated = $reviewerModel->banReviewer($database, $id, $message);
    $_SESSION['admin_notice'] = $updated ? 'Reviewer has been banned.' : 'Reviewer was not found or is already banned.';
} elseif ($action === 'unban') {
    $updated = $reviewerModel->unbanReviewer($database, $id);
    $_SESSION['admin_notice'] = $updated ? 'Reviewer has been unbanned.' : 'Reviewer was not found or is not banned.';
} else {
    http_response_code(400);
    exit('Unknown reviewer action.');
}

header('Location: reviewers.php');
exit();

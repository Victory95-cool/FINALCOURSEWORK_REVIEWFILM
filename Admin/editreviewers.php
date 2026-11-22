<?php
require 'login/Check.php';
require_once __DIR__ . '/../includes/bootstrap.php';
$database = Database::getConnection();
$error = '';

if (array_key_exists('id', $_POST)) {
    $csrf->assertValid($_POST['csrf_token'] ?? null);
    $postedReviewerId = is_string($_POST['id']) || is_int($_POST['id'])
        ? filter_var($_POST['id'], FILTER_VALIDATE_INT)
        : false;
    if ($postedReviewerId === false || $postedReviewerId < 1) {
        http_response_code(400);
        exit('Invalid reviewer ID.');
    }

    try {
        $reviewerModel->updateReviewer(
            $database,
            $postedReviewerId,
            $_POST['username'] ?? '',
            $_POST['phone_number'] ?? '',
            $_POST['email'] ?? ''
        );
        header('Location: reviewers.php');
        exit();
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            $error = 'That username, email address, or phone number is already in use.';
        } else {
            throw $e;
        }
    }
}

$reviewerIdInput = $_POST['id'] ?? $_GET['id'] ?? null;
$reviewerId = is_string($reviewerIdInput) || is_int($reviewerIdInput)
    ? filter_var($reviewerIdInput, FILTER_VALIDATE_INT)
    : false;
$reviewer = $reviewerId ? $reviewerModel->getReviewers($database, $reviewerId) : false;
if (!$reviewer) {
    http_response_code(404);
    exit('Reviewer not found.');
}
if ($error !== '') {
    foreach (['username', 'phone_number', 'email'] as $field) {
        if (is_string($_POST[$field] ?? null)) {
            $reviewer[$field] = trim($_POST[$field]);
        }
    }
}

$title = 'Edit Reviewer';
ob_start();
include '../templates/admin_editreviewers.html.php';
$output = ob_get_clean();
include '../templates/admin_layout.html.php';

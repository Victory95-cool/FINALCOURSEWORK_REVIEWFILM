<?php
require 'login/Check.php';
try {
    include '../includes/DatabaseConnection.php';
    include '../includes/DatabaseFunction.php';

    $id = $_POST['id'] ?? null;
    $type = $_POST['type'] ?? '';

    if ($id) {
        switch ($type) {
            case 'review':
                deleteReview($pdo, $id);
                $redirect = 'admin_review.php';
                break;
            case 'film':
                deleteFilm($pdo, $id);
                $redirect = 'films.php';
                break;
            case 'reviewers':
                deleteReviewers($pdo, $id);
                $redirect = 'reviewers.php';
                break;
            case 'conversation':
                deleteConversation($pdo, $id);
                $redirect = 'mailbox_admin.php';
                break;
            default:
                $redirect = 'admin_review.php';
        }

        header('Location: ' . $redirect);
        exit();
    }
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
    include '../templates/admin_layout.html.php';
    exit();
}

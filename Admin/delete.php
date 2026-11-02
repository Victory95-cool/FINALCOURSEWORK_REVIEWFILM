<?php
require 'login/Check.php';
try {
    require_once __DIR__ . '/../includes/bootstrap.php';
    $database = Database::getConnection();

    $id = $_POST['id'] ?? null;
    $type = $_POST['type'] ?? '';

    if ($id) {
        switch ($type) {
            case 'review':
                Review::deleteReview($database, $id);
                $redirect = 'admin_review.php';
                break;
            case 'film':
                Film::deleteFilm($database, $id);
                $redirect = 'films.php';
                break;
            case 'reviewers':
                Reviewer::deleteReviewers($database, $id);
                $redirect = 'reviewers.php';
                break;
            case 'conversation':
                Conversation::deleteConversation($database, $id);
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

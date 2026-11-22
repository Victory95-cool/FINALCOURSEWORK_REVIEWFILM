<?php
require 'login/Check.php';
try {
    require_once __DIR__ . '/../includes/bootstrap.php';
    $database = Database::getConnection();

    $csrf->assertValid($_POST['csrf_token'] ?? null);
    $id = $_POST['id'] ?? null;
    $type = $_POST['type'] ?? '';

    if ($id) {
        switch ($type) {
            case 'review':
                $reviewModel->deleteReview($database, $id);
                $redirect = 'admin_review.php';
                break;
            case 'film':
                $filmModel->deleteFilm($database, $id);
                $redirect = 'films.php';
                break;
            case 'reviewers':
                $reviewerModel->deleteReviewers($database, $id);
                $redirect = 'reviewers.php';
                break;
            case 'conversation':
                $conversationModel->deleteConversation($database, $id);
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

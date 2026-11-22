<?php
require_once __DIR__ . '/includes/bootstrap.php';
$userController->requireLogin();
try {
    $database = Database::getConnection();
    $csrf->assertValid($_POST['csrf_token'] ?? null);
    $id = $_POST['id'] ?? null;
    $isConversation = isset($_POST['type']) && $_POST['type'] === 'conversation';

    if (!is_scalar($id) || (string) $id === '') {
        header('Location: ' . ($isConversation ? 'mailbox_reviewer.php' : 'review.php'));
        exit();
    }

    if ($isConversation) {
        if (!$conversationModel->isReviewerConversationOwner($database, $id, $userController->currentUserId())) {
            http_response_code(403);
            exit('You are not allowed to delete this conversation.');
        }
        $conversationModel->deleteConversation($database, $id);
        header('location: mailbox_reviewer.php');
    } else {
        $review = $reviewModel->getReview($database, $id);
        if (!$review || (int) $review['reviewers_id'] !== $userController->currentUserId()) {
            http_response_code(403);
            exit('You are not allowed to delete this review.');
        }
        $reviewModel->deleteReview($database, $id);
        header('location: review.php');
    }
    exit;

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}
include 'templates/user_layout.html.php';

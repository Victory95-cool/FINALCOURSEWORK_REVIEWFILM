<?php
try {
    require_once __DIR__ . '/includes/bootstrap.php';
    $database = Database::getConnection();
    $id = $_POST['id'] ?? null;
    $isConversation = isset($_POST['type']) && $_POST['type'] === 'conversation';

    if (!is_scalar($id) || (string) $id === '') {
        header('Location: ' . ($isConversation ? 'mailbox_reviewer.php' : 'review.php'));
        exit();
    }

    if ($isConversation) {
        Conversation::deleteConversation($database, $id);
        header('location: mailbox_reviewer.php');
    } else {
        Review::deleteReview($database, $id);
        header('location: review.php');
    }
    exit;

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}
include 'templates/layout.html.php';

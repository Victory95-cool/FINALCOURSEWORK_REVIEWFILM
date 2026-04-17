<?php
try {
    include 'includes/DatabaseConnection.php';
    include 'includes/DatabaseFunction.php';

    deleteReview($pdo, $_POST['id']);
    header('location: review.php');

    if (isset($_POST['type']) && $_POST['type'] === 'conversation') {
        deleteConversation($pdo, $_POST['id']);
        header('location: mailbox_reviewer.php');
    } else {
        deleteReview($pdo, $_POST['id']);
        header('location: review.php');
    }
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}
include 'templates/layout.html.php';

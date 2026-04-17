<?php
require 'login/Check.php';
include '../includes/DatabaseConnection.php';
include '../includes/DatabaseFunction.php';

try {
    if (isset($_POST['review_text'])) {
        updateReview($pdo, $_POST['id'], $_POST['review_text'], $_POST['rating'], $_POST['film']);
        header('location: admin_review.php');
    } else {
        $review = getReview($pdo, $_GET['id']);
        $films = allFilms($pdo);
        $title = 'Edit comment';
        $current_film_id = $review['film_id'];

        ob_start();
        include '../templates/admin_editreview.html.php';
        $output = ob_get_clean();
    }
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}
include '../templates/admin_layout.html.php';

<?php
require 'login/Check.php';
require_once __DIR__ . '/../includes/bootstrap.php';
$database = Database::getConnection();

try {
    if (isset($_POST['review_text'])) {
        Review::updateReview($database, $_POST['id'], $_POST['review_text'], $_POST['rating'], $_POST['film']);
        header('location: admin_review.php');
    } else {
        $review = Review::getReview($database, $_GET['id']);
        $films = Film::allFilms($database);
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

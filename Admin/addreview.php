<?php
require 'login/Check.php';
include '../includes/DatabaseConnection.php';
include '../includes/DatabaseFunction.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $review_text  = $_POST['review_text'] ?? '';
        $rating       = $_POST['rating'] ?? null;
        $reviewers_name = trim($_POST['reviewers_name'] ?? null);
        $admin_name    = trim($_POST['admin_name'] ?? null);
        $film_id      = $_POST['film'] ?? null;

        insertReview($pdo, $review_text, $rating, $reviewers_name, $film_id);

        header('Location: admin_review.php');
        exit;
    } catch (PDOException $e) {
        $title = 'Database error';
        $output = $e->getMessage();
    }
} else {

    $films = allFilms($pdo);
    $reviewers = allReviewers($pdo);

    $title = 'Add review';

    ob_start();
    include '../templates/admin_addreview.html.php';
    $output = ob_get_clean();
}

include '../templates/admin_layout.html.php';

<?php
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunction.php';

if (!isset($_SESSION['user_registered_name'])) {
    $_SESSION['user_registered_name'] = 'Dave Tran';
    $_SESSION['reviewer_id'] = 27;
}

$approvedFriend = ['Charlie Pham', 'Brian Tran', 'Frank Hoang'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $review_text  = $_POST['review_text'] ?? '';
        $rating       = $_POST['rating'] ?? null;
        $film_id      = $_POST['film'] ?? null;

        $reviewers_name = !empty($_POST['existing_reviewers'])
            ? trim($_POST['existing_reviewers'])
            : trim($_POST['reviewers_name']);

        insertReview($pdo, $review_text, $rating, $reviewers_name, $film_id);

        header('Location: review.php');
        exit;
    } catch (PDOException $e) {
        $title = 'Database error';
        $output = $e->getMessage();
    }
} else {

    $films = allFilms($pdo);
    $reviewers = allReviewers($pdo);


    $title = 'Add New Review';

    $savedName = $_SESSION['user_registered_name'];
    ob_start();
    include 'templates/addreview.html.php';
    $output = ob_get_clean();
}
include 'templates/layout.html.php';

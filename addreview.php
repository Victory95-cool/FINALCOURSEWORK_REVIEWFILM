<?php
session_start();
require_once __DIR__ . '/includes/bootstrap.php';
$database = Database::getConnection();

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

        Review::insertReview($database, $review_text, $rating, $reviewers_name, $film_id);

        header('Location: review.php');
        exit;
    } catch (PDOException $e) {
        $title = 'Database error';
        $output = $e->getMessage();
    }
} else {

    $films = Film::allFilms($database);
    $reviewers = Reviewer::allReviewers($database);


    $title = 'Add New Review';

    $savedName = $_SESSION['user_registered_name'];
    ob_start();
    include 'templates/addreview.html.php';
    $output = ob_get_clean();
}
include 'templates/layout.html.php';

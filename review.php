<?php
session_start();
try {
    include 'includes/DatabaseConnection.php';
    include 'includes/DatabaseFunction.php';

    if (!isset($_SESSION['user_registered_name'])) {
        $_SESSION['user_registered_name'] = 'Dave Tran';
        $_SESSION['reviewer_id'] = 27;
    }

    $currentUser = $_SESSION['user_registered_name'];
    $isAdmin = isset($_SESSION['is_admin']);

    $search = $_GET['search'] ?? '';
    $sort = $_GET['sort'] ?? 'r.review_date DESC';
    $reviews = allReviewsFiltered($pdo, $search, $sort);
    $totalReview = totalReview($pdo);

    $title = 'Reviews List';
    ob_start();
    include 'templates/review.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'Error';
    $output = $e->getMessage();
}
include 'templates/layout.html.php';

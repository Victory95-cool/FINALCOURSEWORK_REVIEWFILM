<?php
require 'login/Check.php';
try {
    include '../includes/DatabaseConnection.php';
    include '../includes/DatabaseFunction.php';

    $search = $_GET['search'] ?? '';
    $sort = $_GET['sort'] ?? 'r.review_date DESC';

    $reviews = allReviewsFiltered($pdo, $search, $sort);
    $totalReview = count($reviews);

    $totalReview = totalReview($pdo);

    ob_start();
    include '../templates/admin_review.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {

    $title = 'Error has occurred!';
    $output = 'Unable to connect to the database server: ' . $e->getMessage();
}

include '../templates/admin_layout.html.php';

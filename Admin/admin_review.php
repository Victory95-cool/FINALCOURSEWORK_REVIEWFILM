<?php
require 'login/Check.php';
$title = 'Manage Reviews List';
try {
    require_once __DIR__ . '/../includes/bootstrap.php';
    $database = Database::getConnection();

    $search = $_GET['search'] ?? '';
    $sort = $_GET['sort'] ?? 'r.review_date DESC';

    $totalReview = Review::totalReview($database);
    $totalItems = Review::totalFilteredReviews($database, $search);
    $pagination = Pagination::forRequest($totalItems, $_GET['page'] ?? 1);
    $reviews = Review::allReviewsFiltered(
        $database,
        $search,
        $sort,
        $pagination['itemsPerPage'],
        $pagination['offset']
    );

    ob_start();
    include '../templates/admin_review.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {

    $title = 'Error has occurred!';
    $output = 'Unable to connect to the database server: ' . $e->getMessage();
}

include '../templates/admin_layout.html.php';

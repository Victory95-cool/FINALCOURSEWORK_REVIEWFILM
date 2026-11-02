<?php
session_start();
try {
    require_once __DIR__ . '/includes/bootstrap.php';
    $database = Database::getConnection();

    if (!isset($_SESSION['user_registered_name'])) {
        $_SESSION['user_registered_name'] = 'Dave Tran';
        $_SESSION['reviewer_id'] = 27;
    }

    $currentUser = $_SESSION['user_registered_name'];
    $isAdmin = isset($_SESSION['is_admin']);

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

    $title = 'Reviews List';
    ob_start();
    include 'templates/review.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'Error';
    $output = $e->getMessage();
}
include 'templates/layout.html.php';

<?php
require_once __DIR__ . '/includes/bootstrap.php';
$reviewerAuth->startSession();
try {
    $database = Database::getConnection();

    $currentReviewerId = $userController->currentUserId();
    $isAdmin = isset($_SESSION['is_admin']);

    $search = $_GET['search'] ?? '';
    $sort = $_GET['sort'] ?? 'r.review_date DESC';
    $totalReview = $reviewModel->totalReview($database);
    $totalItems = $reviewModel->totalFilteredReviews($database, $search);
    $pagination = $paginationService->forRequest($totalItems, $_GET['page'] ?? 1);
    $reviews = $reviewModel->allReviewsFiltered(
        $database,
        $search,
        $sort,
        $pagination['itemsPerPage'],
        $pagination['offset']
    );

    $title = 'Reviews List';
    ob_start();
    include 'templates/user_review.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'Error';
    $output = $e->getMessage();
}
include 'templates/user_layout.html.php';

<?php
require 'login/Check.php';
require_once __DIR__ . '/../includes/bootstrap.php';
$database = Database::getConnection();
if (empty($_SESSION['admin_csrf_token'])) {
    $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
}
$adminNotice = $_SESSION['admin_notice'] ?? '';
unset($_SESSION['admin_notice']);

try {

    $search = $_GET['search'] ?? '';
    $sort = $_GET['sort'] ?? 'created_at DESC';

    $totalItems = $reviewerModel->totalFilteredReviewers($database, $search);
    $pagination = $paginationService->forRequest($totalItems, $_GET['page'] ?? 1);
    $reviewers = $reviewerModel->allReviewers(
        $database,
        $search,
        $sort,
        $pagination['itemsPerPage'],
        $pagination['offset']
    );
    $title = 'Reviewers List';

    $totalReviewers = $reviewerModel->totalReviewers($database);
    $totalBannedReviewers = $reviewerModel->totalBannedReviewers($database);

    ob_start();
    include '../templates/admin_reviewers.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Error: ' . $e->getMessage();
}

include '../templates/admin_layout.html.php';

<?php
require 'login/Check.php';
require_once __DIR__ . '/../includes/bootstrap.php';
$database = Database::getConnection();

try {

    $search = $_GET['search'] ?? '';
    $sort = $_GET['sort'] ?? 'created_at DESC';

    $totalItems = Reviewer::totalFilteredReviewers($database, $search);
    $pagination = Pagination::forRequest($totalItems, $_GET['page'] ?? 1);
    $reviewers = Reviewer::allReviewers(
        $database,
        $search,
        $sort,
        $pagination['itemsPerPage'],
        $pagination['offset']
    );
    $title = 'Reviewers List';

    $totalReviewers = Reviewer::totalReviewers($database);

    ob_start();
    include '../templates/admin_reviewers.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Error: ' . $e->getMessage();
}

include '../templates/admin_layout.html.php';

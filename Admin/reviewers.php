<?php
require 'login/Check.php';
include '../includes/DatabaseConnection.php';
include '../includes/DatabaseFunction.php';

try {

    $search = $_GET['search'] ?? '';
    $sort = $_GET['sort'] ?? 'created_at DESC';

    $reviewers = allReviewers($pdo, $search, $sort);
    $title = 'Reviewers List';

    $totalReviewers = totalReviewers($pdo);

    ob_start();
    include '../templates/admin_reviewers.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Error: ' . $e->getMessage();
}

include '../templates/admin_layout.html.php';

<?php
require 'login/Check.php';
include '../includes/DatabaseConnection.php';
include '../includes/DatabaseFunction.php';

if (isset($_POST['id'])) {
    updateReviewer($pdo, $_POST['id'], $_POST['username'], $_POST['phone_number'], $_POST['email']);
    header('Location: reviewers.php');
    exit();
}

$reviewer = getReviewers($pdo, $_GET['id']);
$title = 'Edit Reviewer';

ob_start();
include '../templates/admin_editreviewers.html.php';
$output = ob_get_clean();
include '../templates/admin_layout.html.php';

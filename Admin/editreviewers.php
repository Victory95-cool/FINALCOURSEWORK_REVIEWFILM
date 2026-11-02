<?php
require 'login/Check.php';
require_once __DIR__ . '/../includes/bootstrap.php';
$database = Database::getConnection();

if (isset($_POST['id'])) {
    Reviewer::updateReviewer($database, $_POST['id'], $_POST['username'], $_POST['phone_number'], $_POST['email']);
    header('Location: reviewers.php');
    exit();
}

$reviewer = Reviewer::getReviewers($database, $_GET['id']);
$title = 'Edit Reviewer';

ob_start();
include '../templates/admin_editreviewers.html.php';
$output = ob_get_clean();
include '../templates/admin_layout.html.php';

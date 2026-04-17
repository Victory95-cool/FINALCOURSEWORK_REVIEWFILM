<?php
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunction.php';

if (!isset($_SESSION['reviewer_id'])) {
    $_SESSION['user_registered_name'] = 'Dave Tran';
    $_SESSION['reviewer_id'] = 27;
}

$reviewer_id = $_SESSION['reviewer_id'];

$convs = getReviewerMailbox($pdo, $reviewer_id);

$title = "My Inbox";
ob_start();
include 'templates/reviewer_mailbox.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';

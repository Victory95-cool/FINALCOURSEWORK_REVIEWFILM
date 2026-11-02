<?php
session_start();
require_once __DIR__ . '/includes/bootstrap.php';
$database = Database::getConnection();

if (!isset($_SESSION['reviewer_id'])) {
    $_SESSION['user_registered_name'] = 'Dave Tran';
    $_SESSION['reviewer_id'] = 27;
}

$reviewer_id = $_SESSION['reviewer_id'];

$convs = Conversation::getReviewerMailbox($database, $reviewer_id);

$title = "My Inbox";
ob_start();
include 'templates/reviewer_mailbox.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';

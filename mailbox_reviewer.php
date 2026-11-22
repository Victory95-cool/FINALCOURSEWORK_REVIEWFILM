<?php
require_once __DIR__ . '/includes/bootstrap.php';
$userController->requireLogin();
$database = Database::getConnection();

$reviewer_id = $userController->currentUserId();

$convs = $conversationModel->getReviewerMailbox($database, $reviewer_id);

$title = "My Inbox";
ob_start();
include 'templates/user_reviewer_mailbox.html.php';
$output = ob_get_clean();

include 'templates/user_layout.html.php';

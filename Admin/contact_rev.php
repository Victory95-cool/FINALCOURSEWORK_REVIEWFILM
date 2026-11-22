<?php
require 'login/Check.php';
require_once __DIR__ . '/../includes/bootstrap.php';
$database = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_send_new'])) {
    $csrf->assertValid($_POST['csrf_token'] ?? null);
    $reviewer_id = $_POST['reviewer_id'];
    $subject = $_POST['subject'];
    $body = $_POST['message'];

    if (!empty($body) && !empty($reviewer_id)) {
        $conversationModel->adminCreateConversation($database, $subject, $reviewer_id, $body);

        header("Location: mailbox_admin.php?status=sent_success");
        exit();
    }
}

$allReviewers = $reviewerModel->allReviewers($database);
$title = "Contact Reviewer";
ob_start();
include '../templates/admin_contactrev.html.php';
$output = ob_get_clean();
include '../templates/admin_layout.html.php';

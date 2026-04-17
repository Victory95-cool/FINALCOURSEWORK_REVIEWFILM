<?php
require 'login/Check.php';
include '../includes/DatabaseConnection.php';
include '../includes/DatabaseFunction.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_send_new'])) {
    $reviewer_id = $_POST['reviewer_id'];
    $subject = $_POST['subject'];
    $body = $_POST['message'];

    if (!empty($body) && !empty($reviewer_id)) {
        adminCreateConversation($pdo, $subject, $reviewer_id, $body);

        header("Location: mailbox_admin.php?status=sent_success");
        exit();
    }
}

$allReviewers = allReviewers($pdo);
$title = "Contact Reviewer";
ob_start();
include '../templates/admin_contactrev.html.php';
$output = ob_get_clean();
include '../templates/admin_layout.html.php';

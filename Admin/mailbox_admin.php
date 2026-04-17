<?php
require 'login/Check.php';
include '../includes/DatabaseConnection.php';
include '../includes/DatabaseFunction.php';

$sql = "SELECT c.id, c.subject, MAX(m.created_at) as created_at, 
        (SELECT r.username FROM reviewers r 
         JOIN mails_box m2 ON r.id = m2.reviewers_id 
         WHERE m2.conversation_id = c.id LIMIT 1) as sender_name,
        SUM(CASE WHEN m.sender = 'reviewer' AND m.is_read = 0 THEN 1 ELSE 0 END) as unread_count 
        FROM conversations c 
        JOIN mails_box m ON c.id = m.conversation_id 
        GROUP BY c.id 
        ORDER BY created_at DESC";
$convs = query($pdo, $sql)->fetchAll();

$allReviewers = allReviewers($pdo);

$title = "Admin Mailbox";
ob_start();
include '../templates/admin_mailbox.html.php';
$output = ob_get_clean();

include '../templates/admin_layout.html.php';

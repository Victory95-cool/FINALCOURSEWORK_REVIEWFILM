<?php
require 'login/Check.php';
include '../includes/DatabaseConnection.php';
include '../includes/DatabaseFunction.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reply'])) {
    $conv_id = $_POST['conversation_id'];
    $rev_id = $_POST['reviewer_id'];
    $subject = "Re: " . $_POST['subject'];
    $body = $_POST['reply_message'];

    replyToConversation($pdo, $conv_id, 'admin', $rev_id, $subject, $body);

    header("Location: conversation.php?id=" . $conv_id . "&status=sent");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: mailbox_reviewer.php');
    exit();
}

try {
    $messages = getConversation($pdo, $id);
    $current_role = (strpos($_SERVER['PHP_SELF'], '/Admin/') !== false) ? 'admin' : 'reviewer';
    markAsRead($pdo, $id, $current_role);

    if (!$messages) {
        throw new Exception("Conversation not found.");
    }

    $title = "Conversation Thread - View Messages";
    ob_start();
    include '../templates/conversation.html.php';
    $output = ob_get_clean();
} catch (Exception $e) {
    $title = "Error";
    $output = "<p>" . $e->getMessage() . "</p>";
}

include '../templates/admin_layout.html.php';

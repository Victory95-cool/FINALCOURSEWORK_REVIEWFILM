<?php
require_once __DIR__ . '/includes/bootstrap.php';
$userController->requireLogin();
$database = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reply'])) {
    $csrf->assertValid($_POST['csrf_token'] ?? null);
    $conv_id = $_POST['conversation_id'] ?? null;
    if (!is_scalar($conv_id) || (string) $conv_id === '') {
        http_response_code(404);
        exit('Conversation not found.');
    }
    $rev_id = $userController->currentUserId();
    if (!$conversationModel->isReviewerConversationOwner($database, $conv_id, $rev_id)) {
        http_response_code(403);
        exit('You are not allowed to reply to this conversation.');
    }
    $subjectInput = $_POST['subject'] ?? '';
    $bodyInput = $_POST['reply_message'] ?? '';
    if (!is_string($subjectInput) || !is_string($bodyInput) || trim($bodyInput) === '') {
        http_response_code(400);
        exit('A valid reply message is required.');
    }
    $subject = "Re: " . $subjectInput;
    $body = $bodyInput;

    $conversationModel->replyToConversation($database, $conv_id, 'reviewer', $rev_id, $subject, $body);

    header("Location: conversation.php?id=" . $conv_id);
    exit();
}

$id = $_GET['id'] ?? null;
if (!is_scalar($id) || (string) $id === '') {
    header('Location: mailbox_reviewer.php');
    exit();
}

try {
    if (!$conversationModel->isReviewerConversationOwner($database, $id, $userController->currentUserId())) {
        http_response_code(403);
        exit('You are not allowed to view this conversation.');
    }
    $messages = $conversationModel->getConversation($database, $id);
    $current_role = (strpos($_SERVER['PHP_SELF'], '/Admin/') !== false) ? 'admin' : 'reviewer';
    $conversationModel->markAsRead($database, $id, $current_role);

    if (!$messages) {
        throw new Exception("Conversation not found.");
    }

    $title = "Message Details";
    ob_start();
    include 'templates/user_conversation.html.php';
    $output = ob_get_clean();
} catch (Exception $e) {
    $title = "Error";
    $output = "<p>" . $e->getMessage() . "</p>";
}

include 'templates/user_layout.html.php';

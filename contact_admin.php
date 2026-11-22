<?php
try {
    require_once __DIR__ . '/includes/bootstrap.php';
    $userController->requireLogin();
    $database = Database::getConnection();

    $title = "Contact Admin";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $csrf->assertValid($_POST['csrf_token'] ?? null);
        $reviewer_id = $userController->currentUserId();
        $name = $_SESSION['user_name'];

        $subject = $_POST['subject'] ?? 'No Subject';
        $body = $_POST['message'] ?? '';

        if (empty($body)) {
            throw new Exception("Message cannot be empty");
        }

        $conversationModel->createConversation($database, $subject, $reviewer_id, $body);

        @mail("adminwebsite123@gmail.com", $subject, "From: $name\n\n$body");

        $output = "<div class='review-box'><h2>Success</h2><p>Message sent to Admin successfully!</p><a href='review.php' class='btn-send' style='text-decoration:none'>Back to Reviews</a></div>";
    } else {
        ob_start();
        include 'templates/user_contact_admin.html.php';
        $output = ob_get_clean();
    }
} catch (Exception $e) {
    $title = "Error";
    $output = "<div class='review-box'><p style='color:red'>" . $e->getMessage() . "</p></div>";
}
include 'templates/user_layout.html.php';

<?php
session_start();

try {
    require 'includes/DatabaseConnection.php';
    require 'includes/DatabaseFunction.php';

    if (!isset($_SESSION['user_name'])) {
        $_SESSION['user_name'] = 'Dave Tran';
        $_SESSION['user_id'] = 27;
    }

    $title = "Contact Admin";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reviewer_id = $_SESSION['user_id'];
        $name = $_SESSION['user_name'];

        $subject = $_POST['subject'] ?? 'No Subject';
        $body = $_POST['message'] ?? '';

        if (empty($body)) {
            throw new Exception("Message cannot be empty");
        }

        createConversation($pdo, $subject, $reviewer_id, $body);

        @mail("adminwebsite123@gmail.com", $subject, "From: $name\n\n$body");

        $output = "<div class='review-box'><h2>Success</h2><p>Message sent to Admin successfully!</p><a href='review.php' class='btn-send' style='text-decoration:none'>Back to Reviews</a></div>";
    } else {
        ob_start();
        include 'templates/contact_admin.html.php';
        $output = ob_get_clean();
    }
} catch (Exception $e) {
    $title = "Error";
    $output = "<div class='review-box'><p style='color:red'>" . $e->getMessage() . "</p></div>";
}
include 'templates/layout.html.php';

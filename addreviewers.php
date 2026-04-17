<?php
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunction.php';

if (isset($_POST['username'])) {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);

    if (!str_ends_with($email, '@gmail.com')) {
        echo "<script>
                alert('Error: Only @gmail.com addresses are accepted. Please do not use school or work emails.'); 
                window.history.back();
              </script>";
        exit();
    }
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reviewers WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetchColumn() > 0) {
        echo "<script>alert('Error: Email already exists!'); window.history.back();</script>";
    } else {
        insertReviewer($pdo, $username, $_POST['phone_number'], $email);

        $_SESSION['user_registered_name'] = $username;

        header('Location: index.php');
        exit();
    }
}

$title = 'Add New Reviewer';
ob_start();
include 'templates/addreviewers.html.php';
$output = ob_get_clean();
include 'templates/layout.html.php';

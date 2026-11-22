<?php
require 'login/Check.php';
require_once __DIR__ . '/../includes/bootstrap.php';
$database = Database::getConnection();
$error = '';
$formValues = ['username' => '', 'phone_number' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf->assertValid($_POST['csrf_token'] ?? null);
    foreach ($formValues as $field => $_) {
        $formValues[$field] = is_string($_POST[$field] ?? null) ? trim($_POST[$field]) : '';
    }

    try {
        if ($formValues['username'] === '' || strlen($formValues['username']) > 100) {
            throw new InvalidArgumentException('Username is required and must be 100 characters or fewer.');
        }
        if (!preg_match('/^\d{10}$/', $formValues['phone_number'])) {
            throw new InvalidArgumentException('Phone number must contain exactly 10 digits.');
        }
        if (!filter_var($formValues['email'], FILTER_VALIDATE_EMAIL)
            || !str_ends_with(strtolower($formValues['email']), '@gmail.com')
        ) {
            throw new InvalidArgumentException('Enter a valid @gmail.com address.');
        }

        $reviewerModel->insertReviewer(
            $database,
            $formValues['username'],
            $formValues['phone_number'],
            $formValues['email']
        );
        header('Location: reviewers.php');
        exit();
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            $error = 'That username, email address, or phone number is already registered.';
        } else {
            throw $e;
        }
    }
}

$title = 'Add New Reviewer';
ob_start();
include '../templates/admin_addreviewers.html.php';
$output = ob_get_clean();
include '../templates/admin_layout.html.php';

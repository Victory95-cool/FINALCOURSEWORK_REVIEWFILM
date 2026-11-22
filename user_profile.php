<?php
require_once __DIR__ . '/includes/bootstrap.php';
$userController->requireLogin();

$database = Database::getConnection();
$reviewerId = $userController->currentUserId();
$reviewerAuth->startSession();
if (empty($_SESSION['profile_csrf_token'])) {
    $_SESSION['profile_csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$notice = $_SESSION['profile_notice'] ?? $_SESSION['watchlist_notice'] ?? '';
unset($_SESSION['profile_notice'], $_SESSION['watchlist_notice']);
$profile = $reviewerModel->getProfile($database, $reviewerId);
$editOpen = false;
$formValues = $profile;
if (empty($_SESSION['watchlist_csrf_token'])) {
    $_SESSION['watchlist_csrf_token'] = bin2hex(random_bytes(32));
}
if (!$profile) {
    $userController->signOut();
    http_response_code(404);
    exit('Reviewer profile not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $editOpen = true;
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!is_string($csrfToken) || !hash_equals($_SESSION['profile_csrf_token'], $csrfToken)) {
        http_response_code(403);
        exit('Invalid request token. Reload the profile and try again.');
    }

    $usernameInput = $_POST['username'] ?? '';
    $emailInput = $_POST['email'] ?? '';
    $phoneInput = $_POST['phone_number'] ?? '';
    $username = is_string($usernameInput) ? trim($usernameInput) : '';
    $email = is_string($emailInput) ? trim($emailInput) : '';
    $phone = is_string($phoneInput) ? trim($phoneInput) : '';
    $formValues['username'] = $username;
    $formValues['email'] = $email;
    $formValues['phone_number'] = $phone;

    if ($username === '' || strlen($username) > 100) {
        $error = 'Username is required and must be 100 characters or fewer.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif (!preg_match('/^\d{10,15}$/', $phone)) {
        $error = 'Phone number must contain 10 to 15 digits.';
    } elseif ($reviewerModel->usernameUsedByOther($database, $username, $reviewerId)) {
        $error = 'That username is already taken.';
    } elseif ($reviewerModel->emailUsedByOther($database, $email, $reviewerId)) {
        $error = 'That email address is already registered.';
    } else {
        $newAvatar = null;
        try {
            $newAvatar = $profileImage->saveUploaded($_FILES['avatar'] ?? null);
            $reviewerModel->updateProfile($database, $reviewerId, $username, $email, $phone, $newAvatar);
            if ($newAvatar !== null) {
                $profileImage->deleteIfManaged($profile['avatar_path'] ?? null);
            }
            $_SESSION['user_name'] = $username;
            $_SESSION['user_registered_name'] = $username;
            $_SESSION['profile_notice'] = 'Profile updated.';
            header('Location: user_profile.php');
            exit;
        } catch (PDOException $e) {
            if ($newAvatar !== null) {
                $profileImage->deleteIfManaged($newAvatar);
            }
            if ($e->getCode() === '23000') {
                $error = 'That username or email address is already in use.';
            } else {
                throw $e;
            }
        } catch (RuntimeException $e) {
            $error = $e->getMessage();
        }
    }
}

$reviews = $reviewModel->allForReviewer($database, $reviewerId);
$watchlistItems = $watchlistService->allForReviewer($database, $reviewerId);
$title = 'My Profile';
ob_start();
include __DIR__ . '/templates/user_profile.html.php';
$output = ob_get_clean();
include __DIR__ . '/templates/user_layout.html.php';

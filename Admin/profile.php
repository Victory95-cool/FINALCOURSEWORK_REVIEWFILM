<?php
require 'login/Check.php';
require_once __DIR__ . '/../includes/bootstrap.php';

$database = Database::getConnection();
$adminId = (int) $_SESSION['admin_id'];
if (empty($_SESSION['admin_profile_csrf_token'])) {
    $_SESSION['admin_profile_csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
$notice = $_SESSION['admin_profile_notice'] ?? '';
unset($_SESSION['admin_profile_notice']);
$profile = $adminModel->getById($database, $adminId);
$editOpen = false;
$formValues = $profile;
if (!$profile) {
    $adminAuth->signOut();
    http_response_code(404);
    exit('Admin profile not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $editOpen = true;
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!is_string($csrfToken) || !hash_equals($_SESSION['admin_profile_csrf_token'], $csrfToken)) {
        http_response_code(403);
        exit('Invalid request token. Reload the profile and try again.');
    }

    $usernameInput = $_POST['username'] ?? '';
    $emailInput = $_POST['email'] ?? '';
    $username = is_string($usernameInput) ? trim($usernameInput) : '';
    $email = is_string($emailInput) ? trim($emailInput) : '';
    $formValues['username'] = $username;
    $formValues['email'] = $email;

    if ($username === '' || strlen($username) > 255) {
        $error = 'Name is required and must be 255 characters or fewer.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif ($adminModel->usernameUsedByOther($database, $username, $adminId)) {
        $error = 'That admin name is already in use.';
    } elseif ($adminModel->emailUsedByOther($database, $email, $adminId)) {
        $error = 'That email address is already in use.';
    } else {
        $newAvatar = null;
        try {
            $newAvatar = $profileImage->saveUploaded($_FILES['avatar'] ?? null);
            $adminModel->updateProfile($database, $adminId, $username, $email, $newAvatar);
            if ($newAvatar !== null) {
                $profileImage->deleteIfManaged($profile['avatar_path'] ?? null);
            }
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_profile_notice'] = 'Profile updated.';
            header('Location: profile.php');
            exit;
        } catch (PDOException $e) {
            if ($newAvatar !== null) {
                $profileImage->deleteIfManaged($newAvatar);
            }
            if ($e->getCode() === '23000') {
                $error = 'That admin name or email is already in use.';
            } else {
                throw $e;
            }
        } catch (RuntimeException $e) {
            $error = $e->getMessage();
        }
    }
}

$title = 'Admin Profile';
ob_start();
include __DIR__ . '/../templates/admin_profile.html.php';
$output = ob_get_clean();
include __DIR__ . '/../templates/admin_layout.html.php';

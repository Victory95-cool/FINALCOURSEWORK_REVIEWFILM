<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$reviewerAuth->startSession();
header('Referrer-Policy: no-referrer');
$database = Database::getConnection();
$error = '';
$message = '';
$resetLink = '';
$tokenInput = $_GET['token'] ?? $_POST['token'] ?? '';
$token = is_string($tokenInput) ? trim($tokenInput) : '';
$reset = $token !== '' ? $reviewerModel->findValidPasswordReset($database, hash('sha256', $token)) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $csrf->assertValid($_POST['csrf_token'] ?? null);
    $email = is_string($_POST['email']) ? trim($_POST['email']) : '';
    $reviewer = $reviewerModel->findByEmail($database, $email);
    $message = 'If the account exists, password reset instructions have been prepared.';

    if ($reviewer) {
        $httpHost = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
        $isLocal = preg_match('/^(localhost|127\.0\.0\.1|\[::1\])(?::\d{1,5})?$/D', $httpHost) === 1;
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        if ($isLocal) {
            $host = $httpHost;
        } else {
            $serverName = (string) ($_SERVER['SERVER_NAME'] ?? '');
            $validServerName = filter_var($serverName, FILTER_VALIDATE_IP)
                || filter_var($serverName, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
            if (!$validServerName) {
                $error = 'Password reset is unavailable because the server host is not configured safely.';
                $message = '';
            }
            if ($validServerName) {
                $port = (int) ($_SERVER['SERVER_PORT'] ?? 0);
                $defaultPort = $scheme === 'https' ? 443 : 80;
                $hostName = filter_var($serverName, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
                    ? '[' . $serverName . ']'
                    : $serverName;
                $host = $hostName . ($port && $port !== $defaultPort ? ':' . $port : '');
            }
        }

        if ($error === '') {
            $plainToken = bin2hex(random_bytes(32));
            $reviewerModel->createPasswordReset($database, $reviewer['id'], hash('sha256', $plainToken));
            $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
            $resetLink = $scheme . '://' . $host . $basePath . '/password_reset.php?token=' . urlencode($plainToken);

            if (!$isLocal) {
                $sent = mail($reviewer['email'], 'Review website password reset', "Use this link within one hour to reset your password:\n\n" . $resetLink);
                if (!$sent) {
                    error_log('Unable to send password reset email for reviewer ID ' . (int) $reviewer['id']);
                }
                $resetLink = '';
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $csrf->assertValid($_POST['csrf_token'] ?? null);
    $password = $_POST['new_password'];
    $confirmation = $_POST['password_confirmation'] ?? '';
    if (!$reset) {
        $error = 'This reset link is invalid, expired, or already used. Request a new one.';
    } elseif (!is_string($password) || strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif (!is_string($confirmation) || $password !== $confirmation) {
        $error = 'Passwords do not match.';
    } elseif ($reviewerModel->completePasswordReset(
        $database,
        hash('sha256', $token),
        $reset['reviewer_id'],
        password_hash($password, PASSWORD_DEFAULT)
    )) {
        $message = 'Password updated. You can now sign in.';
        $token = '';
        $reset = null;
    } else {
        $error = 'This reset link is invalid, expired, or already used. Request a new one.';
    }
}

$title = 'Password Reset';
ob_start();
include __DIR__ . '/../templates/user_password_reset.html.php';
$output = ob_get_clean();
include __DIR__ . '/../templates/user_layout.html.php';

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isReviewerSignedIn = $userController->currentUserId() !== null;
$scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$sitePath = strpos($scriptPath, '/Login/') !== false
    ? dirname(dirname($scriptPath))
    : dirname($scriptPath);
$sitePath = rtrim($sitePath, '/');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= htmlspecialchars($sitePath . '/style.css', ENT_QUOTES, 'UTF-8') ?>">
    <title><?= $title ?></title>
</head>

<body>
    <header>
        <h1>🌟Review Movie Website💫</h1>
        <?php if (!$isReviewerSignedIn): ?>
            <a href="<?= htmlspecialchars($sitePath, ENT_QUOTES, 'UTF-8') ?>/Admin/login/Login.php" class="btn-admin">Admin</a>
        <?php endif; ?>
        <div class="account-actions">
            <?php if ($isReviewerSignedIn): ?>
                <form action="<?= htmlspecialchars($sitePath, ENT_QUOTES, 'UTF-8') ?>/Login/logout.php" method="post" onsubmit="return confirm('Are you sure you want to sign out?');">
                    <button type="submit" class="btn-signout">Sign Out</button>
                </form>
            <?php else: ?>
                <a href="<?= htmlspecialchars($sitePath, ENT_QUOTES, 'UTF-8') ?>/Login/signin.php" class="btn-signin">Sign In</a>
                <a href="<?= htmlspecialchars($sitePath, ENT_QUOTES, 'UTF-8') ?>/Login/addreviewers.php" class="btn-signup">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="<?= htmlspecialchars($sitePath, ENT_QUOTES, 'UTF-8') ?>/index.php">Home</a></li>
            <li><a href="<?= htmlspecialchars($sitePath, ENT_QUOTES, 'UTF-8') ?>/review.php">Reviews List</a></li>
            <li><a href="<?= htmlspecialchars($sitePath, ENT_QUOTES, 'UTF-8') ?>/films.php">Films</a></li>
            <li><a href="<?= htmlspecialchars($sitePath, ENT_QUOTES, 'UTF-8') ?>/contact_admin.php">Contact Admin</a></li>
            <li><a href="<?= htmlspecialchars($sitePath, ENT_QUOTES, 'UTF-8') ?>/mailbox_reviewer.php">Mail Box</a></li>
            <?php if ($isReviewerSignedIn): ?>
                <li><a href="<?= htmlspecialchars($sitePath, ENT_QUOTES, 'UTF-8') ?>/user_profile.php">Profile</a></li>
            <?php endif; ?>

        </ul>
    </nav>
    <main>
        <?= $output ?>
    </main>
    <footer>&copy; Review Movie Website 2026</footer>
</body>

</html>
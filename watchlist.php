<?php
require_once __DIR__ . '/includes/bootstrap.php';
$userController->requireLogin();
$reviewerAuth->startSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: films.php');
    exit();
}

$csrfToken = $_POST['csrf_token'] ?? '';
if (!is_string($csrfToken) || !hash_equals($_SESSION['watchlist_csrf_token'] ?? '', $csrfToken)) {
    http_response_code(403);
    exit('Invalid request token. Reload the film page and try again.');
}

$filmId = filter_var($_POST['film_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$action = $_POST['action'] ?? '';
$returnTo = $_POST['return_to'] ?? '';
if ($filmId === false || !is_string($action)) {
    http_response_code(400);
    exit('Invalid watchlist request.');
}

$database = Database::getConnection();
$reviewerId = $userController->currentUserId();
if ($action === 'add') {
    if (!$filmModel->getFilm($database, $filmId)) {
        http_response_code(404);
        exit('Film not found.');
    }
    $watchlistService->add($database, $reviewerId, $filmId);
    $_SESSION['watchlist_notice'] = 'Film added to your watchlist.';
} elseif ($action === 'remove') {
    $watchlistService->remove($database, $reviewerId, $filmId);
    $_SESSION['watchlist_notice'] = 'Film removed from your watchlist.';
} else {
    http_response_code(400);
    exit('Unknown watchlist action.');
}

if ($returnTo === 'user_profile.php') {
    header('Location: user_profile.php#watchlist');
} else {
    header('Location: filmdetail.php?id=' . $filmId);
}
exit();

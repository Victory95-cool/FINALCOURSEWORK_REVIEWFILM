<?php
require_once __DIR__ . '/includes/bootstrap.php';
$reviewerAuth->startSession();
$database = Database::getConnection();

try {
    $filmIdInput = $_GET['id'] ?? null;
    $filmId = is_string($filmIdInput) || is_int($filmIdInput)
        ? filter_var($filmIdInput, FILTER_VALIDATE_INT)
        : false;
    if ($filmId === false || $filmId < 1) {
        header('Location: index.php');
        exit();
    }

    $film = $filmModel->getFilmDetails($database, $filmId);
    if (!$film) {
        http_response_code(404);
        $title = 'Film Not Found';
        $output = '<p>Film not found.</p>';
        include 'templates/user_layout.html.php';
        exit();
    }
    $totalFilmReviews = $filmModel->totalReviewsForFilm($database, $filmId);
    $reviewPagination = $paginationService->forRequest($totalFilmReviews, $_GET['page'] ?? 1, 3);
    $reviews = $filmModel->getReviewsByFilmId(
        $database,
        $filmId,
        $reviewPagination['itemsPerPage'],
        $reviewPagination['offset']
    );
    $stats = $filmModel->getFilmStats($database, $filmId);
    $currentReviewerId = $userController->currentUserId();
    $isInWatchlist = $currentReviewerId !== null && $watchlistService->contains($database, $currentReviewerId, $film['id']);
    if ($currentReviewerId !== null && empty($_SESSION['watchlist_csrf_token'])) {
        $_SESSION['watchlist_csrf_token'] = bin2hex(random_bytes(32));
    }
    $watchlistNotice = $_SESSION['watchlist_notice'] ?? '';
    unset($_SESSION['watchlist_notice']);

    $title = $film['title'] . " - Movie Detail";

    ob_start();
    include 'templates/user_filmdetail.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Error: ' . $e->getMessage();
}

include 'templates/user_layout.html.php';

<?php
require_once __DIR__ . '/includes/bootstrap.php';
$userController->requireLogin();
$database = Database::getConnection();
$error = '';
$newImagePath = null;

try {
    if (isset($_POST['review_text'])) {
        $csrf->assertValid($_POST['csrf_token'] ?? null);
        $reviewId = $_POST['id'] ?? null;
        if (!is_scalar($reviewId) || (string) $reviewId === '') {
            http_response_code(404);
            exit('Review not found.');
        }
        $review = $reviewModel->getReview($database, $reviewId);
        if (!$review || (int) $review['reviewers_id'] !== $userController->currentUserId()) {
            http_response_code(403);
            exit('You are not allowed to edit this review.');
        }
        $film_id = filter_var($_POST['film'] ?? null, FILTER_VALIDATE_INT);
        $rating = filter_var($_POST['rating'] ?? null, FILTER_VALIDATE_INT);
        $reviewText = is_string($_POST['review_text'] ?? null) ? trim($_POST['review_text']) : '';
        if (!$film_id || !$filmModel->getFilm($database, $film_id)) {
            throw new InvalidArgumentException('Select a valid film.');
        }
        if ($rating === false || $rating < 1 || $rating > 5) {
            throw new InvalidArgumentException('Rating must be between 1 and 5.');
        }
        if ($reviewText === '') {
            throw new InvalidArgumentException('Review text cannot be empty.');
        }

        $newImagePath = $imageUpload->save($_FILES['meme_image'] ?? null, 'review-images');

        $reviewModel->updateReview(
            $database,
            $reviewId,
            $reviewText,
            $rating,
            $film_id,
            $newImagePath
        );
        if ($newImagePath !== null && !empty($review['image_path'])) {
            $imageUpload->deleteManaged($review['image_path'], 'review-images');
        }
        header('location: review.php');
        exit();
    } else {
        $reviewId = $_GET['id'] ?? null;
        if (!is_scalar($reviewId) || (string) $reviewId === '') {
            http_response_code(404);
            exit('Review not found.');
        }
        $review = $reviewModel->getReview($database, $reviewId);
        if (!$review || (int) $review['reviewers_id'] !== $userController->currentUserId()) {
            http_response_code(403);
            exit('You are not allowed to edit this review.');
        }
        $films = $filmModel->allFilms($database);
        $title = 'Edit comment';
        $current_film_id = $review['film_id'];

        ob_start();
        include 'templates/user_editreview.html.php';
        $output = ob_get_clean();
    }
} catch (InvalidArgumentException | RuntimeException $e) {
    $error = $e->getMessage();
    $reviewId = is_scalar($_POST['id'] ?? $_GET['id'] ?? null) ? ($_POST['id'] ?? $_GET['id']) : null;
    $review = $reviewId ? $reviewModel->getReview($database, $reviewId) : false;
    if (!$review || (int) $review['reviewers_id'] !== $userController->currentUserId()) {
        http_response_code(403);
        exit('You are not allowed to edit this review.');
    }
    $films = $filmModel->allFilms($database);
    $title = 'Edit comment';
    $current_film_id = $review['film_id'];
    ob_start();
    include 'templates/user_editreview.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    if ($newImagePath !== null) {
        $imageUpload->deleteManaged($newImagePath, 'review-images');
    }
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}
include 'templates/user_layout.html.php';

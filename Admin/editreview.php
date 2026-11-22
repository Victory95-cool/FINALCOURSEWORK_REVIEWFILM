<?php
require 'login/Check.php';
require_once __DIR__ . '/../includes/bootstrap.php';
$database = Database::getConnection();
$error = '';
$newImagePath = null;

try {
    if (isset($_POST['review_text'])) {
        $csrf->assertValid($_POST['csrf_token'] ?? null);
        $reviewId = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        $filmId = filter_var($_POST['film'] ?? null, FILTER_VALIDATE_INT);
        $rating = filter_var($_POST['rating'] ?? null, FILTER_VALIDATE_INT);
        $reviewText = is_string($_POST['review_text'] ?? null) ? trim($_POST['review_text']) : '';
        if (!$reviewId) {
            throw new InvalidArgumentException('Review not found.');
        }
        $review = $reviewModel->getReview($database, $reviewId);
        if (!$review) {
            throw new InvalidArgumentException('Review not found.');
        }
        if (!$filmId || !$filmModel->getFilm($database, $filmId)) {
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
            $filmId,
            $newImagePath
        );
        if ($newImagePath !== null && !empty($review['image_path'])) {
            $imageUpload->deleteManaged($review['image_path'], 'review-images');
        }
        header('location: admin_review.php');
        exit;
    } else {
        $review = $reviewModel->getReview($database, $_GET['id']);
        $films = $filmModel->allFilms($database);
        $title = 'Edit comment';
        $current_film_id = $review['film_id'];

        ob_start();
        include '../templates/admin_editreview.html.php';
        $output = ob_get_clean();
    }
} catch (InvalidArgumentException | RuntimeException $e) {
    $error = $e->getMessage();
    $reviewId = is_scalar($_POST['id'] ?? $_GET['id'] ?? null) ? ($_POST['id'] ?? $_GET['id']) : null;
    $review = $reviewId ? $reviewModel->getReview($database, $reviewId) : false;
    if (!$review) {
        http_response_code(404);
        exit('Review not found.');
    }
    $films = $filmModel->allFilms($database);
    $title = 'Edit comment';
    $current_film_id = $review['film_id'];
    ob_start();
    include '../templates/admin_editreview.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    if ($newImagePath !== null) {
        $imageUpload->deleteManaged($newImagePath, 'review-images');
    }
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}
include '../templates/admin_layout.html.php';

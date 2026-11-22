<?php
require_once __DIR__ . '/includes/bootstrap.php';
$userController->requireLogin();
$database = Database::getConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf->assertValid($_POST['csrf_token'] ?? null);
    $imagePath = null;
    try {
        $filmId = filter_var($_POST['film'] ?? null, FILTER_VALIDATE_INT);
        $rating = filter_var($_POST['rating'] ?? null, FILTER_VALIDATE_INT);
        $reviewText = is_string($_POST['review_text'] ?? null) ? trim($_POST['review_text']) : '';
        if (!$filmId || !$filmModel->getFilm($database, $filmId)) {
            throw new InvalidArgumentException('Select a valid film.');
        }
        if ($rating === false || $rating < 1 || $rating > 5) {
            throw new InvalidArgumentException('Rating must be between 1 and 5.');
        }
        if ($reviewText === '') {
            throw new InvalidArgumentException('Review text cannot be empty.');
        }
        $imagePath = $imageUpload->save($_FILES['meme_image'] ?? null, 'review-images');
        $reviewModel->insertReviewerReview(
            $database,
            $reviewText,
            $rating,
            $userController->currentUserId(),
            $filmId,
            $imagePath
        );
        header('Location: review.php');
        exit;
    } catch (PDOException $e) {
        if ($imagePath !== null) {
            $imageUpload->deleteManaged($imagePath, 'review-images');
        }
        error_log('Unable to save reviewer review: ' . $e->getMessage());
        $error = 'Unable to add review. Please try again.';
    } catch (InvalidArgumentException | RuntimeException $e) {
        if ($imagePath !== null) {
            $imageUpload->deleteManaged($imagePath, 'review-images');
        }
        $error = $e->getMessage();
    }
}
$films = $filmModel->allFilms($database);
$title = 'Add New Review';
$savedName = $_SESSION['user_registered_name'];
ob_start();
include 'templates/user_addreview.html.php';
$output = ob_get_clean();
include 'templates/user_layout.html.php';

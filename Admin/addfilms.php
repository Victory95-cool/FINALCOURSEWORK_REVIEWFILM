<?php
require 'login/Check.php';
require_once __DIR__ . '/../includes/bootstrap.php';
$database = Database::getConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf->assertValid($_POST['csrf_token'] ?? null);
    $posterPath = null;
    try {
        $titleInput = $_POST['title'] ?? '';
        $yearInput = $_POST['released_year'] ?? '';
        $genreInput = $_POST['genre_id'] ?? '';
        $directorInput = $_POST['director_name'] ?? '';
        if (!is_string($titleInput) || trim($titleInput) === '' || strlen(trim($titleInput)) > 255
            || !is_string($directorInput) || trim($directorInput) === '' || strlen(trim($directorInput)) > 255
        ) {
            throw new InvalidArgumentException('Film title and director are required and must be 255 characters or fewer.');
        }
        $year = is_string($yearInput) || is_int($yearInput) ? filter_var($yearInput, FILTER_VALIDATE_INT) : false;
        $genreId = is_string($genreInput) || is_int($genreInput) ? filter_var($genreInput, FILTER_VALIDATE_INT) : false;
        $validGenreIds = array_map('intval', array_column($filmModel->allGenres($database), 'id'));
        if ($year === false || $year < 1888 || $year > (int) date('Y') + 10
            || $genreId === false || !in_array($genreId, $validGenreIds, true)
        ) {
            throw new InvalidArgumentException('Choose a valid release year and genre.');
        }
        $castInput = $_POST['cast_names'] ?? [];
        if (!is_array($castInput)) {
            throw new InvalidArgumentException('Cast must be submitted as a list.');
        }
        $castNames = array_map('trim', array_filter($castInput, 'is_string'));

        if ($filmModel->duplicateUploadedPosterExists($database, $_FILES['poster_file'] ?? null)) {
            throw new InvalidArgumentException('This poster is already used by another film.');
        }

        $posterPath = $imageUpload->save($_FILES['poster_file'] ?? null, 'posters');
        if ($posterPath === null) {
            throw new InvalidArgumentException('A poster image is required.');
        }
        $database->beginTransaction();
        $filmModel->insertFilm(
            $database,
            trim($titleInput),
            $genreId,
            $year,
            $posterPath,
            is_string($_POST['description'] ?? null) ? trim($_POST['description']) : '',
            trim($directorInput),
            $castNames
        );
        $database->commit();

        header('Location: films.php');
        exit();
    } catch (PDOException $e) {
        if ($database->inTransaction()) {
            $database->rollBack();
        }
        if ($posterPath !== null) {
            $imageUpload->deleteManaged($posterPath, 'posters');
        }
        if ($e->getCode() === '23000') {
            $error = 'This film appears to duplicate an existing film.';
        } else {
            throw $e;
        }
    } catch (InvalidArgumentException | RuntimeException $e) {
        if ($database->inTransaction()) {
            $database->rollBack();
        }
        if ($posterPath !== null) {
            $imageUpload->deleteManaged($posterPath, 'posters');
        }
        $error = $e->getMessage();
    }
}

$genres = $filmModel->allGenres($database);
$directors = $filmModel->allDirectors($database);
$stars = $filmModel->allStars($database);

$title = 'Add New Film';
ob_start();
include '../templates/admin_addfilms.html.php';
$output = ob_get_clean();
include '../templates/admin_layout.html.php';

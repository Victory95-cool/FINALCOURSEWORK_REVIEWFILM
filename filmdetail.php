<?php
require_once __DIR__ . '/includes/bootstrap.php';
$database = Database::getConnection();

try {
    $filmId = $_GET['id'] ?? null;
    if (!$filmId) {
        header('Location: index.php');
        exit();
    }

    $film = Film::getFilmDetails($database, $filmId);
    $reviews = Film::getReviewsByFilmId($database, $filmId);
    $stats = Film::getFilmStats($database, $filmId);

    $title = $film['title'] . " - Movie Detail";

    ob_start();
    include 'templates/filmdetail.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Error: ' . $e->getMessage();
}

include 'templates/layout.html.php';

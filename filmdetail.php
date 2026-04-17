<?php
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunction.php';

try {
    $filmId = $_GET['id'] ?? null;
    if (!$filmId) {
        header('Location: index.php');
        exit();
    }

    $film = getFilmDetails($pdo, $filmId);
    $reviews = getReviewsByFilmId($pdo, $filmId);
    $stats = getFilmStats($pdo, $filmId);

    $title = $film['title'] . " - Movie Detail";

    ob_start();
    include 'templates/filmdetail.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Error: ' . $e->getMessage();
}

include 'templates/layout.html.php';

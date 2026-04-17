<?php
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunction.php';

try {
    $search = $_GET['search'] ?? '';
    $genre_id = $_GET['genre_id'] ?? '';
    $year_range = $_GET['year_range'] ?? '';
    $description = $_GET['description'] ?? '';
    $director = $_GET['director_name'] ?? '';
    $sort = $_GET['sort'] ?? 'f.id DESC';

    $genres = allGenres($pdo);
    $films = allFilms($pdo, $search, $genre_id, $year_range, $sort, $description, $director);

    $title = 'Films List';

    ob_start();
    include 'templates/films.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Error: ' . $e->getMessage();
}
include 'templates/layout.html.php';

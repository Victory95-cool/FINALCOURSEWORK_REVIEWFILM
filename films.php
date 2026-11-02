<?php
require_once __DIR__ . '/includes/bootstrap.php';
$database = Database::getConnection();

try {
    $search = $_GET['search'] ?? '';
    $genre_id = $_GET['genre_id'] ?? '';
    $year_range = $_GET['year_range'] ?? '';
    $director = $_GET['director_name'] ?? '';
    $sort = $_GET['sort'] ?? 'f.id DESC';

    $genres = Film::allGenres($database);
    $totalFilms = Film::totalFilms($database, $search, $genre_id, $year_range, $director);
    $pagination = Pagination::forRequest($totalFilms, $_GET['page'] ?? 1);
    $films = Film::allFilms(
        $database,
        $search,
        $genre_id,
        $year_range,
        $sort,
        $director,
        $pagination['itemsPerPage'],
        $pagination['offset']
    );

    $title = 'Films List';

    ob_start();
    include 'templates/films.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Error: ' . $e->getMessage();
}
include 'templates/layout.html.php';

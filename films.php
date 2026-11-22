<?php
require_once __DIR__ . '/includes/bootstrap.php';
$database = Database::getConnection();

try {
    $search = $_GET['search'] ?? '';
    $genre_id = $_GET['genre_id'] ?? '';
    $year_range = $_GET['year_range'] ?? '';
    $director = $_GET['director_name'] ?? '';
    $sort = $_GET['sort'] ?? 'f.id DESC';

    $genres = $filmModel->allGenres($database);
    $totalFilms = $filmModel->totalFilms($database, $search, $genre_id, $year_range, $director);
    $pagination = $paginationService->forRequest($totalFilms, $_GET['page'] ?? 1);
    $films = $filmModel->allFilms(
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
    include 'templates/user_films.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Error: ' . $e->getMessage();
}
include 'templates/user_layout.html.php';

<?php
require 'login/Check.php';
require_once __DIR__ . '/../includes/bootstrap.php';
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

    $title = 'All Films Management';

    ob_start();
    include '../templates/admin_films.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Error: ' . $e->getMessage();
}

include '../templates/admin_layout.html.php';

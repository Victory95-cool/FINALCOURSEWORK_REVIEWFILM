<?php
require 'login/Check.php';
require_once __DIR__ . '/../includes/bootstrap.php';
$database = Database::getConnection();

if (isset($_POST['title'])) {
    $posterPath = '';
    if (isset($_FILES['poster_file']) && $_FILES['poster_file']['error'] == 0) {
        $targetDir = "../images/";
        $fileName = basename($_FILES['poster_file']['name']);
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($_FILES['poster_file']['tmp_name'], $targetFilePath)) {
            $posterPath = "images/" . $fileName;
        }
    }

    Film::insertFilm($database, $_POST['title'], $_POST['genre_id'], $_POST['released_year'], $posterPath, $_POST['description'], $_POST['director_name'], $_POST['cast_names'] ?? []);

    header('Location: films.php');
    exit();
}

$genres = Film::allGenres($database);
$directors = Film::allDirectors($database);
$stars = Film::allStars($database);

$title = 'Add New Film';
ob_start();
include '../templates/admin_addfilms.html.php';
$output = ob_get_clean();
include '../templates/admin_layout.html.php';

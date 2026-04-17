<?php
require 'login/Check.php';
include '../includes/DatabaseConnection.php';
include '../includes/DatabaseFunction.php';

if (isset($_POST['id'])) {
    $posterPath = $_POST['existing_poster'];

    if (isset($_FILES['poster_file']) && $_FILES['poster_file']['error'] == 0) {
        $targetDir = "../images/";
        $fileName = basename($_FILES['poster_file']['name']);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['poster_file']['tmp_name'], $targetFilePath)) {
            $posterPath = "images/" . $fileName;
        }
    }

    updateFilm($pdo,  $_POST['id'], $_POST['title'], $_POST['genre_id'], $_POST['released_year'], $posterPath, $_POST['description'], $_POST['director_name'], $_POST['cast_names'] ?? []);

    header('Location: films.php');
    exit();
}

$film = getFilmDetails($pdo, $_GET['id']);
$genres = allGenres($pdo);
$directors = allDirectors($pdo);
$stars = allStars($pdo);

$currentStars = !empty($film['cast_names']) ? array_map('trim', explode(',', $film['cast_names'])) : [];

$title = 'Edit Film';
ob_start();
include '../templates/admin_editfilms.html.php';
$output = ob_get_clean();
include '../templates/admin_layout.html.php';

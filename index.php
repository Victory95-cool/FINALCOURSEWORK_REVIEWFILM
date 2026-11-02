<?php
require_once __DIR__ . '/includes/bootstrap.php';
$database = Database::getConnection();

$films = Film::allFilms($database);
foreach ($films as &$film) {
	$film['stats'] = Film::getFilmStats($database, $film['id']);
}
unset($film);

$title = '🎞️Review Website Home';
ob_start();
include 'templates/home.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';

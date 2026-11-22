<?php
require_once __DIR__ . '/includes/bootstrap.php';
$database = Database::getConnection();

$films = $filmModel->allFilms($database);
foreach ($films as &$film) {
	$film['stats'] = $filmModel->getFilmStats($database, $film['id']);
}
unset($film);

$title = '🎞️Review Website Home';
ob_start();
include 'templates/user_home.html.php';
$output = ob_get_clean();

include 'templates/user_layout.html.php';

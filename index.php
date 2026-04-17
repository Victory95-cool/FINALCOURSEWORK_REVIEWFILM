<?php
include 'includes/DatabaseConnection.php';
include 'includes/DatabaseFunction.php';

$films = allFilms($pdo);

$title = '🎞️Review Website Home';
ob_start();
include 'templates/home.html.php';
$output = ob_get_clean();

include 'templates/layout.html.php';

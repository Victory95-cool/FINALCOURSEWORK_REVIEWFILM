<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php');
    exit();
}
$adminAuth->signOut();
header('Location: ../../index.php');
exit();

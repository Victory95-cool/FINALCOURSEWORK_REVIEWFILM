<?php
require_once __DIR__ . '/../../includes/bootstrap.php';

if (!$adminAuth->isAuthenticated()) {
    header("Location: Login.php");
    exit();
}

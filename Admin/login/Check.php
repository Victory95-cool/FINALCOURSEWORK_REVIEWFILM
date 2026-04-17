<?php
session_start();

if (!isset($_SESSION['Authorised']) || $_SESSION['Authorised'] !== "Y") {
    header("Location: Login.php");
    exit();
}

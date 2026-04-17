<?php
session_start();
session_destroy();
header('Location: review.php');
exit();

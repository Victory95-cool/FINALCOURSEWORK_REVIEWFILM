<?php

// File này tải tất cả các class, các trang chỉ cần include 1 file này
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/ReviewerAuth.php';
require_once __DIR__ . '/../classes/UserController.php';
require_once __DIR__ . '/../classes/AdminAuth.php';
require_once __DIR__ . '/../classes/Admin.php';
require_once __DIR__ . '/../classes/Reviewer.php';
require_once __DIR__ . '/../classes/Watchlist.php';
require_once __DIR__ . '/../classes/ProfileImage.php';
require_once __DIR__ . '/../classes/Film.php';
require_once __DIR__ . '/../classes/Review.php';
require_once __DIR__ . '/../classes/Conversation.php';
require_once __DIR__ . '/../classes/Pagination.php';
require_once __DIR__ . '/../classes/Csrf.php';
require_once __DIR__ . '/../classes/ImageUpload.php';

$adminModel = new Admin();
$adminAuth = new AdminAuth();
$conversationModel = new Conversation();
$csrf = new Csrf();
$filmModel = new Film();
$imageUpload = new ImageUpload();
$paginationService = new Pagination();
$profileImage = new ProfileImage();
$reviewModel = new Review();
$reviewerModel = new Reviewer();
$reviewerAuth = new ReviewerAuth();
$userController = new UserController($reviewerModel, $reviewerAuth);
$watchlistService = new Watchlist();

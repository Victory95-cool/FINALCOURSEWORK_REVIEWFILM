<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title><?= $title ?></title>
</head>

<body>
    <a href="Admin/login/Login.php" class="btn-admin">Admin</a>
    <a href="addreviewers.php" class="btn-add_reviewer">Sign Up</a>
    <header>
        <h1>🌟Review Movie Website💫</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="review.php">Reviews List</a></li>
            <li><a href="films.php">Films</a></li>
            <li><a href="contact_admin.php">Contact Admin</a></li>
            <li><a href="mailbox_reviewer.php">Mail Box</a></li>
        </ul>
    </nav>
    <main>
        <?= $output ?>
    </main>
    <footer>&copy; Review Movie Website 2026</footer>
</body>

</html>
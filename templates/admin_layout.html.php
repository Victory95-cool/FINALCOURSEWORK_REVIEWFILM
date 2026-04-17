<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../admin_style.css">
    <title><?= $title ?></title>
</head>

<body>
    <form class="form-logout" action="login/Logout.php" method="post" onsubmit="return confirm('Are you sure you want to logout?');">
        <button type="submit" class="btn-logout">Public site/Logout</button>
    </form>

    <header>
        <h1>🎫Administrator🍿</h1>
    </header>
    <nav>
        <ul>
            <li><a href="admin_review.php">Manage Review List</a></li>
            <li><a href="reviewers.php">Reviewers List</a></li>
            <li><a href="films.php">Films List</a></li>
            <li><a href="mailbox_admin.php">Mail Box</a></li>
        </ul>
    </nav>
    <main>
        <?= $output ?>
    </main>
    <footer>&copy; Review Movie Website 2026</footer>
</body>

</html>
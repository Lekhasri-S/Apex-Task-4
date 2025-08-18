<?php
// header.php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Blog</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<header>
    <h1><a href="index.php" style="text-decoration:none;color:inherit;">My Blog</a></h1>
    <nav>
        <?php if (!empty($_SESSION['user'])): ?>
            Hello, <?=htmlspecialchars($_SESSION['user'])?>
            <a href="index.php">Home</a>
            <a href="add_post.php">Add Post</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>
<hr>
<?php
// flash message
if (!empty($_SESSION['flash'])) {
    echo '<div class="msg">' . htmlspecialchars($_SESSION['flash']) . '</div>';
    unset($_SESSION['flash']);
}
?>

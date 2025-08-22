<?php
// header.php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Blog</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">
    

</head>
<body>
<header>
    <h1><a class="text-sucess bg-info" href="index.php" style="text-decoration:none;color:inherit;">My Blog</a></h1>
    <nav class="">
        <?php if (!empty($_SESSION['user'])): ?>
            Hello, <?=htmlspecialchars($_SESSION['user'])?>
            <a  href="index.php">Home</a>
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

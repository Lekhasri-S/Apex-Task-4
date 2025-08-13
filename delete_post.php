<?php
require 'db.php';
if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "You must login to delete posts.";
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$_SESSION['flash'] = "Post deleted (if existed).";
header("Location: index.php");
exit;

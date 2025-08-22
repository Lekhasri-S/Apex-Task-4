<?php
require 'db.php';

// Only admin can delete
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['flash'] = "Unauthorized action.";
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

// Delete post
$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

$_SESSION['flash'] = "Post deleted successfully.";
header("Location: index.php");
exit;

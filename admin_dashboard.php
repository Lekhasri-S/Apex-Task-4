<?php
require 'db.php';
include 'header.php';
// only allow admin
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="text-center text-primary">Welcome, Admin!</h1>
    <p class="text-center">This is your dashboard.</p>
    <div class="d-flex justify-content-center gap-3">
        <a href="add_post.php" class="btn btn-success">Add Post</a>
        <a href="index.php" class="btn btn-info">View Blog</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>

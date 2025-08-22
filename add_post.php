<?php
require 'db.php';

// Only admin can add posts
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['flash'] = "Unauthorized action.";
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $_SESSION['flash'] = "Please fill all fields.";
        header("Location: add_post.php");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO posts (title, content, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $title, $content);
    $stmt->execute();
    $stmt->close();

    $_SESSION['flash'] = "Post added successfully.";
    header("Location: index.php");
    exit;
}

include 'header.php';
?>

<div class="container mt-4">
    <h2>Add New Post</h2>
    <form method="post" class="border p-4 bg-light rounded shadow">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" rows="6" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Post</button>
    </form>
</div>

<?php include 'footer.php'; ?>

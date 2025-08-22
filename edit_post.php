<?php
require 'db.php';

// Only admin can edit
if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['flash'] = "Unauthorized access.";
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

// If POST → update post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $_SESSION['flash'] = "Fill all fields.";
        header("Location: edit_post.php?id=$id");
        exit;
    }

    $stmt = $conn->prepare("UPDATE posts SET title=?, content=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $content, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['flash'] = "Post updated.";
    header("Location: view.php?id=$id");
    exit;
}

// Fetch post
$stmt = $conn->prepare("SELECT title, content FROM posts WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$post = $res->fetch_assoc();
$stmt->close();

if (!$post) {
    $_SESSION['flash'] = "Post not found.";
    header("Location: index.php");
    exit;
}

include 'header.php';
?>

<div class="container mt-4">
    <h2>Edit Post</h2>
    <form method="post" class="border p-4 bg-light rounded shadow">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" rows="6" class="form-control" required><?= htmlspecialchars($post['content']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Update Post</button>
    </form>
</div>

<?php include 'footer.php'; ?>

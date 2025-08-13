<?php
require 'db.php';
if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "You must login to edit posts.";
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $_SESSION['flash'] = "Fill title and content.";
        header("Location: edit_post.php?id=$id");
        exit;
    }

    $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['flash'] = "Post updated.";
    header("Location: view.php?id=$id");
    exit;
}

// load existing
$stmt = $conn->prepare("SELECT title, content FROM posts WHERE id = ?");
$stmt->bind_param("i", $id); // ✅ FIXED
$stmt->execute();
$res = $stmt->get_result();
$post = $res->fetch_assoc();
$stmt->close();

if (!$post) {
    $_SESSION['flash'] = "Post not found.";
    header("Location: index.php");
    exit;
}
?>

<h2>Edit Post</h2>
<form method="post">
    <label>Title:<br>
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
    </label><br><br>
    <label>Content:<br>
        <textarea name="content" rows="8" cols="80" required><?= htmlspecialchars($post['content']) ?></textarea>
    </label><br><br>
    <button type="submit">Update</button>
</form>

<?php include 'footer.php'; ?>

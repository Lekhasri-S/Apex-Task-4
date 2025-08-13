<?php
require 'db.php';
if (empty($_SESSION['user'])) {
    $_SESSION['flash'] = "You must login to add posts.";
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $_SESSION['flash'] = "Fill title and content.";
        header("Location: add_post.php");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);
    $stmt->execute();
    $stmt->close();

    $_SESSION['flash'] = "Post added.";
    header("Location: index.php");
    exit;
}

?>

<h2>Add Post</h2>
<form method="post">
    <label>Title:<br><input type="text" name="title" required></label><br><br>
    <label>Content:<br><textarea name="content" rows="8" cols="80" required></textarea></label><br><br>
    <button type="submit">Create</button>
</form>

<?php include 'footer.php'; ?>

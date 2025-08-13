<?php
require 'db.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT id, title, content, created_at FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$post = $res->fetch_assoc();
$stmt->close();

if (!$post) {
    echo "<p>Post not found.</p>";
    include 'footer.php';
    exit;
}
?>

<h2><?= htmlspecialchars($post['title']) ?></h2>
<p><small><?= htmlspecialchars($post['created_at']) ?></small></p>
<div><?= nl2br(htmlspecialchars($post['content'])) ?></div>

<?php include 'footer.php'; ?>

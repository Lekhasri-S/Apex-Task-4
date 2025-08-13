<?php
require 'db.php';
// get posts (latest first)
$result = $conn->query("SELECT id, title, LEFT(content,200) AS excerpt, created_at FROM posts ORDER BY created_at DESC");
?>

<h2>All Posts</h2>

<?php while ($row = $result->fetch_assoc()): ?>
    <article>
        <h3><a href="view.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h3>
        <p><small><?= htmlspecialchars($row['created_at']) ?></small></p>
        <p><?= nl2br(htmlspecialchars($row['excerpt'])) ?>...</p>
        <?php if (!empty($_SESSION['user'])): ?>
            <p>
                <a href="edit_post.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
                <a href="delete_post.php?id=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Delete?')">Delete</a>
            </p>
        <?php endif; ?>
        <hr>
    </article>
<?php endwhile; ?>

<?php include 'footer.php'; ?>

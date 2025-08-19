<?php
require 'db.php';

// -------------------- SEARCH --------------------
$search = trim($_GET['q'] ?? '');
$where = '';
if ($search !== '') {
    $safe = $conn->real_escape_string($search);
    $where = "WHERE title LIKE '%$safe%' OR content LIKE '%$safe%'";
}

// -------------------- PAGINATION --------------------
$limit = 5; // posts per page
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// count total posts
$countResult = $conn->query("SELECT COUNT(*) as cnt FROM posts $where");
$totalPosts = $countResult->fetch_assoc()['cnt'];
$totalPages = ceil($totalPosts / $limit);

// get posts
$sql = "SELECT id, title, LEFT(content,200) AS excerpt, created_at 
        FROM posts 
        $where 
        ORDER BY created_at DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<h2>All Posts</h2>

<!-- Search Form -->
<form method="get" action="index.php" style="margin-bottom:20px;">
    <input type="text" name="q" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <article style="margin-bottom:20px;">
            <h3><a href="view.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h3>
            <p><small><?= htmlspecialchars($row['created_at']) ?></small></p>
            <p><?= nl2br(htmlspecialchars($row['excerpt'])) ?>...</p>
            <?php if (!empty($_SESSION['user'])): ?>
                <p>
                    <a href="edit_post.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
                    <a href="delete_post.php?id=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Delete?')">Delete</a>
                    <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-view">View</a>
                </p>
            <?php endif; ?>
            <hr>
        </article>
    <?php endwhile; ?>
<?php else: ?>
    <p>No posts found.</p>
<?php endif; ?>

<!-- Pagination -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?q=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">⬅ Prev</a>
    <?php endif; ?>

    <span>Page <?= $page ?> of <?= $totalPages ?></span>

    <?php if ($page < $totalPages): ?>
        <a href="?q=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next ➡</a>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Blog</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">
    

</head>
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
            <h3><a class="new" href="view.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h3>
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
    <p class="text-primary bg-info">No posts found.</p>
<?php endif; ?>

<!-- Pagination -->
<nav aria-label="Search results pagination">
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page - 1 ?>" aria-label="Previous">
                    <span aria-hidden="true">⬅ Prev</span>
                </a>
            </li>
        <?php endif; ?>

        <li class="page-item disabled">
            <span class="page-link">Page <?= $page ?> of <?= $totalPages ?></span>
        </li>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page + 1 ?>" aria-label="Next">
                    <span aria-hidden="true">Next ➡</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>


<?php include 'footer.php'; ?>
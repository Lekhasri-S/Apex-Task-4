<?php
include 'header.php';
require 'db.php';

// -------------------- SEARCH --------------------
$search = trim($_GET['q'] ?? '');
$where = '';
$params = [];
$types = '';

if ($search !== '') {
    $where = "WHERE title LIKE ? OR content LIKE ?";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types   .= "ss";
}

// -------------------- PAGINATION --------------------
$limit = 5; // posts per page
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// count total posts
if ($where) {
    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM posts $where");
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $countResult = $stmt->get_result();
} else {
    $countResult = $conn->query("SELECT COUNT(*) as cnt FROM posts");
}
$totalPosts = $countResult->fetch_assoc()['cnt'] ?? 0;
$totalPages = max(1, ceil($totalPosts / $limit));

// -------------------- GET POSTS --------------------
if ($where) {
    $sql = "SELECT id, title, LEFT(content,200) AS excerpt, created_at 
            FROM posts 
            $where 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);

    // add pagination params separately
    $paramsWithPagination = [...$params, $limit, $offset];
    $stmt->bind_param($types . "ii", ...$paramsWithPagination);

    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT id, title, LEFT(content,200) AS excerpt, created_at 
            FROM posts 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<h2 class="mb-4">All Posts</h2>

<!-- Search Form -->
<form method="get" action="index.php" class="mb-4 d-flex">
    <input type="text" name="q" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>" class="form-control me-2">
    <button type="submit" class="btn btn-primary">Search</button>
</form>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <article class="mb-4 p-3 border rounded shadow-sm">
            <h3><a class="new" href="view.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h3>
            <p><small class="text-muted"><?= htmlspecialchars($row['created_at']) ?></small></p>
            <p><?= nl2br(htmlspecialchars($row['excerpt'])) ?>...</p>
            
            <?php if (!empty($_SESSION['user'])): ?>
                <div class="mt-2">
                    <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success">View</a>
                    
                    <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="edit_post.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_post.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </article>
    <?php endwhile; ?>
<?php else: ?>
    <p class="text-primary bg-info p-2">No posts found.</p>
<?php endif; ?>

<!-- Pagination -->
<nav aria-label="Search results pagination">
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">⬅ Prev</a>
            </li>
        <?php endif; ?>

        <li class="page-item disabled">
            <span class="page-link">Page <?= $page ?> of <?= $totalPages ?></span>
        </li>

        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?q=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next ➡</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<?php include 'footer.php'; ?>

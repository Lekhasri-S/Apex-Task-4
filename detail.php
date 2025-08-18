<?php
require 'db.php';

// Get the post ID from the URL
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($post_id > 0) {
    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT title, content, created_at FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($title, $content, $created_at);
        $stmt->fetch();
        ?>
        
        <div class="post">
            <h2 class="post-title"><?= htmlspecialchars($title) ?></h2>
            <p class="post-meta"><small>Posted on: <?= htmlspecialchars($created_at) ?></small></p>
            <div class="post-content"><?= nl2br(htmlspecialchars($content)) ?></div>
            <p><a class="back-link" href="index.php">← Back to all posts</a></p>
        </div>

        <?php
    } else {
        echo "<p class='error-message'>Post not found.</p>";
    }

    $stmt->close();
} else {
    echo "<p class='error-message'>Invalid post ID.</p>";
}

$conn->close();
?>
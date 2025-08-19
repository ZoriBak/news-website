<?php
session_start(); 
require_once '../config/db.php'; 
include '../includes/header.php'; 

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) { // Block non-admins
    echo "<p>Access denied. Admins only.</p>"; 
    include '../includes/footer.php'; 
    exit; 
}

// Delete
if (isset($_GET['delete'])) { 
    $deleteId = (int)$_GET['delete']; 
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?"); // Delete query
    $stmt->execute([$deleteId]); 
    header("Location: articles-overview.php");
    exit; 
}



// Shows all articles
$stmt = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC); 
?>

<h1>Articles Overview</h1>

<p><a href="submit-article.php">Submit New Article</a></p> <!-- Link to create new -->

<table border="1" cellpadding="10" cellspacing="0"> <!-- Articles table -->
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Published</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!$articles): ?>
            <tr><td colspan="5">No articles found.</td></tr>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?= htmlspecialchars($article['id']) ?></td> <!-- Article ID -->
                    <td><?= htmlspecialchars($article['title']) ?></td> <!-- Article title -->
                    <td><?= $article['published'] ? 'Yes' : 'No' ?></td> 
                    <td><?= htmlspecialchars(date('F j, Y', strtotime($article['created_at']))) ?></td> <!-- Date -->
                    <td>
                        <a href="edit-article.php?id=<?= $article['id'] ?>">Edit</a> | 
                        <a href="articles-overview.php?delete=<?= $article['id'] ?>" onclick="return confirm('Are you sure?');">Delete</a> | 
                        <a href="articles-overview.php?toggle=<?= $article['id'] ?>">
                            <?= $article['published'] ? 'Unpublish' : 'Publish' ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
    </table>

<?php include '../includes/footer.php'; ?>

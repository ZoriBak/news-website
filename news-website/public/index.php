<?php 
require_once __DIR__ . '/../config/db.php'; 
include __DIR__ . '/../includes/header.php'; 


$search = trim($_GET['search'] ?? ''); 

try { // Attempt to open articles
    if ($search) { // did the user search something
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE published = 1 AND (title LIKE ? OR content LIKE ?) ORDER BY created_at DESC");
        $stmt->execute(["%$search%", "%$search%"]); 
    } else {
        $stmt = $pdo->query("SELECT * FROM articles WHERE published = 1 ORDER BY created_at DESC"); // Open all published articles
    }
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC); // Take all the results from the database and store them in a list
} catch (PDOException $e) { // Handle DB errors 
    echo "Database error: " . htmlspecialchars($e->getMessage()); // show the error
}
?> 

<?php if ($articles): ?> <!-- If there are any articles -->
    <div class="articles"> 
    <?php foreach ($articles as $article): ?> <!-- go through each article one by one so it can show on a different page -->
        <div class="article"> 
            <h2>
                <a href="article.php?id=<?php echo $article['id']; ?>"> 
                    <?php echo htmlspecialchars($article['title']); ?> 
                </a>
            </h2>
            <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p> 
            <p class="published"><strong>Published:</strong> <?php echo date('F j, Y', strtotime($article['created_at'])); ?></p> <!-- date -->
        </div>
        <hr> 
    <?php endforeach; ?>
    </div> 
<?php else: ?> <!-- If no results -->
    <p>No articles found.</p> 
<?php endif; ?> 

<?php include __DIR__ . '/../includes/footer.php';  ?>




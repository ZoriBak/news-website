<?php
require_once '../config/db.php'; 
include '../includes/header.php'; 

// Get the article ID from the URL
$articleId = (int)($_GET['id'] ?? 0); 

if ($articleId <= 0) { // Validate the id
    echo "<p>Invalid article ID.</p>"; // Show error
    include '../includes/footer.php'; 
    exit; 
}

try {
    // Open the current article
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ? AND published = 1"); 
    $stmt->execute([$articleId]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) { // If not found 
        echo "<p>Article not found.</p>"; 
        include '../includes/footer.php';
        exit; 
    }

    // Display the article
    echo "<h1>" . htmlspecialchars($article['title']) . "</h1>"; 
    echo "<p>Published: " . htmlspecialchars(date('F j, Y', strtotime($article['created_at']))) . "</p>"; 

    if ($article['image']) { // If there is an image url
        echo "<img src='" . htmlspecialchars($article['image']) . "' alt='" . htmlspecialchars($article['title']) . "' style='max-width:100%; height:auto;'>";
    }

    echo "<div>" . nl2br(htmlspecialchars($article['content'])) . "</div>"; 

    // Previous article
    $prevStmt = $pdo->prepare("SELECT id, title FROM articles WHERE published = 1 AND id < ? ORDER BY id DESC LIMIT 1");
    $prevStmt->execute([$articleId]);
    $prevArticle = $prevStmt->fetch(PDO::FETCH_ASSOC);

    // Next article
    $nextStmt = $pdo->prepare("SELECT id, title FROM articles WHERE published = 1 AND id > ? ORDER BY id ASC LIMIT 1"); 
    $nextStmt->execute([$articleId]);
    $nextArticle = $nextStmt->fetch(PDO::FETCH_ASSOC);

    echo "<div style='margin-top:20px;'>"; 
    if ($prevArticle) { // Show previous link 
        echo "<a href='article.php?id=" . $prevArticle['id'] . "'>&laquo; " . htmlspecialchars($prevArticle['title']) . "</a>";
    }
    if ($nextArticle) { // Show next link 
        echo "<a href='article.php?id=" . $nextArticle['id'] . "' style='float:right;'>" . htmlspecialchars($nextArticle['title']) . " &raquo;</a>";
    }
    echo "</div>"; 

} catch (PDOException $e) { 
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>"; 
}

include '../includes/footer.php'; 


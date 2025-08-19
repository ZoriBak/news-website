<?php
session_start(); 
require_once '../config/db.php'; 
include '../includes/header.php'; 

// Check if user is admin
if (!($_SESSION['is_admin'] ?? false)) { // Block non-admins
    echo "<p>Access denied. Admins only.</p>"; // Message
    include '../includes/footer.php';
    exit; 
}

// Get the article ID
$articleId = (int)($_GET['id'] ?? 0); //  id structure 
if ($articleId <= 0) { // Checks if the id is correct
    echo "<p>Invalid article ID.</p>"; // Error message
    include '../includes/footer.php';
    exit; 
}

$errors = []; // Corrects errors
$success = ''; 

try {
    // Opens article details
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?"); 
    $stmt->execute([$articleId]); // Open with id
    $article = $stmt->fetch(PDO::FETCH_ASSOC); 

    if (!$article) { // If not found
        echo "<p>Article not found.</p>"; 
        include '../includes/footer.php'; 
        exit; 
    }

    // Handle form 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
        $title = trim($_POST['title'] ?? ''); 
        $content = trim($_POST['content'] ?? ''); 
        $published = isset($_POST['published']) ? 1 : 0; 

        if (!$title || !$content) { 
            $errors[] = "Title and content cannot be empty.";
        }

        // Handle file upload if there is a new image
        if (!empty($_FILES['image']['name'])) { // If a file was uploaded
            $targetDir = '../uploads/'; 
            $targetFile = $targetDir . basename($_FILES['image']['name']); 
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)); 

            
            if (!in_array($imageFileType, ['jpg','jpeg','png','gif'])) { // Allowed images
                $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed.";
            } else {
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) { 
                    $errors[] = "Failed to upload image.";
                } else {
                    $imagePath = $targetFile; 
                }
            }
        } else {
            $imagePath = $article['image']; // keep existing image
        }

        if (!$errors) { // If no errors, update row
            $updateStmt = $pdo->prepare("
                UPDATE articles 
                SET title = ?, content = ?, image = ?, published = ? 
                WHERE id = ?
            "); //  update
            $updateStmt->execute([$title, $content, $imagePath, $published, $articleId]); // Execute update
            $success = "Article updated successfully."; // Success notice
            
            // Refresh article data
            $stmt->execute([$articleId]); // Re-run select
            $article = $stmt->fetch(PDO::FETCH_ASSOC); // Reload row
        }
    }

} catch (PDOException $e) { 
    $errors[] = "Database error: " . htmlspecialchars($e->getMessage());
}
?>

<h1>Edit Article</h1>

<?php if ($errors): ?> <!-- Show errors -->
    <ul style="color:red;">
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green;"><?= htmlspecialchars($success) ?></p> <!-- Success message -->
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data"> <!-- Edit form -->
    <label for="title">Title:</label><br>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($article['title']) ?>" required><br><br>

    <label for="content">Content:</label><br>
    <textarea name="content" id="content" rows="10" cols="50" required><?= htmlspecialchars($article['content']) ?></textarea><br><br>

    <label for="image">Image:</label><br>
    <?php if ($article['image']): ?>
        <img src="<?= htmlspecialchars($article['image']) ?>" alt="Article Image" style="max-width:200px;"><br>
    <?php endif; ?>
    <input type="file" name="image" id="image"><br><br>

    <label>
        <input type="checkbox" name="published" <?= $article['published'] ? 'checked' : '' ?>> Published
    </label><br><br>

    <button type="submit">Update Article</button>
</form>

<?php
include '../includes/footer.php'; 
?>

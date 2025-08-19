<?php
session_start(); 
require_once '../config/db.php'; 
include '../includes/header.php'; 

// Only allow logged-in admins
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) { // Block non-admins
    echo "<p>Access denied. Admins only.</p>"; 
    include '../includes/footer.php'; 
    exit; 
}

$errors = []; 
$title = ''; 
$content = ''; 
$image = ''; 
$published = 0; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $title = trim($_POST['title'] ?? ''); 
    $content = trim($_POST['content'] ?? ''); 
    $image = trim($_POST['image'] ?? ''); 
    $published = isset($_POST['published']) ? 1 : 0;

    if (!$title || !$content) { //Required fields
        $errors[] = "Title and content are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO articles (title, content, image, published, created_at) VALUES (?, ?, ?, ?, NOW())"); // Insert article
            $stmt->execute([$title, $content, $image, $published]); // Execute insert
            echo "<p>Article submitted successfully.</p>"; 
        } catch (PDOException $e) { 
            $errors[] = "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<h1>Submit New Article</h1>

<?php if ($errors): ?> <!-- Show validation errors -->
    <ul style="color:red;">
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action=""> <!-- Article submission form -->
    <label for="title">Title:</label><br>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($title) ?>" required><br><br>

    <label for="content">Content:</label><br>
    <textarea name="content" id="content" rows="8" required><?= htmlspecialchars($content) ?></textarea><br><br>

    <label for="image">Image URL:</label><br>
    <input type="text" name="image" id="image" value="<?= htmlspecialchars($image) ?>"><br><br>

    <label for="published"> 
        <input type="checkbox" name="published" id="published" value="1" <?= $published ? 'checked' : '' ?>>
        Published
    </label><br><br>

    <button type="submit">Submit Article</button>
</form>

<?php include '../includes/footer.php'; ?>

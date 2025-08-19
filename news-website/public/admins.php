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

// Shows all admins
try {
    $stmt = $pdo->query("SELECT id, email, created_at FROM users WHERE is_admin = 1 ORDER BY created_at DESC");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC); 
} catch (PDOException $e) {
    echo "<p>Error fetching admins: " . htmlspecialchars($e->getMessage()) . "</p>"; // Show error
    $admins = [];
}
?>

<h1>Administrators</h1>

<?php if (!$admins): ?>
    <p>No administrators found.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0"> <!-- Admins table -->
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Registered At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admins as $admin): ?>
            <tr>
                <td><?= htmlspecialchars($admin['id']) ?></td> <!-- Admin ID -->
                <td><?= htmlspecialchars($admin['email']) ?></td> <!-- Admin email -->
                <td><?= htmlspecialchars($admin['created_at']) ?></td> <!-- Registration date -->
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

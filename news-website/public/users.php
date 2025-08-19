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

// Delete
if (isset($_GET['delete_id'])) { 
    $deleteId = (int)$_GET['delete_id']; // Check id
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND is_admin = 0"); // Only delete non-admins
        $stmt->execute([$deleteId]); // Delete
        echo "<p>User deleted successfully.</p>"; 
    } catch (PDOException $e) { 
        echo "<p>Error deleting user: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// Fetch all regular users
try {
    $stmt = $pdo->query("SELECT id, email, created_at FROM users WHERE is_admin = 0 ORDER BY created_at DESC"); 
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC); // Read rows
} catch (PDOException $e) { 
    echo "<p>Error fetching users: " . htmlspecialchars($e->getMessage()) . "</p>";
    $users = []; 
}
?>

<h1>Regular Users</h1>

<?php if (!$users): ?>
    <p>No regular users found.</p>
<?php else: ?>
    <table border="1" cellpadding="8" cellspacing="0"> <!-- Users table -->
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Registered At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td> <!-- User ID -->
                <td><?= htmlspecialchars($user['email']) ?></td> <!-- User email -->
                <td><?= htmlspecialchars($user['created_at']) ?></td> <!-- Registration date -->
                <td>
                    <a href="?delete_id=<?= $user['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a> <!-- Delete  -->
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>

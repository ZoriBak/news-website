<?php
session_start(); 
require_once '../config/db.php'; 

// Checks if the user is logged in and if they are an admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) { // Block non-admins
    header('HTTP/1.1 403 Forbidden'); 
    echo "<h1>Access Denied</h1><p>You do not have permission to access this page.</p>";
    exit; 
}

include '../includes/header.php'; 
?>

<h1>Admin Dashboard</h1>
<p>Welcome, <?= htmlspecialchars($_SESSION['user_email']); ?>!</p> <!-- Welcome message for the admin -->

<h2>All Users</h2>
<?php
try {
    $stmt = $pdo->query("SELECT id, email, is_admin FROM users ORDER BY id ASC"); // Shows all users
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Email</th><th>Admin</th></tr>"; 
    foreach ($users as $user) { // Shows each user row
        echo "<tr>";
        echo "<td>" . htmlspecialchars($user['id']) . "</td>"; // ID
        echo "<td>" . htmlspecialchars($user['email']) . "</td>"; // Email
        echo "<td>" . ($user['is_admin'] ? 'Yes' : 'No') . "</td>"; 
        echo "</tr>";
    }
    echo "</table>"; 
} catch (PDOException $e) { // Handle errors
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>

<?php include '../includes/footer.php'; ?>

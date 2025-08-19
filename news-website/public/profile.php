<?php
session_start(); 
require_once '../config/db.php'; 
include '../includes/header.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) { // If not logged in
    header("Location: login.php"); 
    exit; 
}

$userEmail = $_SESSION['user_email']; // Read the logged-in user's email
?>

<h1>User Profile</h1>

<p><strong>Email:</strong> <?php echo htmlspecialchars($userEmail); ?></p> <!-- Shows the users email -->

<p><a href="logout.php">Logout</a></p> 

<?php
include '../includes/footer.php'; 


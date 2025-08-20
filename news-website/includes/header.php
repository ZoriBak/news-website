<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
} 
?> 

<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta charset="UTF-8" /> 
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<title>News Website</title> 
<link rel="stylesheet" href="/assets/css/style.css" /> 
</head> 
<body> 
<header> 
<nav> 
    <a href="index.php">News</a> 
    <?php if (isset($_SESSION['user_id'])): ?> <!-- If a user is logged in -->
        <a href="profile.php">Profile</a> <!-- Show profile -->
        <a href="logout.php">Logout</a> <!-- Show logout  -->
    <?php else: ?> 
        <a href="login.php">Login</a> <!-- Link to login-->
        <a href="register.php">Register</a> <!-- Link to registration -->
    <?php endif; ?> 
</nav> 

  <form action="index.php" method="GET" style="margin-top:10px;"> 
    <input type="text" name="query" placeholder="Search articles..." value="<?= htmlspecialchars($_GET['query'] ?? '') ?>" /> 
    <button type="submit">Search</button> 
  </form> 
  <hr> 
</header> 
<main>




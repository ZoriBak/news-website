<?php 
$host = '127.0.0.1'; 
$port = 8889; 
$db   = 'news_website'; 
$user = 'root'; // Database username
$pass = 'root'; // Database password

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4"; 

try { 
    $pdo = new PDO($dsn, $user, $pass); // Create a new Php date object instance
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) { // connection errors
    die("Database connection failed: " . $e->getMessage()); // Stop and print error
}





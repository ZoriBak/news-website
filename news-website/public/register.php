<?php
session_start(); 
require_once '../config/db.php'; 
include '../includes/header.php'; 

$errors = []; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // If form was submitted
    $email = trim($_POST['email'] ?? ''); // Check email input
    $password = $_POST['password'] ?? ''; // Read password
    $confirmPassword = $_POST['confirm_password'] ?? ''; // Read confirmation

    //  Validations
    if (!$email || !$password || !$confirmPassword) { // Require all fields
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Validate email
        $errors[] = "Invalid email format.";
    } elseif ($password !== $confirmPassword) { // Ensure passwords match
        $errors[] = "Passwords do not match.";
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?"); 
            $stmt->execute([$email]); // Execute with email
            if ($stmt->fetch()) { 
                $errors[] = "Email already registered.";
            } else {
                // New user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 
                $insertStmt = $pdo->prepare("INSERT INTO users (email, password, is_admin) VALUES (?, ?, 0)"); // Create user
                $insertStmt->execute([$email, $hashedPassword]);

                // Log in user immediately
                $_SESSION['user_id'] = $pdo->lastInsertId(); // Save new user id
                $_SESSION['user_email'] = $email; // Save email
                $_SESSION['is_admin'] = false; 

                header("Location: profile.php"); 
                exit;
            }
        } catch (PDOException $e) { 
            $errors[] = "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<h1>Register</h1>

<?php
if ($errors) { // If there are errors, render them
    echo '<ul style="color:red;">';
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>"; // Escape each error
    }
    echo '</ul>';
}
?>

<form method="POST" action=""> <!-- Registration form -->
    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password" required><br><br>

    <label for="confirm_password">Confirm Password:</label><br>
    <input type="password" name="confirm_password" id="confirm_password" required><br><br>

    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>

<?php
include '../includes/footer.php'; // Include footer

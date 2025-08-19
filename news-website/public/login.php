<?php 
require_once __DIR__ . '/../config/db.php'; 
include __DIR__ . '/../includes/header.php'; 




$errors = []; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // If form submitted
    $email = trim($_POST['email'] ?? ''); 
    $password = $_POST['password'] ?? ''; // check if it matches the stored password

    if (!$email || !$password) { // Check required fields
        $errors[] = "Both email and password are required."; // validation error
    } else { 
        try { 
            // Fetch user by email
            $stmt = $pdo->prepare("SELECT id, email, password, is_admin FROM users WHERE email = ?"); 
            $stmt->execute([$email]); // Execute with provided email
            $user = $stmt->fetch(PDO::FETCH_ASSOC); 

            if ($user && password_verify($password, $user['password'])) { // Verify password
                // Login successful 
                $_SESSION['user_id'] = $user['id']; // Save user id
                $_SESSION['user_email'] = $user['email']; // Save email
                $_SESSION['is_admin'] = (bool)$user['is_admin'];

                
                if ($_SESSION['is_admin']) { 
                    header("Location: admin-dashboard.php"); 
                } else { // Regular user gets profile
                    header("Location: profile.php"); 
                }
                exit; 
            } else {
                $errors[] = "Invalid email or password."; 
            }
        } catch (PDOException $e) { // Handle DB exceptions
            $errors[] = "Database error: " . htmlspecialchars($e->getMessage()); 
        } 
    } 
} 
?> 

<h1>Login</h1> 

<?php if ($errors): ?> <!-- show validation errors if any -->
    <ul style="color:red;"> 
        <?php foreach ($errors as $error): ?> // Iterate error messages
            <li><?= htmlspecialchars($error) ?></li> 
        <?php endforeach; ?> // End foreach
    </ul> 
<?php endif; ?> 

<form method="POST" action=""> <!-- Login form -->
    <label for="email">Email:</label><br> <!-- Email  -->
    <input type="email" name="email" id="email" required><br><br> <!-- Email  details-->

    <label for="password">Password:</label><br> <!-- Password label -->
    <input type="password" name="password" id="password" required><br><br> <!-- Password details -->

    <button type="submit">Login</button> 
</form> 

<p>Don't have an account? <a href="register.php">Register here</a></p> 

<?php include '../includes/footer.php'; ?>


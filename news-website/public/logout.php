<?php
session_start(); // Start or resume the session
session_unset(); 
session_destroy(); 
header("Location: login.php"); // Redirect to login page
exit(); 

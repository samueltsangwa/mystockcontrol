<?php
// Start the session only if it hasn't already been started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'conn.php'; // Assuming your database connection is in conn.php

// Prevent redeclaration of isLoggedIn() function
if (!function_exists('isLoggedIn')) {
    // Function to check if user is logged in
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

// Fetch user data if logged in
if (isLoggedIn()) {
    $stmt = $pdo->prepare("SELECT username, profile_photo FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Store user details in session variables
        $_SESSION['username'] = $user['username'];
        $_SESSION['profile_photo'] = $user['profile_photo'] ?? 'path/to/default/profile.jpg'; // Default if no photo exists
    } else {
        logout();
    }
}
?>

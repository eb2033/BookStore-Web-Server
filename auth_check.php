<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
require 'connection.php';

// Function to check if user is a seller
function is_seller($db, $username) {
    $stmt = $db->prepare("SELECT SellerID FROM sellers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

//Check for remember token 
if (empty($_SESSION['logged_in']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    
    // Verify token against database
    $stmt = $db->prepare("SELECT username, remember_token FROM sellers WHERE remember_token IS NOT NULL");
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($user = $result->fetch_assoc()) {
        if (password_verify($token, $user['remember_token'])) {
            // Valid token found - restore session
            $_SESSION['username'] = $user['username'];
            $_SESSION['logged_in'] = true;
            $_SESSION['last_activity'] = time();
            $_SESSION['is_seller'] = is_seller($db, $user['username']);
            break;
        }
    }
}

// Verify session
if (isset($_SESSION['logged_in'])) {
    // Check if username exists in sellers table
    if (!isset($_SESSION['is_seller'])) {
        $_SESSION['is_seller'] = is_seller($db, $_SESSION['user_id']);
    }
    
    // Session timeout (10m)
    $inactive = 600;
    if (time() - $_SESSION['last_activity'] > $inactive) {
        session_unset();
        session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
        header("Location: login.php");
        exit();
    }
    $_SESSION['last_activity'] = time();
} else {
    // Not logged in - redirect to login page
    header("Location: login.php");
    exit();
}
// Additional role checking (example)
if ($_SESSION['user_type'] !== 'seller' ) {
    header("HTTP/1.1 403 Forbidden");
    exit("<h1>Access denied</h1>");
}
?>
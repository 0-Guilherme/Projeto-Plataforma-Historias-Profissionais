<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: feed.php');
    exit();
}

// Redirect to login page
header('Location: login.html');
exit();
?>
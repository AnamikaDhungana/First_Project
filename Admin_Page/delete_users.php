<?php
session_start();
include('../database_connection.php');

// Check if user ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Check if the user exists
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // Delete the user
        $deleteQuery = "DELETE FROM users WHERE id = $id";
        
        if (mysqli_query($conn, $deleteQuery)) {
            $_SESSION['success'] = "User deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting user: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "User not found.";
    }
} else {
    $_SESSION['error'] = "No user ID provided.";
}

header('Location: manage_users.php');
exit;
?>
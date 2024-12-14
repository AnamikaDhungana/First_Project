<?php
session_start();
include("database_connection.php");

// Admin credentials
$adminemail = "MasterAdmin111@gmail.com";
$adminpassword = "MasterAdmin1!";

// Get user input and sanitize it
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$pw = isset($_POST['password']) ? trim($_POST['password']) : '';

// Step 1: Check if it's the admin
if ($email === $adminemail && $pw === $adminpassword) {
    $_SESSION['userRole'] = 'Admin'; // Set a role for the admin
    $_SESSION['userEmail'] = $adminemail;
    $_SESSION['isLoggedIn'] = true;

    // Redirect to the admin page
    header("Location: ../Admin_Page/auth_admin.php");
    exit();
}

// Step 2: Check for regular user in the database
$stmt = $conn->prepare("SELECT full_name, password, phone FROM users WHERE email = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $email);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Step 3: Verify user credentials
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    // Verify the password
    if (password_verify($pw, $row['password'])) {
        // Set session variables
        $_SESSION['username'] = $row['full_name'];
        $_SESSION['phone'] = $row['phone'];
        $_SESSION['userEmail'] = $email;
        $_SESSION['isLoggedIn'] = true;

        // Redirect to user home page
        header("Location: ../Header/proceed_checkout.html");
        exit();
    } else {
        // Incorrect password
        echo "<script>
                alert('Incorrect password. Please try again.');
                window.location.href = '../Login/login.html';
              </script>";
    }
} else {
    // No user found
    echo "<script>
            alert('No user found with this email. Please register.');
            window.location.href = '../Login/register.html';
          </script>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?> 

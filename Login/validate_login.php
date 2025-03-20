<?php
session_start();
include("../database_connection.php");

// Admin Credentials
$adminEmail = "masteradmin111@gmail.com";
$adminPassword = "MasterAdmin1!";

// Getting User Input
$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Email Validation
if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@gmail\.com$/', $email)) {
    echo "<script>alert('Invalid email! Please use a valid Gmail address.'); window.location.href = '../Login/login.php';</script>";
    exit();
}

// Password Validation
if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
    echo "<script>alert('Invalid password! Must be at least 8 characters, include an uppercase letter, a lowercase letter, a number, and a special character.'); window.location.href = '../Login/login.php';</script>";
    exit();
}

// Admin Login
if ($email === $adminEmail && $password === $adminPassword) {
    $_SESSION['userEmail'] = $adminEmail;
    $_SESSION['isLoggedIn'] = true;
    $_SESSION['userRole'] = 'Admin';
    header("Location: ../Admin_Page/auth_admin.php");
    exit();
}

// Regular User Login
$stmt = $conn->prepare("SELECT id, full_name, password, phone FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        $_SESSION['userID'] = $row['id'];
        $_SESSION['username'] = $row['full_name'];
        $_SESSION['phone'] = $row['phone'];
        $_SESSION['userEmail'] = $email;
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['userRole'] = 'User';

        // Show the success alert and then redirect to products.php
        echo "<script>
                alert('You have successfully logged into our website!');
                window.location.href = '../Header/products.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('Incorrect password. Please try again.'); window.location.href = '../Login/login.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('No user found with this email. Please register.'); window.location.href = '../Login/register.php';</script>";
    exit();
}

$stmt->close();
$conn->close();
?>

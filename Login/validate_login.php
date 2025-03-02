<?php
session_start();
include("../database_connection.php");

// Admin Credentials
$adminEmail = "masteradmin111@gmail.com";
$adminPassword = "MasterAdmin1!";

// Getting User Input
$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Email Validation (No Uppercase Letters)
if (preg_match('/[A-Z]/', $email)) {
    echo "<script>alert('Email cannot contain uppercase letters.'); window.location.href = '../Login/login.php';</script>";
    exit();
}

// Email Format Validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format. Please enter a valid email.'); window.location.href = '../Login/login.php';</script>";
    exit();
}

// Password Length Validation
if (strlen($password) < 5) {
    echo "<script>alert('Password must be at least 5 characters long.'); window.location.href = '../Login/login.php';</script>";
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
        echo "<script>
                if (confirm('Are you sure you want to buy these products?')) {
                    window.location.href = '../Header/home_page.php';
                } else {
                    window.location.href = '../Header/add_to_cart.php';
                }
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
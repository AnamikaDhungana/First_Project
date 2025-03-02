<?php
session_start();
include("../database_connection.php");

// Define admin credentials
$adminemail = "masteradmin111@gmail.com"; // 
$adminpassword = "MasterAdmin1!";

// Get user input and convert email to lowercase
$email = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : ''; 
$pw = isset($_POST['password']) ? trim($_POST['password']) : '';

// ** Server-side Validation **
if (preg_match('/[A-Z]/', $_POST['email'])) {
    echo "<script>
            alert('Email cannot contain uppercase letters.');
            window.location.href = '../Login/login.php';
          </script>";
    exit();
}

// Ensure email format is correct
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
            alert('Invalid email format. Please enter a valid email.');
            window.location.href = '../Login/login.php';
          </script>";
    exit();
}

// Ensure password is at least 5 characters long
if (strlen($pw) < 5) {
    echo "<script>
            alert('Password must be at least 5 characters long.');
            window.location.href = '../Login/login.php';
          </script>";
    exit();
}

// Admin Login Check
if ($email === $adminemail && $pw === $adminpassword) {
    $_SESSION['userEmail'] = $adminemail;
    $_SESSION['isLoggedIn'] = true;
    $_SESSION['userRole'] = 'Admin';

    header("Location: ../Admin_Page/auth_admin.php");
    exit();
}

// Check for Regular User in Database
$stmt = $conn->prepare("SELECT id, full_name, password, phone FROM users WHERE email = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Verify User Credentials
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    if (password_verify($pw, $row['password'])) {
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
        echo "<script>
                alert('Incorrect password. Please try again.');
                window.location.href = '../Login/login.php';
              </script>";
        exit();
    }
} else {
    echo "<script>
            alert('No user found with this email. Please register.');
            window.location.href = '../Login/register.php';
          </script>";
    exit();
}

$stmt->close();
$conn->close();
?>

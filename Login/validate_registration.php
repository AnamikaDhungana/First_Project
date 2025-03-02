<?php
include '../database_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data   
    $full_name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $email = strtolower(mysqli_real_escape_string($conn, trim($_POST['email']))); 
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, trim($_POST['confirm-password']));

    // Validate Full Name (Only Alphabets & Minimum 6 Characters)
    if (!preg_match("/^[A-Za-z\s]{6,}$/", $full_name)) {
        echo "<script>
                alert('Full name must contain only alphabets and be at least 6 characters long.');
                window.location.href = 'register.php';
              </script>";
        exit;
    }

    // Validate Password Matching
    if ($password !== $confirm_password) {
        echo "<script>
                alert('Passwords do not match!');
                window.location.href = 'register.php';
              </script>";
        exit;
    }

    // Validate Password Length (Minimum 5 Characters)
    if (strlen($password) < 5) {
        echo "<script>
                alert('Password must be at least 5 characters long!');
                window.location.href = 'register.php';
              </script>";
        exit;
    }

    // Validate Email Format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('Invalid email format!');
                window.location.href = 'register.php';
              </script>";
        exit;
    }

    // Ensure Email Starts with a Letter & Is a Gmail Address
    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9._%+-]*[a-zA-Z]+@gmail\.com$/', $email)) {
      echo "<script>
              alert('Invalid email! It must start with a letter, contain at least one letter, and be a Gmail address.');
              window.location.href = 'register.php';
            </script>";
      exit;
  }
  
    // Validate Nepali Phone Number
    if (!preg_match("/^(9[6-8][0-9])\d{7}$/", $phone)) {
        echo "<script>
                alert('Phone number must be a valid Nepali number (e.g., 9801234567)');
                window.location.href = 'register.php';
              </script>";
        exit;
    }

    // Check if Email Already Exists
    $check_email = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);
    if (!$result) {
        echo "<script>
                alert('Database error: Failed to validate email.');
                window.location.href = 'register.php';
              </script>";
        exit;
    }

    if (mysqli_num_rows($result) > 0) {
        echo "<script>
                alert('This email is already registered!');
                window.location.href = 'register.php';
              </script>";
        exit;
    }

    // Hash the Password Before Storing It
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert Data into Database
    $sql = "INSERT INTO users (full_name, phone, email, password) 
            VALUES ('$full_name', '$phone', '$email', '$hashed_password')";

    // Execute the Query
    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Registration successful! Please login.');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: Could not complete the registration.');
                window.location.href = 'register.php';
              </script>";
    }
}

mysqli_close($conn);
?>
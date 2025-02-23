<?php
// Include the database connection file
include 'database_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data   
    $full_name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $email = mysqli_real_escape_string($conn, trim(strtolower($_POST['email'])));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, trim($_POST['confirm-password']));

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>
                alert('Passwords do not match!');
                window.location.href = 'register.php';
              </script>";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('Invalid email format!');
                window.location.href = 'register.php';
              </script>";
        exit;
    }

    // Validate Nepali phone number
    if (!preg_match("/^(9[6-8][0-9])\d{7}$/", $phone)) {
      echo "<script>
              alert('Phone number must be a valid Nepali number (e.g., 9801234567)');
              window.location.href = 'register.php';
            </script>";
      exit;
    }
  
    // Check if email already exists
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

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert data into the users table
    $sql = "INSERT INTO users (full_name, phone, email, password) 
            VALUES ('$full_name', '$phone', '$email', '$hashed_password')";

    // Execute the query
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

// Close the connection (only at the end)
mysqli_close($conn);
?>

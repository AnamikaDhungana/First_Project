<?php
include '../database_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data   
    $full_name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $email = strtolower(mysqli_real_escape_string($conn, trim($_POST['email']))); 
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, trim($_POST['confirm-password'])); // Fixed name mismatch

    // Validate Full Name (Only Alphabets & Minimum 6 Characters)
    if (!preg_match("/^[A-Za-z\s]{6,}$/", $full_name)) {
        echo "<script>
                alert('Full name must contain only alphabets and be at least 6 characters long.');
                window.location.href = 'register.php';
              </script>";
        exit;
    }

    // Validate Email Format (Allows numbers at the beginning, must be a valid Gmail address)
    if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@gmail\.com$/', $email)) {
        echo "<script>
                alert('Invalid email! It must be a valid Gmail address and can start with a number.');
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

    // Validate Password Strength
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        echo "<script>
                alert('Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a number, and a special character.');
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

    // Check if Email Already Exists
    $check_email_query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $check_email_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

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

    mysqli_stmt_close($stmt);

    // Hash the Password Before Storing It
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert Data into Database
    $insert_query = "INSERT INTO users (full_name, phone, email, password) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "ssss", $full_name, $phone, $email, $hashed_password);

    // Execute the Query
    if (mysqli_stmt_execute($stmt)) {
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

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
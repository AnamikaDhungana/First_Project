<?php
// Include the database connection file
include ('db_connection.php');

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
                window.location.href = 'register.html';
              </script>";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('Invalid email format!');
                window.location.href = 'register.html';
              </script>";
        exit;
    }

    // // Validate phone number
    // if (!preg_match("/^(98|97|96)[0-9]{8}$/", $phone)) {
    // echo "<script>
    //         alert('Phone number must be a valid Nepali number starting with 98 or 97 and contain 10 digits!');
    //         window.location.href = 'register.html';
    //       </script>";
    // exit;
    // }

    // Validate Nepali phone number with optional country code
if (!preg_match("/^\+?977?([98|97|96|97|98])\d{8}$/", $phone)) {
    echo "<script>
            alert('Phone number must be a valid Nepali number starting with +977 or 977 and followed by 9 digits!');
            window.location.href = 'register.html';
          </script>";
    exit;
}

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>
                alert('This email is already registered!');
                window.location.href = 'register.html';
              </script>";
        exit;
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert data into the users table
    $sql = "INSERT INTO users (full_name, phone, email, password) VALUES ('$full_name', '$phone', '$email', '$hashed_password')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Registration successful! Please login.');
                window.location.href = 'login.html';
              </script>";
    } else {
        echo "<script>
                alert('Error: Could not complete the registration.');
                window.location.href = 'register.html';
              </script>";
    }

    // Close the connection
    mysqli_close($conn);
}
?>

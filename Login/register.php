<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login_style.css">
    <style>
        input:invalid {
            border-color: red;
        }
        input:valid {
            border-color: green;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Create Account</h2>
            <form action="validate_registration.php" method="post">
                <!-- Full Name Validation -->
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" 
                    pattern="[A-Za-z\s]{6,}" 
                    title="Name must contain only alphabets and be at least 6 characters long." 
                    required>

                <!-- Phone Validation -->
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" 
                    pattern="(9[6-8][0-9])\d{7}" 
                    title="Enter a valid Nepali phone number (e.g., 9801234567)" 
                    required>

                <!-- Email Validation -->
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" 
                    pattern="/^[a-zA-Z][a-zA-Z0-9._%+-]*[a-zA-Z]+@gmail\.com$/" 
                    title="Email must be a valid Gmail address (e.g., example@gmail.com)" 
                    required>

                <!-- Password and Confirm Password -->
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" minlength="5" required>

                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" minlength="5" required>

                <button type="submit" class="login-btn">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here.</a></p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login_style.css">
    <script>
        function validateForm() {
            let email = document.getElementById('email').value.trim().toLowerCase();
            let password = document.getElementById('password').value.trim();
            let errorMessage = "";

            // Email Uppercase Validation
            if (/[A-Z]/.test(email)) {
                errorMessage += "Email cannot contain uppercase letters.\n";
            }

            // Email Format Validation (Gmail, allows numbers at the start)
            let emailPattern = /^[a-zA-Z0-9][a-zA-Z0-9._%+-]*@gmail\.com$/;
            if (!emailPattern.test(email)) {
                errorMessage += "Please enter a valid Gmail address.\n";
            }

            // Password Validation: At least 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special character
            let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordPattern.test(password)) {
                errorMessage += "Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.\n";
            }

            // Show Error Message
            if (errorMessage !== "") {
                alert(errorMessage);
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<div class="login-container">
    <div class="login-form">
        <h2>User Login</h2>
        <form action="validate_login.php" method="POST" onsubmit="return validateForm()">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here.</a></p>
    </div>
</div>
</body>
</html>
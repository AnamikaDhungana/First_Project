<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login_style.css"> <!-- External CSS file -->
    <script>
        function validateForm() {
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            var errorMessage = "";

            // Validate email (proper email format)
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!email.match(emailPattern)) {
                errorMessage += "Please enter a valid email address.\n";
            }

            // Validate password
            if (password === "") {
                errorMessage += "Password is required.\n";
            }

            if (errorMessage !== "") {
                alert(errorMessage);
                return false; // Prevent form submission if validation fails
            }
            return true; // Proceed with form submission if validation is successful
        }
    </script>
</head>
<body>

    <div class="login-container">
        <div class="login-form">
            <h2>User Login</h2>
            <form action="validate_login.php" method="POST">
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


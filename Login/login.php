<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login_style.css"> 
    <script>
        function validateForm() {
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            var errorMessage = "";

            if (/[A-Z]/.test(email)) {
                errorMessage += "Email cannot contain uppercase letters.\n";
            }

            var emailPattern = /^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,6}$/;
            if (!email.match(emailPattern)) {
                errorMessage += "Please enter a valid email address.\n";
            }
            
            if (password.length < 5) {
                errorMessage += "Password must be at least 5 characters long.\n";
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
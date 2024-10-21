<?php
session_start(); // Start the session

$error_message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username and password are correct
    if ($username === 'admin' && $password === 'admin') {
        // Redirect to admin dashboard page
        header('Location: admin.php'); // Change this to your actual admin dashboard file
        exit();
    } else {
        // If the credentials are incorrect, set the error message
        $error_message = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #eef2f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            transition: all 0.3s ease;
        }
        .form-container:hover {
            box-shadow: 0px 15px 25px rgba(0, 0, 0, 0.15);
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 10px;
            font-size: 35px;
            color: blue;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 24px;
            color: #333;
        }
        .form-container label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        .form-container input {
            width: calc(100% - -1px); /* Adjust width for icon */
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            color: #333;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        .form-container input:focus {
            border-color: #007bff;
            outline: none;
        }
        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }

        /* Styling for Remember me and Forgot password link */
        .form-container .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14.5px;
            margin: -10px 0 15px;
        }

        .form-container .remember-forgot label {
            display: flex;
            align-items: center;
        }

        .form-container .remember-forgot input[type="checkbox"] {
            margin-right: 5px; /* Adds gap between checkbox and label */
            accent-color: #007bff; /* Customize checkbox color */
        }

        .form-container .remember-forgot a {
            color: #007bff;
            text-decoration: none;
        }

        .form-container .remember-forgot a:hover {
            text-decoration: underline;
        }

        .form-container .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        .form-container p {
            text-align: center;
            font-size: 14px;
            color: #555;
        }

        .form-container p a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .form-container p a:hover {
            color: #0056b3;
        }
        
        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 12px;
            cursor: pointer;
            color: #333; /* Adjust the color of the icon */
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Admin Login</h1>
        <h2>Login</h2>
        <form action="" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <span class="toggle-password" onclick="togglePassword()">
                    <i class="fas fa-eye-slash" id="eye-icon"></i>
                </span>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox" id="remember" name="remember">Remember me</label>
                <a href="forgetpassword.html">Forgot password?</a>
            </div>

            <button type="submit">Login</button>

            <!-- Display error message if any -->
            <?php if (!empty($error_message)): ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </form>
        <p>Don't have an account? <a href="signup.html">Sign up</a></p>
    </div>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            // Toggle the password visibility
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text'; // Show password
                eyeIcon.classList.remove('fa-eye-slash'); // Change icon to eye-slash
                eyeIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password'; // Hide password
                eyeIcon.classList.remove('fa-eye'); // Change icon to eye
                eyeIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>

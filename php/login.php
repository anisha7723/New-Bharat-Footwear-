<?php
// Start session to keep track of the user's login status
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "e-commerece");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to find user by email and password (without hashing)
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Fetch the user data
        $user = mysqli_fetch_assoc($result);

        // Set session variables for the user
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        
        // Redirect to dashboard or homepage
        header("Location: index.php");
        exit();
    } else {
        // User not found or incorrect password
        $error_message = "Invalid email or password. Please try again.";
    }

    // Close the connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Footwear Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 40%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-size: 16px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background-color: #5a67d8;
            color: white;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #4c51bf;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Login to Footwear Paradise</h2>

        <!-- Display error message if any -->
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Login">
            </div>
        </form>

        <p style="text-align: center;">Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Footwear Paradise. All rights reserved.</p>
    </footer>

</body>
</html>

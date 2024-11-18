<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "e-commerece");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Insert user data into the database
    $query = "INSERT INTO users (name, email, password, phone, address) 
              VALUES ('$name', '$email', '$password', '$phone', '$address')";

    if (mysqli_query($conn, $query)) {
        echo "<p>Registration successful! You can now <a href='login.php'>login</a>.</p>";
    } else {
        echo "<p>Error: " . mysqli_error($conn) . "</p>";
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Footwear Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 50%;
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
        <h2>Signup for Footwear Paradise</h2>
        <form method="POST" action="signup.php">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <input type="submit" value="Sign Up">
            </div>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Footwear Paradise. All rights reserved.</p>
    </footer>

</body>
</html>

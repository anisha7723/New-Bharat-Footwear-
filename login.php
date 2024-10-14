<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your MySQL password
$dbname = "bharat_footwear"; // Your actual database name
$port = 3306;

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname, $port);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to check if the email and password match in the database
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    // Verify if a matching record was found
    if (mysqli_num_rows($result) == 1) {
        echo "Login successful! Welcome.";
        header("Location: home.html");
        exit();
    } else {
        echo "Invalid email or password.";
    }
}

// Close the connection
mysqli_close($conn);
?>

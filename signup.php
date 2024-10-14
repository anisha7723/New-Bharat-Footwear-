<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bharat_footwear";
    $port = 3306;


    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname, $port);

    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $userType = $_POST['userType'];

    // Insert the data into the database
    $sql = "INSERT INTO users (name, email, password, phone, user_type) VALUES ('$name', '$email', '$password', '$phone', '$userType')";

    if (mysqli_query($conn, $sql)) {
        echo "Registration successful!";
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close the connection
    mysqli_close($conn);
} else {
    // If the method is not POST, return 405 error
    http_response_code(405);
    echo "Method not allowed";
}
?>

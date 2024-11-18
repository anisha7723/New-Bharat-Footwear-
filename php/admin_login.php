<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check admin credentials
    $query = "SELECT * FROM admins WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $email;
        header('Location: upload_products.html');
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }
}
?>

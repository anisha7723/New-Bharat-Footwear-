<?php
$servername = "localhost"; // Replace with your database server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "bharat_footwear"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seller_id = $_POST['seller_id'];
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    
    // Handle image upload
    if ($_FILES['image_url']['error'] == UPLOAD_ERR_OK) {
        $image_dir = 'uploads/';
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0777, true);
        }

        $image_path = $image_dir . basename($_FILES['image_url']['name']);
        if (move_uploaded_file($_FILES['image_url']['tmp_name'], $image_path)) {
            $image_url = $image_path;
        } else {
            echo "Error uploading image.";
            exit;
        }
    } else {
        $image_url = null; // Image not uploaded or required
    }

    $sql = "INSERT INTO products (seller_id, product_name, description, price, stock, category, image_url)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("issdiss", $seller_id, $product_name, $description, $price, $stock, $category, $image_url);
        
        if ($stmt->execute()) {
            echo "Product added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
}
?>

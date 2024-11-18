<?php
include('db.php'); // Database connection file

if (isset($_POST['upload'])) {
    // Get form data
    $name = $_POST['name'];
    $supplier = $_POST['supplier'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $stock = $_POST['stock'];
    
    // Handle the uploaded image
    $image = $_FILES['image']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($image);

    // Check if the image file is a valid image (for security purposes)
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        echo "<script>alert('File is not an image'); window.location.href='../upload_products.html';</script>";
        exit;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<script>alert('Sorry, file already exists.'); window.location.href='../upload_products.html';</script>";
        exit;
    }

    // Limit the file size (e.g., 5MB)
    if ($_FILES['image']['size'] > 5000000) {
        echo "<script>alert('Sorry, your file is too large.'); window.location.href='../upload_products.html';</script>";
        exit;
    }

    // Allow only certain file formats (e.g., JPEG, PNG, GIF)
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.'); window.location.href='../upload_products.html';</script>";
        exit;
    }

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Insert product data into the database
        $query = "INSERT INTO products (name, supplier, category, amount, stock, image) 
                  VALUES ('$name', '$supplier', '$category', '$amount', '$stock', '$image')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Product uploaded successfully'); window.location.href='../upload_products.html';</script>";
        } else {
            echo "<script>alert('Error uploading product'); window.location.href='../upload_products.html';</script>";
        }
    } else {
        echo "<script>alert('Failed to upload image'); window.location.href='../upload_products.html';</script>";
    }
}
?>

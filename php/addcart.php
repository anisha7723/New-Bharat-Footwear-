<?php
session_start();

// Ensure the cart is initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product_id is passed in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "e-commerece");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch product details from the database
    $query = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);

        // Check if the product is already in the cart
        $product_exists = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity'] += 1;  // Increase quantity if already in cart
                $product_exists = true;
                break;
            }
        }

        // If the product is not in the cart, add it
        if (!$product_exists) {
            $_SESSION['cart'][] = [
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'price' => $product['amount'],
                'quantity' => 1
            ];
        }

        // Redirect to the cart page
        header("Location: cart.php");
        exit();
    } else {
        echo "Product not found.";
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "Invalid request.";
}
?>

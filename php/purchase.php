<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "e-commerece");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetching product_id from URL
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : null;

// Error handling if no product is selected
if (!$product_id) {
    echo "No product selected.<br>";
    echo "<a href='products.php'>Back to Products</a>";
    exit();
}

// Fetch product details using product_id
$query = "SELECT * FROM products WHERE product_id = $product_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
} else {
    echo "Product not found.";
    exit();
}

// Handling the purchase
if (isset($_POST['confirm_purchase'])) {
    $quantity = $_POST['quantity'];
    $total_amount = $quantity * $product['amount'];
    $user_id = $_SESSION['user_id']; // Assuming a user is logged in

    if ($quantity > $product['stock']) {
        echo "Insufficient stock available.";
    } else {
        // Insert into orders table
        $order_query = "INSERT INTO orders (user_id, product_id, quantity, total_amount) VALUES ('$user_id', '$product_id', '$quantity', '$total_amount')";
        if (mysqli_query($conn, $order_query)) {
            // Update stock in products table
            $new_stock = $product['stock'] - $quantity;
            $update_query = "UPDATE products SET stock = $new_stock WHERE product_id = $product_id";
            mysqli_query($conn, $update_query);

            // Display a receipt
            echo "<h2>Order Confirmation</h2>";
            echo "<p>Order placed successfully! A receipt has been generated.</p>";
            echo "<hr>";
            echo "<h3>Receipt</h3>";
            echo "<p><strong>Product:</strong> " . $product['name'] . "</p>";
            echo "<p><strong>Price per Unit:</strong> ₹" . number_format($product['amount'], 2) . "</p>";
            echo "<p><strong>Quantity:</strong> " . $quantity . "</p>";
            echo "<p><strong>Total Amount:</strong> ₹" . number_format($total_amount, 2) . "</p>";
            echo "<hr>";
            echo "<a href='products.php'>Back to Products</a>";
        } else {
            echo "Order failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Purchase</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h2 {
            color: #5a67d8;
        }
        p {
            font-size: 16px;
        }
        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }
        form {
            margin-top: 20px;
        }
        input[type="number"] {
            padding: 5px;
            width: 50px;
        }
        button {
            background-color: #5a67d8;
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4c51bf;
        }
        a {
            color: #5a67d8;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h3><?php echo $product['name']; ?></h3>
    <p>Price: ₹<?php echo number_format($product['amount'], 2); ?> per unit</p>
    <form method="POST">
        <label>Enter Quantity:</label>
        <input type="number" name="quantity" min="1" max="<?php echo $product['stock']; ?>" required>
        <button type="submit" name="confirm_purchase">Confirm Purchase</button>
    </form>
    <a href='products.php'>Back to Products</a>
</body>
</html>

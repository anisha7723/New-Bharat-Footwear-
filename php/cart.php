<?php
session_start();

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<h2>Your cart is empty.</h2>";
    echo "<a href='index.php'>Continue Shopping</a>"; // Link to go back to the shopping page
    exit();
}

// Function to calculate the total price of the cart
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Update cart quantities if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $index => $qty) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$index]); // Remove item if quantity is zero or less
        } else {
            $_SESSION['cart'][$index]['quantity'] = $qty;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index array
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        td {
            background-color: #fff;
        }
        .actions a {
            text-decoration: none;
            padding: 8px 12px;
            margin: 5px;
            display: inline-block;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .remove-btn {
            background-color: #dc3545;
            color: white;
        }
        .remove-btn:hover {
            background-color: #c82333;
        }
        .buy-btn {
            background-color: #28a745;
            color: white;
        }
        .buy-btn:hover {
            background-color: #218838;
        }
        .total {
            font-weight: bold;
            font-size: 1.2em;
            text-align: right;
            padding-right: 20px;
        }
        .update-cart, .checkout, .continue-shopping {
            text-align: center;
            margin: 20px 0;
        }
        .update-cart button, .checkout a, .continue-shopping a {
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .update-cart button:hover,
        .checkout a:hover,
        .continue-shopping a:hover {
            background-color: #0056b3;
        }
        .checkout, .continue-shopping  {
            margin-top:50px;
        }
    </style>
</head>
<body>

<h2>Shopping Cart</h2>

<form method="post">
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price (₹)</th>
                <th>Quantity</th>
                <th>Subtotal (₹)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo number_format($item['price'], 2); ?></td>
                    <td>
                        <input type="number" name="quantity[<?php echo $index; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                    </td>
                    <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td class="actions">
                        <a href="remove_from_cart.php?index=<?php echo $index; ?>" class="remove-btn">Remove</a>
                        <a href="purchase.php?product_id=<?php echo $item['product_id']; ?>" class="buy-btn">Buy</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3 class="total">Total: ₹<?php echo number_format(calculateTotal($_SESSION['cart']), 2); ?></h3>

    <div class="checkout">
        <a href="checkout.php">Proceed to Checkout</a>
    </div>
    
    <div class="continue-shopping">
        <a href="index.php">Continue Shopping</a>
    </div>
</form>

</body>
</html>

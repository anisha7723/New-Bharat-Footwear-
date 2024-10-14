<?php
session_start();

// Initialize cart array if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add item to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product = [
        'id' => $_POST['product_id'],
        'name' => $_POST['product_name'],
        'price' => $_POST['product_price'],
        'quantity' => $_POST['product_quantity']
    ];

    // Check if item already exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['id'] === $product['id']) {
            $cartItem['quantity'] += $product['quantity']; // Update quantity
            $found = true;
            break;
        }
    }

    // Add new item if not found
    if (!$found) {
        $_SESSION['cart'][] = $product;
    }

    // Redirect back to cart page
    header('Location: cart.php');
    exit();
}

// Remove item from cart
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $removeIndex = $_GET['remove'];
    if (isset($_SESSION['cart'][$removeIndex])) {
        unset($_SESSION['cart'][$removeIndex]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
    }
    header('Location: cart.php');
    exit();
}

// Display cart items
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <section class="cart">
        <div class="container">
            <h2>Your Cart</h2>
            <div class="cart-items">
                <?php if (!empty($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                        <div class="cart-item">
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <p>Price: $<?= htmlspecialchars($item['price']) ?></p>
                            <p>Quantity: <?= htmlspecialchars($item['quantity']) ?></p>
                            <p>Total: $<?= htmlspecialchars($item['price'] * $item['quantity']) ?></p>
                            <a href="cart.php?remove=<?= $index ?>">Remove</a>
                        </div>
                        <?php $total += $item['price'] * $item['quantity']; ?>
                    <?php endforeach; ?>
                    <div class="cart-summary">
                        <p>Total: $<?= $total ?></p>
                        <a href="checkout.php">Proceed to Checkout</a>
                    </div>
                <?php else: ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>
</html>

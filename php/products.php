<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footwear Store</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #333;
            color: #fff;
            padding: 15px;
            text-align: center;
        }
        .header .logo {
            font-size: 24px;
        }
        .header .nav-links {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .header .nav-links a {
            color: #fff;
            text-decoration: none;
        }
        .products-section {
            padding: 40px 20px;
            text-align: center;
        }
        .products-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .product-card {
            border: 1px solid #ddd;
            padding: 15px;
            width: 250px;
            text-align: center;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .product-card img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .product-card h3 {
            font-size: 18px;
            margin: 10px 0;
        }
        .product-card p {
            margin: 5px 0;
        }
        .product-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .product-buttons a {
            background-color: #5a67d8;
            color: #fff;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
        }
        .product-buttons a:hover {
            background-color: #4c51bf;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <header class="header">
        <h1 class="logo">Footwear Paradise</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="products.php">Shop Now</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="signup.php">Signup</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <!-- Products Section -->
    <section class="products-section">
        <h2>Featured Products</h2>
        <div class="products-grid">
            <?php
            // Database connection
            $conn = mysqli_connect("localhost", "root", "", "e-commerece");

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch all products
            $query = "SELECT * FROM products";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='product-card'>
                            <img src='uploads/{$row['image']}' alt='{$row['name']}'>
                            <h3>{$row['name']}</h3>
                            <p>Price: ₹{$row['amount']}</p>
                            <p>Stock: {$row['stock']}</p>
                            <div class='product-buttons'>
                                <a href='purchase.php?product_id={$row['product_id']}'>Buy</a>
                                <a href='addcart.php?product_id={$row['product_id']}'>Add to Cart</a>
                            </div>
                        </div>";
                }
            } else {
                echo "<p>No products found.</p>";
            }

            // Close database connection
            mysqli_close($conn);
            ?>
        </div>
    </section>
</body>
</html>

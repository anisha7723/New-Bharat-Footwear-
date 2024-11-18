<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footwear Store</title>
    <style>
        /* Internal CSS for styling */
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
        .welcome {
            background-color: #f4f4f4;
            padding: 40px 20px;
            text-align: center;
        }
        .btn {
            background-color: #5a67d8;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
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
            font-size: 14px;
        }
        .product-buttons a:hover {
            background-color: #4c51bf;
        }
        .footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }
        .social-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 5px;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <header class="header">
        <h1 class="logo">Footwear Paradise</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="products.php">products</a></li>
                <li><a href="my_orders.php">Orders</a></li>
                <li><a href="cart.html">Cart</a></li>
                <li>search</li>
                <li><a href="login.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Welcome Section -->
    <section class="welcome">
        <div class="welcome-card">
            <h2>Welcome to Footwear Paradise</h2>
            <p>Discover the latest collection of stylish footwear for Women, Men, and Kids.</p>
            <a href="products.php" class="btn">Shop the Collection</a>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="products-section">
    <h2>Featured Products</h2>
    <div class="products-grid">
        <?php
        // Database connection
        $conn = mysqli_connect("localhost", "root", "", "e-commerece");

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Fetch products from the database
        $query = "SELECT * FROM products";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='product-card'>
                        <img src='uploads/{$row['image']}' alt='{$row['name']}'>
                        <h3>{$row['name']}</h3>
                        <p>Supplier: {$row['supplier']}</p>
                        <p>Category: {$row['category']}</p>
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


    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2024 Footwear Paradise. All rights reserved.</p>
        <div class="social-links">
            <a href="#">Facebook</a> | 
            <a href="#">Instagram</a> |
            <a href="#">Twitter</a>
        </div>
    </footer>

</body>
</html>

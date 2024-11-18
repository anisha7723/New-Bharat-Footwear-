<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .admin-header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
        }

        .admin-nav {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }

        .admin-nav li {
            margin: 0 15px;
        }

        .admin-nav a {
            color: #fff;
            text-decoration: none;
        }

        .admin-nav a:hover {
            text-decoration: underline;
        }

        .products-section {
            margin-top: 30px;
        }

        .products-section h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .products-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .products-table th, .products-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .products-table th {
            background-color: #007bff;
            color: #fff;
        }

        .products-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .products-table img {
            width: 70px;
            height: auto;
            border-radius: 5px;
        }

        .products-table a {
            color: #ff4c4c;
            text-decoration: none;
        }

        .products-table a:hover {
            text-decoration: underline;
        }

        .no-products {
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <!-- Admin Header Section -->
    <header class="admin-header">
        <h1>Admin Panel</h1>
       
    </header>

    <!-- Products Section -->
    <section class="products-section">
        <h2>All Products</h2>
        <div class="products-container">
            <?php
            include('db.php'); // Database connection file
            
            // Fetch products from the database
            $query = "SELECT * FROM products";
            $result = mysqli_query($conn, $query);
            
            if (mysqli_num_rows($result) > 0) {
                echo "<table class='products-table'>";
                echo "<tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Supplier</th>
                        <th>Category</th>
                        <th>Amount (₹)</th>
                        <th>Stock</th>
                        <th>Image</th>
                        <th>Action</th>
                      </tr>";
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['product_id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['supplier']}</td>
                            <td>{$row['category']}</td>
                            <td>₹{$row['amount']}</td>
                            <td>{$row['stock']}</td>
                            <td><img src='../uploads/{$row['image']}' alt='{$row['name']}'></td>
                            <td>
                                <a href='php/delete_product.php?id={$row['product_id']}' onclick='return confirm(\"Are you sure you want to delete this product?\")'>Delete</a>
                            </td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='no-products'>No products found.</p>";
            }
            ?>
        </div>
    </section>
</body>
</html>

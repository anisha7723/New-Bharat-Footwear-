<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "e-commerece");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];

// Fetch orders for the logged-in user
$query = "
    SELECT o.order_id, o.product_id, o.quantity, o.total_amount, o.order_date, 
           p.name AS product_name, p.amount AS product_price
    FROM orders o
    JOIN products p ON o.product_id = p.product_id
    WHERE o.user_id = '$user_id'
    ORDER BY o.order_date DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h2 {
            color: #5a67d8;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #5a67d8;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        a {
            color: #5a67d8;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .back-link {
            margin-top: 20px;
            display: inline-block;
            background-color: #5a67d8;
            color: #fff;
            padding: 8px 12px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
        }
        .back-link:hover {
            background-color: #4c51bf;
        }
    </style>
</head>
<body>
    <h2>My Orders</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Price per Unit</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td>₹<?php echo number_format($row['product_price'], 2); ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>₹<?php echo number_format($row['total_amount'], 2); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($row['order_date'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>

    <a href="products.php" class="back-link">Back to Products</a>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>

<?php
// Start session and include database connection
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "freelancedb";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$application_id = $_POST['application_id'] ?? null;
$action = $_POST['action'] ?? null;
$statusMessage = "No action performed.";
$job_id = $_POST['job_id'] ?? null;

if ($application_id && $action) {
    if ($action === 'accept') {
        $stmt = $conn->prepare("UPDATE applied_jobs SET status = 'accepted' WHERE id = ?");
        $statusMessage = "The application has been accepted.";
    } elseif ($action === 'reject') {
        $stmt = $conn->prepare("UPDATE applied_jobs SET status = 'rejected' WHERE id = ?");
        $statusMessage = "The application has been rejected.";
    }
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $stmt->close();
}

$additionalInfo = "Return to the applications list to view more.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Processing</title>
    <style>
        /* General reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Confirmation message style */
        .confirmation-message {
            max-width: 600px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .confirmation-message h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .confirmation-message p {
            color: #555;
            margin-bottom: 20px;
        }

        .confirmation-message .back-button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .confirmation-message .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="confirmation-message">
    <h3><?php echo $statusMessage; ?></h3>
    <p><?php echo $additionalInfo; ?></p>
    <a href="view_applications.php?job_id=<?php echo $job_id; ?>" class="back-button">Back to Applications</a>
</div>

</body>
</html>

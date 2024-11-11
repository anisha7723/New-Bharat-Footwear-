<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Applications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        h3 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #0066cc;
            color: #fff;
            text-transform: uppercase;
            font-size: 14px;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        td a {
            color: #0066cc;
            text-decoration: none;
            font-weight: bold;
        }
        td a:hover {
            text-decoration: underline;
        }
        p {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #0066cc;
            color: #fff;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #005bb5;
        }
    </style>
</head>
<body>
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "freelancedb"; // Change this to your actual database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Accept/Reject actions
if (isset($_GET['action']) && isset($_GET['app_id'])) {
    $app_id = intval($_GET['app_id']);
    $action = $_GET['action'];

    if ($action == 'accept') {
        $update_query = "UPDATE applied_jobs SET status = 'accepted' WHERE id = ?";
    } elseif ($action == 'reject') {
        $update_query = "UPDATE applied_jobs SET status = 'rejected' WHERE id = ?";
    }

    if (isset($update_query)) {
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param('i', $app_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Get the job ID from the URL (e.g., job.php?job_id=11)
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

// Fetch job details
$job_query = "SELECT * FROM job_postings WHERE id = ?";
$job_stmt = $conn->prepare($job_query);
$job_stmt->bind_param('i', $job_id);
$job_stmt->execute();
$job_result = $job_stmt->get_result();
$job = $job_result->fetch_assoc();

// Check if job exists
if (!$job) {
    echo "<p style='color:red;'>Invalid Job ID: $job_id</p>";
    exit;
}

// Display job details in a card format
echo "
<style>
    .job-card {
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 20px auto;
        max-width: 600px;
    }
    .job-card h2 {
        color: #333;
        margin-bottom: 10px;
    }
    .job-card p {
        margin: 5px 0;
        color: #666;
    }
    .job-card strong {
        color: #444;
    }
    .accepted-table, .pending-table {
        margin: 20px auto;
        max-width: 90%;
        border-collapse: collapse;
        width: 100%;
    }
    .accepted-table th, .pending-table th, 
    .accepted-table td, .pending-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    .accepted-table th, .pending-table th {
        background-color: #4CAF50;
        color: white;
    }
    .pending-table th {
        background-color: #ff9800;
    }
    .action-links a {
        margin-right: 10px;
        text-decoration: none;
        color: #1e88e5;
    }
    .action-links a:hover {
        text-decoration: underline;
    }
</style>

<div class='job-card'>
    <h2>Job Details</h2>
    <p><strong>Job Title:</strong> {$job['job_title']}</p>
    <p><strong>Company:</strong> {$job['employer_name']}</p>
    <p><strong>Description:</strong> {$job['job_description']}</p>
    <p><strong>Category:</strong> {$job['job_category']}</p>
    <p><strong>Skills Required:</strong> {$job['job_skills']}</p>
    <p><strong>Job Type:</strong> {$job['job_type']}</p>
    <p><strong>Pay:</strong> \${$job['job_pay']} per hour</p>
    <p><strong>Deadline:</strong> {$job['job_deadline']}</p>
    <p><strong>Posted At:</strong> {$job['posted_at']}</p>
</div>
";

// Fetch accepted applications
$accepted_query = "SELECT * FROM applied_jobs WHERE job_id = ? AND status = 'accepted'";
$accepted_stmt = $conn->prepare($accepted_query);
$accepted_stmt->bind_param('i', $job_id);
$accepted_stmt->execute();
$accepted_result = $accepted_stmt->get_result();

// Display accepted applications
echo "<h2>Accepted Applications</h2>";

if ($accepted_result->num_rows > 0) {
    echo "<table class='accepted-table'>
            <tr>
                <th>ID</th>
                <th>Applicant Name</th>
                <th>Email</th>
                <th>Resume</th>
                <th>Applied At</th>
                <th>Status</th>
            </tr>";
    
    while ($row = $accepted_result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['applicant_name']}</td>
                <td>{$row['applicant_email']}</td>
                <td><a href='{$row['resume_path']}' target='_blank'>Download Resume</a></td>
                <td>{$row['applied_at']}</td>
                <td>{$row['status']}</td>
              </tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>No accepted applications found for this job.</p>";
}

// Fetch pending/rejected applications
$app_query = "SELECT * FROM applied_jobs WHERE job_id = ? AND status = 'pending'";
$app_stmt = $conn->prepare($app_query);
$app_stmt->bind_param('i', $job_id);
$app_stmt->execute();
$app_result = $app_stmt->get_result();

// Display pending/rejected applications
echo "<h2>Pending Applications</h2>";

if ($app_result->num_rows > 0) {
    echo "<table class='pending-table'>
            <tr>
                <th>ID</th>
                <th>Applicant Name</th>
                <th>Email</th>
                <th>Resume</th>
                <th>Applied At</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>";
    
    while ($row = $app_result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['applicant_name']}</td>
                <td>{$row['applicant_email']}</td>
                <td><a href='{$row['resume_path']}' target='_blank'>Download Resume</a></td>
                <td>{$row['applied_at']}</td>
                <td>{$row['status']}</td>
                <td class='action-links'>
                    <a href='?job_id=$job_id&app_id={$row['id']}&action=accept' onclick='return confirm(\"Are you sure you want to accept this application?\");'>Accept</a> 
                    <a href='?job_id=$job_id&app_id={$row['id']}&action=reject' onclick='return confirm(\"Are you sure you want to reject this application?\");'>Reject</a>
                </td>
              </tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>No pending or rejected applications found for this job.</p>";
}


// Fetch pending/rejected applications
$app_query = "SELECT * FROM applied_jobs WHERE job_id = ? AND status = 'rejected'";
$app_stmt = $conn->prepare($app_query);
$app_stmt->bind_param('i', $job_id);
$app_stmt->execute();
$app_result = $app_stmt->get_result();

// Display pending/rejected applications
echo "<h2>Rejected Applications</h2>";

if ($app_result->num_rows > 0) {
    echo "<table class='pending-table'>
            <tr>
                <th>ID</th>
                <th>Applicant Name</th>
                <th>Email</th>
                <th>Resume</th>
                <th>Applied At</th>
                <th>Status</th>
            </tr>";
    
    while ($row = $app_result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['applicant_name']}</td>
                <td>{$row['applicant_email']}</td>
                <td><a href='{$row['resume_path']}' target='_blank'>Download Resume</a></td>
                <td>{$row['applied_at']}</td>
                <td>{$row['status']}</td>
              </tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>No rejected applications found for this job.</p>";
}
// Close database connections
$app_stmt->close();
$accepted_stmt->close();
$job_stmt->close();
$conn->close();
?>

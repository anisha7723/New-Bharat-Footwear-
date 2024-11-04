<?php
session_start();

// Assuming user type is already checked
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_type'] !== 'employer') {
    header("Location: login.html"); // Redirect to login if not an employer
    exit();
}

// Sample data for job postings and applications
$jobPostings = [
    ['title' => 'Web Developer', 'status' => 'Active', 'applicants' => 5],
    ['title' => 'Graphic Designer', 'status' => 'Closed', 'applicants' => 0],
    ['title' => 'Content Writer', 'status' => 'Active', 'applicants' => 3],
];

// Additional functionality for managing job postings can be added here.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard</title>
    <link rel="stylesheet" href="dashboard_style.css"> <!-- Link to your CSS file -->
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #7d0552;
            color: white;
        }

        nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            margin-right: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
        }

        .container {
            padding: 20px;
        }

        h1 {
            color: #7d0552;
        }

        .job-list {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }

        .job-list th, .job-list td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .job-list th {
            background-color: #f2f2f2;
        }

        .status-active {
            color: green;
        }

        .status-closed {
            color: red;
        }

        .button {
            background-color: #7d0552;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .button:hover {
            background-color: #5c043e;
        }
    </style>
</head>
<body>
    <header>
        <h1>Employer Dashboard</h1>
        <nav>
            <ul>
                <li><a href="employer_home.php">Home</a></li>
                <li><a href="category.php">Categories</a></li>
                <li><a href="search.php">Jobs</a></li>
                <li><a href="freedash.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Your Job Postings</h2>
        <table class="job-list">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Status</th>
                    <th>Applicants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobPostings as $job): ?>
                <tr>
                    <td><?php echo htmlspecialchars($job['title']); ?></td>
                    <td class="<?php echo $job['status'] === 'Active' ? 'status-active' : 'status-closed'; ?>">
                        <?php echo htmlspecialchars($job['status']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($job['applicants']); ?></td>
                    <td>
                        <a href="view_applicants.php?job=<?php echo urlencode($job['title']); ?>" class="button">View Applicants</a>
                        <a href="edit_job.php?job=<?php echo urlencode($job['title']); ?>" class="button">Edit</a>
                        <a href="delete_job.php?job=<?php echo urlencode($job['title']); ?>" class="button">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Add New Job Posting</h2>
        <form action="add_job.php" method="POST">
            <input type="text" name="job_title" placeholder="Job Title" required>
            <input type="text" name="job_description" placeholder="Job Description" required>
            <input type="text" name="job_status" placeholder="Status (Active/Closed)" required>
            <button type="submit" class="button">Add Job</button>
        </form>
    </div>
</body>
</html>

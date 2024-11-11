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

// Fetch job postings for the employer (assuming `user_id` is stored in the session)
$user_id = $_SESSION['user_id'] ?? 1; // Replace with the actual session variable

// Fetch job postings for the employer
$stmt = $conn->prepare("SELECT * FROM job_postings WHERE employer_name = (SELECT name FROM users WHERE user_id = ?)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$job_result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Postings and Applications</title>
    <style>
        /* General reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: plum;
            color: #333;
            padding: 20px;
        }

        /* Container for job postings */
        .job-container {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .job-header h3 {
            color:purple;
            margin-bottom: 15px;
        }

        .applications-container {
            margin-top: 15px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        .applicant-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fafafa;
        }

        .applicant-info p {
            margin: 5px 0;
        }

        .applicant-actions {
            display: flex;
            gap: 10px;
        }

        button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .accept-button {
            background-color: #28a745;
            color: #fff;
        }

        .reject-button {
            background-color: #dc3545;
            color: #fff;
        }

        .view-resume-button,
        .contact-button {
            background-color: #007bff;
            color: #fff;
        }

        .contact-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>Your Job Postings and Applications</h2>

<?php if ($job_result->num_rows > 0): ?>
    <?php while ($job = $job_result->fetch_assoc()): ?>
        <div class="job-container">
            <div class="job-header">
                <h3>Job: <?= htmlspecialchars($job['job_title']); ?></h3>
                <p><strong>Pay:Rs</strong> <?= htmlspecialchars($job['job_pay']); ?>/hr</p>
                <p><strong>Category:</strong> <?= htmlspecialchars($job['job_category']); ?></p>
            </div>

            <?php
            // Fetch applications for the current job
            $stmt = $conn->prepare("SELECT * FROM applied_jobs WHERE job_id = ?");
            $stmt->bind_param("i", $job['id']);
            $stmt->execute();
            $applications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            ?>

            <div class="applications-container">
                <h4>Applications for this Job</h4>
                <?php if (!empty($applications)): ?>
                    <?php foreach ($applications as $application): ?>
                        <div class="applicant-card">
                            <div class="applicant-info">
                                <p><strong>Name:</strong> <?= htmlspecialchars($application['applicant_name']); ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($application['applicant_email']); ?></p>
                                <p><strong>Applied At:</strong> <?= htmlspecialchars($application['applied_at']); ?></p>
                            </div>
                            <div class="applicant-actions">
    <?php if ($application['status'] === 'accepted'): ?>
        <a href="view_resume.php?file=<?= urlencode($application['resume_path']); ?>" class="contact-button" target="_blank">View Resume</a>
        <button class="contact-button" onclick="alert('Contact Email: <?= htmlspecialchars($application['applicant_email']); ?>')">Contact Info</button>
    <?php elseif($application['status'] === 'pending'): ?>
        <a href="view_resume.php?file=<?= urlencode($application['resume_path']); ?>" class="contact-button" target="_blank">View Resume</a>
        <form action="process_application.php" method="POST" style="display:inline;">
            <input type="hidden" name="application_id" value="<?= $application['id']; ?>">
            <input type="hidden" name="job_id" value="<?= $job['id']; ?>">
            <button type="submit" name="action" value="accept" class="accept-button">Accept</button>
            <button type="submit" name="action" value="reject" class="reject-button">Reject</button>
        </form>
    <?php else: ?>
        <input type="hidden" name="application_id" value="<?= $application['id']; ?>">
            <input type="hidden" name="job_id" value="<?= $job['id']; ?>">
            <button type="button" class="reject-button">REJECTED APPLICATION</button>
    <?php endif; ?>
</div>

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No applications found for this job.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No job postings found.</p>
<?php endif; ?>

</body>
</html>

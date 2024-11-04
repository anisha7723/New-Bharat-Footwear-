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


// Assuming the user ID is stored in the session for demo purposes
$user_id = $_SESSION['user_id'] ?? 1; // Default to user ID 1 for testing

// Count the number of applied jobs for the user
$stmt = $conn->prepare("SELECT COUNT(*) as total_applied FROM applied_jobs WHERE applicant_email = (SELECT email FROM users WHERE user_id = ?)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$count_result = $stmt->get_result();
$total_applied = $count_result->fetch_assoc()['total_applied'];
$stmt->close();
?>
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #2c2c2c; /* Dark background */
        color: #ffffff; /* Light text color */
        margin: 0;
        padding: 20px;
    }

    h2, h3, h4 {
        color: #f0c674; /* Light yellow for headings */
    }

    .dashboard-overview {
        background-color: #3a3a3a; /* Slightly lighter background for the dashboard */
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .content-section {
        background-color: #444444; /* Background for content sections */
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .portfolio-form {
        background-color: #555555; /* Form background */
        padding: 15px;
        border-radius: 5px;
        margin-top: 15px;
    }

    input[type="text"],
    input[type="url"],
    textarea {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #666666;
        border-radius: 5px;
        background-color: #333333; /* Darker input background */
        color: #ffffff; /* Light text for inputs */
    }

    input[type="text"]:focus,
    input[type="url"]:focus,
    textarea:focus {
        border-color: #f0c674; /* Highlight border on focus */
        outline: none; /* Remove default outline */
    }

    button {
        background-color: #f0c674; /* Button color */
        color: #2c2c2c; /* Button text color */
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #e5b651; /* Darker button on hover */
    }

    .applied-jobs-list {
        background-color: #444444; /* Background for applied jobs list */
        padding: 15px;
        border-radius: 5px;
    }

    .job-listing {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #666666;
        border-radius: 5px;
    }

    .job-listing h4 {
        margin: 0 0 5px 0;
    }
</style>


<body>
<section id="dash" class="dashboard-overview">
    <h2>Your Dashboard</h2>
    <p>Number of jobs applied: <?php echo $total_applied; ?></p>
    <a href="javascript:void(0);" onclick="showPortfolioForm();">Portfolio</a>
</section><!-- Portfolio Section --> 

<section id="portfolio" class="content-section" style="display: none;">
    <h3>Your Portfolio</h3>
    <button id="add-portfolio-btn">Add to Portfolio</button>
    
    <div class="portfolio-form" id="portfolio-form" style="display:none;">
        <form action="portfolio_submit.php" method="POST">
            <label for="project-title">Project Title:</label>
            <input type="text" id="project-title" name="title" required>
            <label for="project-description">Project Description:</label>
            <textarea id="project-description" name="description" rows="5" required></textarea>
            <label for="project-link">Project Link:</label>
            <input type="url" id="project-link" name="project_link" required>
            <button type="submit">Add Project</button>
        </form>
    </div>

    <div id="portfolio-list">
        <?php
        // Assuming $conn is your database connection
        $stmt = $conn->prepare("SELECT * FROM portfolios WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<div class='project'>
                    <h4>{$row['title']}</h4>
                    <p>{$row['description']}</p>
                    <a href='{$row['project_link']}'>View Project</a>
                  </div>";
        }
        $stmt->close();
        ?>
    </div>
</section>

        <section id="applied-jobs" class="content-section">
            <h2>Your Applied Jobs</h2>
            <?php
            // Fetch all applied jobs regardless of status, including application status
            $stmt = $conn->prepare("
                SELECT ja.id, jp.job_title, jp.employer_name, jp.job_pay, ja.applied_at, ja.status
                FROM applied_jobs ja
                JOIN job_postings jp ON ja.job_id = jp.id
                WHERE ja.applicant_email = (SELECT email FROM users WHERE user_id = ?)
            ");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<div class='applied-jobs-list'>";
                while ($job = $result->fetch_assoc()) {
                    echo "<div class='job-listing'>
                            <h4>{$job['job_title']}</h4>
                            <p><strong>Company:</strong> {$job['employer_name']}</p>
                            <p><strong>Pay:</strong> \${$job['job_pay']}/hr</p>
                            <p><strong>Applied On:</strong> {$job['applied_at']}</p>
                            <p><strong>Status:</strong> {$job['status']}</p> <!-- Displaying application status -->
                          </div>";
                }
                echo "</div>";
            } else {
                echo "<p>You have not applied for any jobs yet.</p>";
            }

            $stmt->close();
            ?>
        </section>
</body>
</html>
<script>
        function showPortfolioForm() {
            var portfolioSection = document.getElementById('portfolio');
            portfolioSection.style.display = 'block'; // Show the portfolio section
        }

        // JavaScript to show the portfolio form when the "Add to Portfolio" button is clicked
        document.getElementById('add-portfolio-btn').addEventListener('click', function() {
            var form = document.getElementById('portfolio-form');
            form.style.display = 'block'; // Show the form
        });
</script>
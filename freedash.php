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
    <title>freelancer dashboard</title>
    <link rel="stylesheet" href="freelance_home.css">
</head>
<style>
   body {
    font-family: Arial, sans-serif;
    background-color: rgba(245, 245, 220, 0.8); /* Darker background */
    color: #ffffff; /* Light text color */
    margin: 0;
    padding: 20px;
}

h2, h3, h4 {
    color: maroon; /* Yellowish color for headings */
}

.dashboard-overview {
    background-color: rgba(128, 0, 128, 0.8); /* Semi-transparent plum */
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.content-section {
    background-color: rgba(128, 0, 128, 0.3); /* Semi-transparent plum */
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

/* Portfolio Section Styling */
#portfolio {
    background-color: rgba(128, 0, 128, 0.8); /* Semi-transparent plum */
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    color: #ffffff; /* Light text color */
}

#portfolio h3 {
    color: #f0c674; /* Yellowish color for headings */
    margin-bottom: 15px;
}

#add-portfolio-btn {
    background-color: #f0c674; /* Button color */
    color: #2c2c2c; /* Button text color */
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-bottom: 15px;
}

#add-portfolio-btn:hover {
    background-color: #e5b651; /* Darker button on hover */
}

.portfolio-form {
    display: none;
    background-color: #4b0082; /* Darker plum color */
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
}

.portfolio-form label {
    color: #f0c674; /* Yellowish label color */
}

.portfolio-form input[type="text"],
.portfolio-form input[type="url"],
.portfolio-form textarea {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #666666;
    border-radius: 5px;
    background-color: #333333; /* Darker input background */
    color: #ffffff; /* Light text for inputs */
}

.portfolio-form input[type="text"]:focus,
.portfolio-form input[type="url"]:focus,
.portfolio-form textarea:focus {
    border-color: #f0c674; /* Highlight border on focus */
    outline: none; /* Remove default outline */
}

.portfolio-form button {
    background-color: #f0c674; /* Button color */
    color: #2c2c2c; /* Button text color */
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 10px;
}

.portfolio-form button:hover {
    background-color: #e5b651; /* Darker button on hover */
}

#portfolio-list {
    margin-top: 20px;
}

.project {
    background-color: rgba(0, 0, 0, 0.7); /* Darker transparent background */
    border: 1px solid #8b008b;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.project h4 {
    color: #f0c674; /* Yellow for project titles */
    margin-bottom: 10px;
}

.project p {
    color: #ffffff; /* White text for project description */
}

.project a {
    color: #add8e6; /* Light blue for project links */
    text-decoration: none;
}

.project a:hover {
    text-decoration: underline;
}
/* General Styling for the Job Listings Section */
.applied-jobs-list {
    background-color: #222; /* Dark background for the section */
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Individual Job Listing Styling */
.job-listing {
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 15px;
    color: #fff;
    transition: transform 0.2s ease-in-out;
}

/* Hover effect for job listings */
.job-listing:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

/* Accepted Job Listings */
.job-listing.accepted {
    background-color:rgba(128, 255, 128, 0.6); /* Green background for accepted jobs */
    border: 1px solid #1b5e20;
}

/* Pending Job Listings */
.job-listing.pending {
    background-color: #ffeb3b; /* Yellow background for pending jobs */
    border: 1px solid #f9a825;
    color: #333; /* Dark text for better contrast */
}

/* Rejected Job Listings */
.job-listing.rejected {
    background-color: #d32f2f; /* Red background for rejected jobs */
    border: 1px solid #b71c1c;
}

/* Headings inside job listings */
.job-listing h4 {
    margin: 0 0 8px;
    font-size: 1.2em;
    color: black; /* Gold color for job titles */
}

/* Details inside job listings */
.job-listing p {
    margin: 5px 0;
    font-size: 0.9em;
}

/* Button styling for job actions (if any) */
.job-listing button {
    background-color: #f0c674;
    color: #2c2c2c;
    padding: 8px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.job-listing button:hover {
    background-color: #e5b651;
}

/* Responsive Styling */
@media (max-width: 768px) {
    .applied-jobs-list {
        padding: 15px;
    }

    .job-listing {
        padding: 10px;
    }

    .job-listing h4 {
        font-size: 1em;
    }
}

</style>


<body>
<section id="dash" class="dashboard-overview">
    <h2>Your Dashboard</h2>
    <p>Number of jobs applied: <?php echo $total_applied; ?></p>
  
</section><!-- Portfolio Section --> 

<section id="portfolio" class="content-section">
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

    <!-- Accepted Applications Section -->
    <h3>Accepted Applications</h3>
    <?php
    // Fetch accepted applications
    $stmt = $conn->prepare("
        SELECT ja.id, jp.job_title, jp.employer_name, jp.job_pay, ja.applied_at, ja.status
        FROM applied_jobs ja
        JOIN job_postings jp ON ja.job_id = jp.id
        WHERE ja.applicant_email = (SELECT email FROM users WHERE user_id = ?) AND ja.status = 'Accepted'
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='applied-jobs-list'>";
        while ($job = $result->fetch_assoc()) {
            echo "<div class='job-listing accepted'>
                    <h4>{$job['job_title']}</h4>
                    <p><strong>Company:</strong> {$job['employer_name']}</p>
                    <p><strong>Pay: Rs</strong> {$job['job_pay']}/hr</p>
                    <p><strong>Applied On:</strong> {$job['applied_at']}</p>
                    <p><strong>Status:</strong> {$job['status']}</p>
                  </div>";
        }
        echo "</div>";
    } else {
        echo "<p>No accepted applications yet.</p>";
    }
    $stmt->close();
    ?>

    <!-- Pending Applications Section -->
    <h3>Pending Applications</h3>
    <?php
    // Fetch pending applications
    $stmt = $conn->prepare("
        SELECT ja.id, jp.job_title, jp.employer_name, jp.job_pay, ja.applied_at, ja.status
        FROM applied_jobs ja
        JOIN job_postings jp ON ja.job_id = jp.id
        WHERE ja.applicant_email = (SELECT email FROM users WHERE user_id = ?) AND ja.status = 'Pending'
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='applied-jobs-list'>";
        while ($job = $result->fetch_assoc()) {
            echo "<div class='job-listing pending'>
                    <h4>{$job['job_title']}</h4>
                    <p><strong>Company:</strong> {$job['employer_name']}</p>
                    <p><strong>Pay: Rs</strong> {$job['job_pay']}/hr</p>
                    <p><strong>Applied On:</strong> {$job['applied_at']}</p>
                    <p><strong>Status:</strong> {$job['status']}</p>
                  </div>";
        }
        echo "</div>";
    } else {
        echo "<p>No pending applications yet.</p>";
    }
    $stmt->close();
    ?>
    <h3>Rejected Applications</h3>
    <?php
    // Fetch pending applications
    $stmt = $conn->prepare("
        SELECT ja.id, jp.job_title, jp.employer_name, jp.job_pay, ja.applied_at, ja.status
        FROM applied_jobs ja
        JOIN job_postings jp ON ja.job_id = jp.id
        WHERE ja.applicant_email = (SELECT email FROM users WHERE user_id = ?) AND ja.status = 'rejected'
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='applied-jobs-list'>";
        while ($job = $result->fetch_assoc()) {
            echo "<div class='job-listing rejected'>
                    <h4>{$job['job_title']}</h4>
                    <p><strong>Company:</strong> {$job['employer_name']}</p>
                    <p><strong>Pay: Rs</strong> {$job['job_pay']}/hr</p>
                    <p><strong>Applied On:</strong> {$job['applied_at']}</p>
                    <p><strong>Status:</strong> {$job['status']}</p>
                  </div>";
        }
        echo "</div>";
    } else {
        echo "<p>No rejected applications yet.</p>";
    }
    $stmt->close();
    ?>
</section>

</body>
</html>
<script>
       

        // JavaScript to show the portfolio form when the "Add to Portfolio" button is clicked
        document.getElementById('add-portfolio-btn').addEventListener('click', function() {
            var form = document.getElementById('portfolio-form');
            form.style.display = 'block'; // Show the form
        });
</script>
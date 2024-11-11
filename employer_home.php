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
$user_id = $_SESSION['user_id'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard</title>
    <link rel="stylesheet" href="employerhome.css">
</head>


<header>
        <div class="logo">
            <img src="image/logo.png" alt="logo">
        </div>
        <nav>
            <ul>
                <li><a href="employer_homee.php">Home</a></li>
               
                <li><a href="view_applications.php">Dashboard</a></li>
              
                <li><a href="#about">About</a></li>
                <li>
                    <form class="search-form" action="search.php" method="GET">
                        <input type="text" name="search" placeholder="Search jobs...">
                        <button type="submit">Search</button>
                    </form>
                </li>
                    <li><a href="login.html">Logout</a></li>
        
            </ul>
</nav>
</header>

<body>
    <main>
        <section class="hero">
            <h1>Welcome to Your Platform!</h1>
            <p>Connect with top talent and streamline your hiring process.</p>
            <a href="#job-form" class="cta-button" onclick="toggleJobForm();">Post a Job</a>
        </section>

        <section class="post_job" style="display: none;">
            <div class="job-form" id="job-form">
                <h3>Post a Freelance Job</h3>
                <p>Provide details about the project you need help with, and our system will help you find the best fit.</p>

                <form action="job_posting.php" method="POST">
                    <label for="job-title">Job Title:</label>
                    <input type="text" id="job-title" name="job-title" placeholder="e.g., Frontend Developer, Marketing Expert" required>

                    <label for="employer_name">Employer Name:</label>
                    <input type="text" id="employer_name" name="employer_name" placeholder="Your Name or Company Name" required>

                    <label for="job-description">Job Description:</label>
                    <textarea id="job-description" name="job-description" rows="5" placeholder="Describe the project and required skills..." required></textarea>

                    <label for="job-category">Job Category:</label>
                    <select id="job-category" name="job-category" required>
                        <option value="web-dev">Web Development</option>
                        <option value="graphic-design">Graphic Design</option>
                        <option value="content-writing">Content Writing</option>
                        <option value="translation">Translation</option>
                        <option value="video-editing">Video Editing</option>
                        <option value="finance">Finance</option>
                        <option value="education">Education</option>
                        <option value="healthcare">Healthcare</option>
                        <option value="marketing">Marketing</option>
                    </select>

                    <div class="form-row">
                        <div>
                            <label for="job-hours">Number of Hours:</label>
                            <input type="number" id="job-hours" name="job-hours" placeholder="e.g., 40" min="1" required>
                        </div>
                        <div>
                            <label for="job-pay">Pay (per hour):</label>
                            <input type="number" id="job-pay" name="job-pay" placeholder="e.g., 15" min="0" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div>
                            <label for="job-type">Job Type:</label>
                            <select id="job-type" name="job-type" required>
                                <option value="full-time">Full-Time</option>
                                <option value="part-time">Part-Time</option>
                                <option value="contract">Contract</option>
                            </select>
                        </div>
                        <div>
                            <label for="job-deadline">Application Deadline:</label>
                            <input type="date" id="job-deadline" name="job-deadline" required>
                        </div>
                    </div>

                    <label for="job-skills">Required Skills:</label>
                    <textarea id="job-skills" name="job-skills" rows="3" placeholder="List required skills separated by commas (e.g., HTML, CSS, SEO)..." required></textarea>

                    <button type="submit">Post Job</button>
                </form>
            </div>
        </section>

        <section id="job" class="content-section visible">
            <?php
            // Fetching job postings for the logged-in employer
            $stmt = $conn->prepare("SELECT * FROM job_postings WHERE employer_name = (SELECT name FROM users WHERE user_id = ?)");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $job_result = $stmt->get_result();

            if ($job_result->num_rows > 0) {
                echo "<h3>Your Job Postings</h3>";
                echo "<div class='job-cards'>"; // Flex container for the job postings

                while ($job = $job_result->fetch_assoc()) {
                    echo "<div class='job-listing'>
                            <h4>{$job['job_title']}</h4>
                            <p><strong>Pay: Rs</strong> {$job['job_pay']}/hr</p>
                            <p><strong>Category:</strong> {$job['job_category']}</p>
                            <p><strong>Description:</strong> {$job['job_description']}</p>
                            <p><strong>Skills Needed:</strong> {$job['job_skills']}</p>
                            <p><strong>Hours:</strong> {$job['job_hours']}</p>
                            <p><strong>Job Type:</strong> {$job['job_type']}</p>
                            <p><strong>Deadline:</strong> {$job['job_deadline']}</p>
                            <a href=\"updated_view_application.php?job_id=" . $job['id'] . "\">View Applications</a>
                          </div>";
                }
                

                echo "</div>"; // End of job-cards flex container
            } else {
                echo "<p>You have not posted any jobs yet.</p>";
            }
            $stmt->close();
            ?>
        </section>

        <section class="about" id="about">
            <h2>About Us</h2>
            <p>Learn more about our mission and values.</p>
        </section>
    </main>

    <footer>
        <ul>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">FAQs</a></li>
        </ul>
        <div class="social-media">
            <a href="#">Facebook</a>
            <a href="#">Twitter</a>
            <a href="#">LinkedIn</a>
        </div>
    </footer>

    <script>
        function toggleJobForm() {
            const jobForm = document.querySelector('.post_job');
            jobForm.style.display = jobForm.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>

</html>

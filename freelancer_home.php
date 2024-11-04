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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard</title>
    <link rel="stylesheet" href="freelancer_home.css">
</head>

<body>
    <!-- Include the header file -->
    <header>
        <div class="logo">
            <img src="image/logo.png" alt="logo">
        </div>
        <nav>
            <ul>
                <li><a href="freelance_home.php">Home</a></li>
                <li><a href="category.php">Categories</a></li>
                <li><a href="search.php">Jobs</a></li>
                <li><a href="freedash.php">Dashboard</a></li>
                <li><a href="#footer">Contact</a></li>
                <li><a href="#footer">About</a></li>
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

    <main>
        <section class="categories">
            <h2>Explore Job Categories</h2>
            <div class="category-cards">
                <a href="jobs.php?category=Web-dev">
                    <div class="card">
                        <img src="image/web.jpeg" alt="Web Development" class="category-image">
                        <span>Technology</span>
                    </div>
                </a>
                <a href="jobs.php?category=Marketing">
                    <div class="card">
                        <img src="image/marketing.jpeg" alt="Marketing" class="category-image">
                        <span>Marketing</span>
                    </div>
                </a>
                <a href="jobs.php?category=Design">
                    <div class="card">
                        <img src="image/design.jpeg" alt="Design" class="category-image">
                        <span>Design</span>
                    </div>
                </a>
                <a href="jobs.php?category=Finance">
                    <div class="card">
                        <img src="image/finance.jpeg" alt="Finance" class="category-image">
                        <span>Finance</span>
                    </div>
                </a>
                <a href="jobs.php?category=Education">
                    <div class="card">
                        <img src="image/education.jpeg" alt="Education" class="category-image">
                        <span>Education</span>
                    </div>
                </a>
                <a href="jobs.php?category=Healthcare">
                    <div class="card">
                        <img src="image/health.jpeg" alt="Healthcare" class="category-image">
                        <span>Healthcare</span>
                    </div>
                </a>
            </div>
        </section>

        <section id="job" class="content-section visible">
            <h3>Available Jobs</h3>
            <?php
            $stmt = $conn->prepare("SELECT * FROM job_postings");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<div class='job-container'>"; 
                while ($job = $result->fetch_assoc()) {
                    echo "<div class='job-listing'>
                            <h4>{$job['job_title']}</h4>
                            <p><strong>Company:</strong> {$job['employer_name']}</p>
                            <p><strong>Pay:</strong> \${$job['job_pay']}/hr</p>
                            <p><strong>Category:</strong> {$job['job_category']}</p>
                            <p><strong>Description:</strong> {$job['job_description']}</p>
                            <p><strong>Skills Needed:</strong> {$job['job_skills']}</p>
                            <p><strong>Hours:</strong> {$job['job_hours']}</p>
                            <p><strong>Job Type:</strong> {$job['job_type']}</p>
                            <p><strong>Deadline:</strong> {$job['job_deadline']}</p>
                            <button onclick=\"applyJob({$job['id']}, '{$user_id}')\">Apply</button>
                          </div>";
                }
                echo "</div>";
            } else {
                echo "<p>No jobs available at the moment.</p>";
            }

            $stmt->close();
            ?>
        </section>



       

        <section class="contact">
            <h2>Need Assistance?</h2>
            <form>
                <input type="text" placeholder="Your Name" required>
                <input type="email" placeholder="Your Email" required>
                <textarea placeholder="Your Message" required></textarea>
                <button type="submit">Submit</button>
            </form>
        </section>

        <section class="about">
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
        function showPortfolioForm() {
            var portfolioSection = document.getElementById('portfolio');
            portfolioSection.style.display = 'block'; // Show the portfolio section
        }

        // JavaScript to show the portfolio form when the "Add to Portfolio" button is clicked
        document.getElementById('add-portfolio-btn').addEventListener('click', function() {
            var form = document.getElementById('portfolio-form');
            form.style.display = 'block'; // Show the form
        });

        function applyJob(jobId, userId) {
            // Logic to apply for a job using jobId and userId
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "apply_job.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert('Job applied successfully!');
                        location.reload(); // Refresh the page to update the list of applied jobs
                    } else {
                        alert('Error applying for job.');
                    }
                }
            };
            xhr.send("jobId=" + jobId + "&userId=" + userId);
        }
    </script>
</body>

</html>

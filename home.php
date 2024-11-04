<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelance Platform</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    /* Hero Section Styling */
.hero {
    position: relative;
    width: 100%;
    height: 60vh;
    overflow: hidden;
}

.hero-slideshow {
    position: absolute;
    width: 100%;
    height: 100%;
    display: flex;
    transition: opacity 1s ease-in-out;
}

.slide {
    min-width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 2em;
    font-weight: bold;
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
    opacity: 0;
    transition: opacity 1s ease-in-out;
}

.slide.active {
    opacity: 1;
}
.hero-slideshow {
    position: relative;
    width: 100%;
    height: 400px;
    overflow: hidden;
}

.slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}

.slide:nth-child(1) {
    animation: fade 16s infinite;
}
.slide:nth-child(2) {
    animation: fade 16s infinite 4s;
}
.slide:nth-child(3) {
    animation: fade 16s infinite 8s;
}
.slide:nth-child(4) {
    animation: fade 16s infinite 12s;
}

@keyframes fade {
    0%, 25% {
        opacity: 1;
    }
    30%, 100% {
        opacity: 0;
    }
}

</style>
<body>
<!-- Include the header file -->
<header>
<header>
        <div class="logo">
            <img src="image/logo.png" alt="logo">
        </div>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="category.php">Categories</a></li>
                <li><a href="search.php">Jobs</a></li>
              
                <li><a href="#footer">Contact</a></li>
                <li><a href="#footer">About</a></li>
                <li>
                    <form class="search-form" action="search.php" method="GET">
                        <input type="text" name="search" placeholder="Search jobs...">
                        <button type="submit">Search</button>
                    </form>
                </li>
              
                    <li><a href="login.html">Login</a></li>
               
            </ul>
        </nav>
    </header>
</header>
<main>
    <section class="welcome">
        <h2>Find the best freelance jobs here</h2>
        <a href="login.html"><button>Join as a Freelancer</button></a>
        <a href="login.html"><button>Post a Job</button></a>
    </section>
    <section class="hero">
    <div class="hero-slideshow">
        <div class="slide" style="background-image: url('image/design.jpeg');">
                        <h2>Web Development</h2>
        </div>
        <div class="slide" style="background-image: url('image/graph.jpeg');">
            <h2>Graphic Design</h2>
        </div>
        <div class="slide" style="background-image: url('image/content.jpeg');">
            <h2>Content Writing</h2>
        </div>
        <div class="slide" style="background-image: url('image/marketing.jpeg');">
            <h2>Marketing</h2>
        </div>
    </div>
</section>

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


    <!-- Latest Jobs Section -->
    <section class="latest-jobs">
        <h3>Latest Jobs</h3>
        <div id="job-list">
            <!-- Example PHP Loop to Fetch and Display Jobs -->
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "freelancedb";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("SELECT * FROM job_postings");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($job = $result->fetch_assoc()) {
                    echo "<div class='job-card'>
                            <h4>{$job['job_title']}</h4>
                            <p><strong>Company:</strong> {$job['employer_name']}</p>
                            <p><strong>Pay: Rs </strong>{$job['job_pay']}/hr</p>
                            <p><strong>Category:</strong> {$job['job_category']}</p>
                            <p><strong>Description:</strong> {$job['job_description']}</p>
                            <p><strong>Skills Needed:</strong> {$job['job_skills']}</p>
                            <p><strong>Hours:</strong> {$job['job_hours']}</p>
                            <p><strong>Job Type:</strong> {$job['job_type']}</p>
                            <p><strong>Deadline:</strong> {$job['job_deadline']}</p>
                            <button onclick=\"applyJob({$job['id']}, '" . addslashes($job['employer_name']) . "', '" . addslashes($job['job_title']) . "')\">Apply</button>
                          </div>";
                }
            } else {
                echo "<p>No jobs available at the moment.</p>";
            }

            $stmt->close();
            ?>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2024 Freelance Platform. All rights reserved.</p>
</footer>

<!-- JavaScript block -->
<script>
    // Assuming the user ID is passed from the server into JavaScript
    const userId = "<?php echo $_SESSION['user_id']; ?>"; // This assumes that the session user_id is set

    function applyJob(jobId, employerName, jobTitle) {
        // Send data to a PHP script via AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "apply_job.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText); // Display response message
            }
        };

        // Send job application details to the PHP handler
        xhr.send("job_id=" + jobId + "&employer_name=" + encodeURIComponent(employerName) + "&user_id=" + userId + "&job_title=" + encodeURIComponent(jobTitle));
    }
    
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero .slide');

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (i === index) {
                slide.classList.add('active');
            }
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    // Initialize the first slide
    showSlide(currentSlide);

    // Set interval to change slides every 5 seconds
    setInterval(nextSlide, 5000);
    // Test if images are loading by showing the first slide
document.addEventListener('DOMContentLoaded', () => {
    slides[0].classList.add('active');
});
document.addEventListener('DOMContentLoaded', function() {
    let slides = document.querySelectorAll('.slide');
    let currentSlide = 0;

    function showNextSlide() {
        slides[currentSlide].style.opacity = '0';
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].style.opacity = '1';
    }

    setInterval(showNextSlide, 4000); // Change slide every 4 seconds
});




</script>
</body>
</html>

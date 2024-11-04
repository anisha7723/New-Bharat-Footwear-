<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Job Search Results</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            margin-top: 80px;
            background: linear-gradient(to right, pink, plum);
            color: #333;
        }

        header {
            background-color: #007bff;
            padding: 10px 20px;
            color: white;
            text-align: center;
        }

        .job-cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
            padding: 0 20px;
        }

        .job-card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            width: 280px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: left;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .job-card h3 {
            margin-top: 0;
            color: maroon;
        }

        .apply-button {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: maroon;
            color: goldenrod;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
        }

        .apply-button:hover {
            background-color: goldenrod;
            color: maroon;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
        }

        footer {
            padding: 20px;
            background-color: purple;
            color: white;
            text-align: center;
            margin-top: 20px;
        }

        footer a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <header>
        <?php include 'header.php'; ?>
    </header>

    <?php
        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "freelancedb";

        // Create a connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch the search term from the URL
        $searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

        // Prepare SQL query to fetch jobs based on the search term
        if ($searchTerm) {
            $stmt = $conn->prepare("SELECT * FROM job_postings WHERE job_category LIKE ?");
            $likeSearchTerm = "%" . $searchTerm . "%";
            $stmt->bind_param("s", $likeSearchTerm);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "SELECT * FROM job_postings";
            $result = $conn->query($sql);
        }

        if ($result->num_rows > 0) {
            echo "<div class='job-cards-container'>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='job-card'>";
                echo "<h3>" . htmlspecialchars($row['job_title']) . "</h3>";
                echo "<p><strong>Company:</strong> " . htmlspecialchars($row['employer_name']) . "</p>";
                echo "<p><strong>Description:</strong> " . htmlspecialchars($row['job_description']) . "</p>";
                echo "<p><strong>Pay:</strong> $" . htmlspecialchars($row['job_pay']) . "/hour</p>";
                echo "<p><strong>Type:</strong> " . htmlspecialchars($row['job_type']) . "</p>";
                echo "<p><strong>Hours:</strong> " . htmlspecialchars($row['job_hours']) . " hours/week</p>";
                echo "<button class='apply-button' onclick='showApplyForm(" . intval($row['id']) . ")'>Apply</button>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p style='text-align:center;'>No jobs found matching the search criteria.</p>";
        }

        $conn->close();
    ?>

    <div id="applyModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeApplyForm()">&times;</span>
            <h2>Apply for Job</h2>
            <form id="applyForm" method="POST" action="apply.php" enctype="multipart/form-data">
                <input type="hidden" id="job_id" name="job_id">
                <div>
                    <label for="applicant_name">Name:</label>
                    <input type="text" id="applicant_name" name="applicant_name" required>
                </div>
                <div>
                    <label for="applicant_email">Email:</label>
                    <input type="email" id="applicant_email" name="applicant_email" required>
                </div>
                <div>
                    <label for="resume">Resume (PDF, DOC, DOCX):</label>
                    <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
                </div>
                <button type="submit">Submit Application</button>
            </form>
        </div>
    </div>

    <script>
        function showApplyForm(jobId) {
            console.log("Apply button clicked for job ID:", jobId);
            document.getElementById('job_id').value = jobId;
            document.getElementById('applyModal').style.display = 'flex';
        }

        function closeApplyForm() {
            document.getElementById('applyModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target === document.getElementById('applyModal')) {
                closeApplyForm();
            }
        }
    </script>

    <footer>
        <p>&copy; 2024 Your Company. All rights reserved.</p>
        <p>
            <a href="#">Privacy Policy</a> |
            <a href="#">Terms of Service</a>
        </p>
    </footer>

</body>
</html>

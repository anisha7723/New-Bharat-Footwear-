<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "freelancedb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$fetched_users = [];
$fetched_jobs = [];

// Add User when "Add User" button is clicked
if (isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Not hashed
    $phone = $_POST['phone'];
    $user_type = $_POST['user_type'];

    $sql = "INSERT INTO users (name, email, password, phone, user_type)
            VALUES ('$name', '$email', '$password', '$phone', '$user_type')";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>New user added successfully!</p>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

// Fetch Users (Employers or Freelancers) when view buttons are clicked
if (isset($_POST['view_employers'])) {
    $sql = "SELECT * FROM users WHERE user_type = 'employer'";
    $result = $conn->query($sql);
    $fetched_users = $result->fetch_all(MYSQLI_ASSOC);
}

if (isset($_POST['view_freelancers'])) {
    $sql = "SELECT * FROM users WHERE user_type = 'freelancer'";
    $result = $conn->query($sql);
    $fetched_users = $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch Jobs when the page loads
$sql = "SELECT id, job_title, job_description, job_category, job_hours, job_pay,
        job_type, job_deadline, job_skills, posted_at, employer_name
        FROM job_postings";
$result = $conn->query($sql);
$fetched_jobs = $result->fetch_all(MYSQLI_ASSOC);

// Delete Job when the "Delete" button is clicked
if (isset($_POST['delete_job'])) {
    $job_id = $_POST['job_id'];

    $sql = "DELETE FROM job_postings WHERE id = '$job_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>Job deleted successfully!</p>";
        header("Refresh:0");
        exit();
    } else {
        echo "<p class='error'>Error deleting job: " . $conn->error . "</p>";
    }
}

// Delete User when the "Delete" button is clicked
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    $sql = "DELETE FROM users WHERE user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>User deleted successfully!</p>";
        header("Refresh:0");
        exit();
    } else {
        echo "<p class='error'>Error deleting user: " . $conn->error . "</p>";
    }
}

// Edit User when the "Edit" button is clicked
if (isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
}

// Update User when the "Update User" button is clicked
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Not hashed
    $phone = $_POST['phone'];
    $user_type = $_POST['user_type'];

    // Use a prepared statement to prevent SQL injection
    if ($password) {
        $sql = "UPDATE users SET name = ?, email = ?, phone = ?, user_type = ?, password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $email, $phone, $user_type, $password, $user_id);
    } else {
        // If no new password, just update the other fields
        $sql = "UPDATE users SET name = ?, email = ?, phone = ?, user_type = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $phone, $user_type, $user_id);
    }

    if ($stmt->execute()) {
        echo "<p class='success'>User updated successfully!</p>";
        header("Refresh:0");
        exit();
    } else {
        echo "<p class='error'>Error updating user: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Users and Jobs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 30px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        select,
        textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .success {
            color: green;
            font-size: 16px;
        }

        .error {
            color: red;
            font-size: 16px;
        }

        .view-buttons, .add-user-btn {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        #addUserForm {
            display: none;
        }
    </style>

    <script>
        function toggleAddUserForm() {
            var form = document.getElementById("addUserForm");
            form.style.display = form.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body>

<div class="container">
    <h1>Manage Users</h1>

    <!-- Add User Button -->
    <div class="add-user-btn">
        <button onclick="toggleAddUserForm()">Add New User</button>
    </div>

    <!-- Add User Form (hidden initially) -->
    <form id="addUserForm" action="" method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="tel" name="phone" placeholder="Phone" required>
        <select name="user_type" required>
            <option value="freelancer">Freelancer</option>
            <option value="employer">Employer</option>
        </select>
        <input type="submit" name="add_user" value="Add User">
    </form>

    <!-- View Buttons -->
    <div class="view-buttons">
        <form action="" method="POST">
            <input type="submit" name="view_employers" value="View Employers">
        </form>

        <form action="" method="POST">
            <input type="submit" name="view_freelancers" value="View Freelancers">
        </form>
    </div>

    <!-- Display Users -->
    <?php if (!empty($fetched_users)) : ?>
        <table>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($fetched_users as $user): ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['phone']; ?></td>
                    <td><?php echo $user['user_type']; ?></td>
                    <td>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <input type="submit" name="edit_user" value="Edit">
                        </form>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <input type="submit" name="delete_user" value="Delete" onclick="return confirm('Are you sure you want to delete this user?');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- Edit User Form -->
    <?php if (isset($user)): ?>
        <h2>Edit User: <?php echo $user['name']; ?></h2>
        <form action="" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
            <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            <input type="password" name="password" placeholder="Leave blank to keep current password">
            <input type="tel" name="phone" value="<?php echo $user['phone']; ?>" required>
            <select name="user_type" required>
                <option value="freelancer" <?php echo $user['user_type'] === 'freelancer' ? 'selected' : ''; ?>>Freelancer</option>
                <option value="employer" <?php echo $user['user_type'] === 'employer' ? 'selected' : ''; ?>>Employer</option>
            </select>
            <input type="submit" name="update_user" value="Update User">
        </form>
    <?php endif; ?>

    <h1>Manage Jobs</h1>

    <!-- Fetch Jobs Table -->
    <table>
        <tr>
            <th>Job ID</th>
            <th>Job Title</th>
            <th>Job Description</th>
            <th>Category</th>
            <th>Hours</th>
            <th>Pay</th>
            <th>Type</th>
            <th>Deadline</th>
            <th>Skills</th>
            <th>Posted At</th>
            <th>Employer Name</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($fetched_jobs as $job): ?>
            <tr>
                <td><?php echo $job['id']; ?></td>
                <td><?php echo $job['job_title']; ?></td>
                <td><?php echo $job['job_description']; ?></td>
                <td><?php echo $job['job_category']; ?></td>
                <td><?php echo $job['job_hours']; ?></td>
                <td><?php echo $job['job_pay']; ?></td>
                <td><?php echo $job['job_type']; ?></td>
                <td><?php echo $job['job_deadline']; ?></td>
                <td><?php echo $job['job_skills']; ?></td>
                <td><?php echo $job['posted_at']; ?></td>
                <td><?php echo $job['employer_name']; ?></td>
                <td>
                    <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                        <input type="submit" name="delete_job" value="Delete" onclick="return confirm('Are you sure you want to delete this job?');">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>

<?php $conn->close(); ?>

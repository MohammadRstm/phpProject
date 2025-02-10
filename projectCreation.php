<?php
session_start();  // Start the session
// Check if the user is logged in and is an admin

if (!isset($_SESSION['ID']) || !isset($_SESSION["ID"]["adminID"])) {
    // Redirect to login page or show an error message
    header("Location: login.php");  // Replace 'login.php' with your login page
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include database connection
include('establisDBconnection.php');

$successMessage = "";

// Fetch manager IDs from the database
$managers = [];
$result = $conn->query("SELECT ID, firstName, lastName FROM manager");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $managers[] = $row;
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $projectName = $_POST['project-name'];
    $managerID = $_POST['manager-id'];
    $deadline = $_POST['deadline'];

    $formattedDeadline = date('Y-m-d', strtotime($deadline));
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO project (projectName, managerID, deadline, dateCreated, projectDone) VALUES (?, ?, ?, NOW(), 'NO')");
    $stmt->bind_param("sss", $projectName, $managerID, $formattedDeadline);

    // Execute and check if the insertion is successful
    if ($stmt->execute()) {
        $successMessage = "New project created successfully!";
    } else {
        $successMessage = "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project - Project Tracker Pro</title>
    <link rel="stylesheet" href="projectCreation.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Create New <span>Project</span></h1>
        <nav>
            <ul>
                <li><a href="admindb.php">Dashboard</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <p>Fill in the details to create a new project.</p>
    </section>

    <div class="container">
        <form class="project-form" method="POST" action="projectCreation.php">
            <label for="project-name">Project Name:</label>
            <input type="text" id="project-name" name="project-name" placeholder="Enter project name" required>

            <label for="manager-id">Manager:</label>
            <select id="manager-id" name="manager-id" required>
                <option value="">Select Manager</option>
                <?php foreach ($managers as $manager): ?>
                    <option value="<?= $manager['ID']; ?>">
                        <?= $manager['firstName'] . " " . $manager['lastName']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="deadline">Deadline:</label>
            <input type="date" id="deadline" name="deadline" required>

            <button type="submit" class="btn">Create Project</button>
            
            <?php if (!empty($successMessage)) : ?>
                <p class="success-message"><?php echo $successMessage; ?></p>
            <?php endif; ?>
        </form>
    </div>

    <footer class="footer">
        &copy; 2025 Project Tracker Pro.
    </footer>
</body>
</html>

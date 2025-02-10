<?php
session_start();
include "establisDBconnection.php";

if (!$_SESSION["WHO"]['isManager']){
    header('Location:login.php');
    exit();
}

$stmt = $conn->prepare("SELECT firstName , lastName FROM manager 
                        WHERE manager.ID = ?");
$stmt->bind_param("i" , $_SESSION['ID']['managerID']);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();
if ($result->num_rows == 1){// should be only one manager in results 
    $name = $row['firstName']." ".$row['lastName'];
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - Project Tracker Pro</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <h1>Project <span>Tracker</span> Pro</h1>
        <nav>
            <ul>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <h2>Welcome, <span>Manager <?php echo $name;?></span></h2>
        <p>You can manage your projects and tasks here.</p>
    </section>

    <div class="container">
        <a href="task.php" class="card-link">
            <div class="card">
                <h3>✅ Task Assignments</h3>
                <p>Assign tasks to team members and track their progress.</p>
            </div>
        </a>
        <a href="projectDetails.php" class="card-link">
            <div class="card">
                <h3>📊 Project Analytics</h3>
                <p>Monitor the progress and performance of your projects.</p>
            </div>
        </a>
        <a href="viewEmployees.php" class="card-link">
            <div class="card">
                <h3>👥 View Employees</h3>
                <p>View and manage the employees working under your projects.</p>
            </div>
        </a>
    </div>

    <footer class="footer">
        &copy; 2025 Project Tracker Pro. 
    </footer>
</body>
</html>
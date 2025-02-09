<?php
session_start();
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
                <li><a href="#">Profile</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <h2>Welcome, <span>Manager!</span></h2>
        <p>You can manage your projects and tasks here.</p>
    </section>

    <div class="container">
        
    <a href = "task.php" class ="card-link" >
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
    </div>

    <footer class="footer">
        &copy; 2025 Project Tracker Pro. 
    </footer>
</body>
</html>
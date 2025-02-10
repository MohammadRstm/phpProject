<?php 
session_start();

if (!$_SESSION["WHO"]['isAdmin'] || $_SESSION['ID']['adminID'] == -1){
    header('Location:login.php');
    exit();
}


?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Project Tracker Pro</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <h1>Project <span>Tracker</span> Pro</h1>
        <nav>
            <ul>
                <li><a href="#">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <h2>Welcome, <span>Admin!</span></h2>
        <p>You have full control over the platform.</p>
    </section>

    <div class="container">
       
        <a href="signusers.php" class="card-link">
            <div class="card">
                <h3>👥 Users</h3>
                <p>Manage user accounts and roles.</p>
            </div>
        </a>

        <a href="projectCreation.php" class="card-link">
            <div class="card">
                <h3>📂 Projects</h3>
                <p>Create projects and assign managers.</p>
            </div>
        </a>

        <a href = "task.php" class = "card-link">
        <div class="card">
            <h3>✅ Tasks</h3>
            <p>Manage and monitor tasks assigned across all projects.</p>
        </div>
        </a>
    </div>

    <footer class="footer">
        &copy; 2025 Project Tracker Pro. 
    </footer>
</body>
</html>
<?php
session_start();

if (!$_SESSION["WHO"]["isEmployee"]){
    header("Location:login.php");
    exit();
}

include "establisDBconnection.php";

$stmt = $conn->prepare("SELECT firstName , lastName FROM employee 
                        WHERE employee.ID = ?");
$stmt->bind_param("i" , $_SESSION['ID']['employeeID']);
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
    <title>Member Dashboard - Project Tracker Pro</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .cards-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            margin-top: 50px;
        }
    </style>
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
        <h2>Welcome, <span>Member <?php echo$name;  ?></span></h2>
        <p>Here are the tasks assigned to you.</p>
    </section>
    
    <div class="cards-wrapper">
        <a href="membertasks.php" class="card-link">
            <div class="card">
                <h3>✅ Your Tasks</h3>
                <p>Track and update your assigned tasks.</p>
            </div>
        </a>
        
        <a href="task.php" class="card-link">
            <div class="card">
                <h3>✅ View Tasks</h3>
                <p>Review your tasks and check the deadlines.</p>
            </div>
        </a>
    </div>
    
    <footer class="footer">
        &copy; 2025 Project Tracker Pro. 
    </footer>
</body>
</html>

    
 
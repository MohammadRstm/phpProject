<?php 
session_start();
include "establisDBconnection.php"; // Initiate database connection
if (isset($_POST['submit'])){
    $stmt = $conn->prepare('INSERT INTO tasks (assigned_to, `description`, priority, project_id, title) VALUES (?, ?, ?, ?, ?)');

    $title = $_POST['title'];
    $assigned_to = $_POST['assigned_to'];
    $desc = $_POST['desc'];
    $priority = $_POST['priority'];
    $project_id = $_POST['pid'];

    $stmt->bind_param('issis', $assigned_to, $desc, $priority, $project_id, $title);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table of Tasks</title>
    <link rel="stylesheet" href="tasks.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="tasks.js" defer></script>
</head>
<body>

<header class="header">
    <h1><span style="color: white;">Table of </span> <span>Tasks</span></h1>
    <nav>
        <ul>
            <li><a href="memberdb.html">Dashboard</a></li>
            <li><a href="logout.html">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <?php 
    if (isset($_SESSION['WHO']) && ($_SESSION['WHO']['isAdmin'] || $_SESSION['WHO']['isManager'])) { 
    ?>
        <form method="post" action="task.php">
            <table align="center">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Assigned to</th>
                        <th>Description</th>
                        <th>Priority</th>
                        <th>Project</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="title" placeholder="Enter title" required /></td>
                        <td><input type="number" name="assigned_to" placeholder="Enter member ID" required /></td>
                        <td><input type="text" name="desc" placeholder="(Optional)" /></td>
                        <td><input type="number" name="priority" placeholder="Enter priority" required /></td>
                        <td><input type="number" name="pid" placeholder="Enter project ID" required /></td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" name="submit" value="Add Task" />
        </form>
    <?php } ?>

    <table>
        <thead>
            <tr>
                <th>Task#</th>
                <th>Title</th>
                <th>Assigned To</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Project</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (isset($_SESSION['WHO']) && isset($_SESSION['ID'])){
                if ($_SESSION['WHO']['isEmployee']) {
                    $stmt = $conn->prepare('SELECT tasks.task_id, tasks.title, tasks.assigned_to, tasks.priority, project.projectName 
                                            FROM tasks
                                            JOIN project ON tasks.project_id = project.ID 
                                            WHERE tasks.assigned_to = ?');
                    $stmt->bind_param('i', $_SESSION['ID']['employeeID']);
                } elseif ($_SESSION['WHO']['isManager']){
                    $stmt = $conn->prepare('SELECT tasks.task_id, tasks.title, tasks.assigned_to, tasks.priority, project.projectName 
                                            FROM tasks 
                                            JOIN project ON tasks.project_id = project.ID 
                                            WHERE project.managerID = ?');
                    $stmt->bind_param('i', $_SESSION['ID']['managerID']);
                } elseif ($_SESSION['WHO']['isAdmin']) {
                    $stmt = $conn->prepare('SELECT tasks.task_id, tasks.title, tasks.assigned_to, tasks.priority, project.projectName 
                                            FROM tasks 
                                            ');
                }

                if (isset($stmt)) {
                    $stmt->execute();
                    $results = $stmt->get_result();
                    while ($row = $results->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['task_id']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['assigned_to']}</td>
                                <td>{$row['priority']}</td>
                                <td>{$row['projectName']}</td>
                            </tr>";
                    }
                    $stmt->close();
                }
            }
            ?>
        </tbody>
    </table>
</div>

<footer>
    <p>&copy; 2024 Project Tracker Pro</p>
</footer>

</body>
</html>

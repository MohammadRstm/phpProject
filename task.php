<?php 
session_start();
include "establisDBconnection.php"; // Initiate database connection
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit']) && $_POST['submit'] == 'Add Task') {
    $log = fopen('Log.txt','a');
    fwrite($log,'adding to tasks '.$_POST['title']);
    echo"<h1> adding</h1>";
    $stmt = $conn->prepare('INSERT INTO tasks (assigned_to, `description`, priority, project_id, title) VALUES (?, ?, ?, ?, ?)');

    $title = $_POST['title'];
    $assigned_to = $_POST['assigned_to'];
    $desc = $_POST['desc'];
    $priority = $_POST['priority'];
    $project_id = $_POST['pid'];

    $stmt->bind_param('issis', $assigned_to, $desc, $priority, $project_id, $title);
    $stmt->execute();
    $stmt->close();
    fclose($log);
    header("Location: task.php");
    exit();

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
                        <td><select name = 'priority' required>
                            <option value = 'High'>High</option>
                            <option value = 'Medium' selected>Medium</option>
                            <option value = 'Low'>Low</option>
                        </select>
                        </td>
                        <td><input type="number" name="pid" placeholder="Enter project ID" required /></td>
                    </tr>
                </tbody>
            </table>
            <div align = "center">
            <input type="submit" name="submit" value="Add Task" />
            </div>
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
                <th>Project#</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (isset($_SESSION['WHO']) && isset($_SESSION['ID'])){
                //echo"<h1>in</h1>";
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
                    $stmt = $conn->prepare('SELECT tasks.task_id, tasks.title, tasks.assigned_to, tasks.priority, project.projectName,tasks.status,project.ID 
                                            FROM tasks ,project
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
                                <td>{$row['status']}</td>
                                <td>{$row['projectName']}</td>
                                <td>{$row['ID']}</td>";?>
                                <td><a href="#" class="delete-task card-link" data-id="{$row['task_id']}">Delete</a></td>
                                <?php echo"
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
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-task").forEach(function (deleteBtn) {
        deleteBtn.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent page reload

            let taskId = this.getAttribute("data-id"); // Get task ID from data attribute
            let row = this.closest("tr"); // Get the row to remove

            if (confirm("Are you sure you want to delete this task?")) {
                fetch("delete_task.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "delete=" + taskId
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        row.remove(); // Remove the row from the table
                    } else {
                        alert("Failed to delete the task.");
                    }
                })
                .catch(error => console.error("Error:", error));
            }
        });
    });
});
</script>
        
</body>
</html>

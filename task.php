<?php 
session_start();
include "establisDBconnection.php"; // Initiate database connection
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit']) && $_POST['submit'] == 'Add Task') { // adding tasks
    $log = fopen('Log.txt','a');
    fwrite($log,'adding to tasks '.$_POST['title']);
    
    
    $title = $_POST['title'];
    $assigned_to = $_POST['assigned_to'];
    $desc = $_POST['desc'];
    $priority = $_POST['priority'];
    $project_id = $_POST['pid'];
    $for_manager = 0;
    $errorMessage = "";
    $employeeFound = false;
    if ($_SESSION['WHO']['isManager']) {// check if project is assigned to manager 
    $stmt = $conn->prepare ('SELECT project.ID 
                            FROM project , manager
                            where project.managerID = ?');
        $stmt->bind_param('i', $_SESSION['ID']['managerID']);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            if ($row['ID'] == $project_id){
                $for_manager = 1;
                break;
            }
        }
    $stmt->close();
    } 

    $stmt = $conn->prepare ('SELECT * FROM employee');
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if ($row['ID'] == $assigned_to){
            $employeeFound = true;
            break;
        }
    }

    if ( $_SESSION['WHO']['isManager'] && $for_manager && $employeeFound || $_SESSION['WHO']['isAdmin'] && $employeeFound) {
    $stmt = $conn->prepare('INSERT INTO tasks (assigned_to, `description`, priority, project_id, title) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('issis', $assigned_to, $desc, $priority, $project_id, $title);
    $stmt->execute();
    $stmt->close();
    fclose($log);
    header("Location: task.php");
    exit();
    }else if ($_SESSION['WHO']['isManager'] && !$for_manager ){
        $errorMessage = 'This project is not assigned to you';
        $_SESSION['errorMessage'] = $errorMessage;
        header("Location: task.php");
        exit();
    }else if (!$employeeFound){
        $errorMessage = "Employee not found";
        $_SESSION['errorMessage'] = $errorMessage;
        header("Location: task.php");
        exit();
    }

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
            <?php if (!empty($_SESSION['errorMessage'])): ?>
                <h3 class="error"><?php echo htmlspecialchars($_SESSION['errorMessage']); ?></h3>
                <?php unset($_SESSION['errorMessage']); // Clear the message after displaying ?>
            <?php endif; ?>
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
                if ($_SESSION['WHO']['isEmployee']) { 
                    $stmt = $conn->prepare('SELECT tasks.task_id, tasks.title, tasks.assigned_to, tasks.priority, project.projectName , tasks.status , project.ID
                                            FROM tasks , project
                                            WHERE tasks.project_id = project.ID and tasks.assigned_to = ?');
                    $stmt->bind_param('i', $_SESSION['ID']['employeeID']);
                } elseif ($_SESSION['WHO']['isManager']){
                    $stmt = $conn->prepare('SELECT tasks.task_id, tasks.title, tasks.assigned_to, tasks.priority, project.projectName , tasks.status , project.ID
                                            FROM tasks , project
                                            WHERE tasks.project_id = project.ID and project.managerID = ?');
                    $stmt->bind_param('i', $_SESSION['ID']['managerID']);
                } elseif ($_SESSION['WHO']['isAdmin']) {
                    $stmt = $conn->prepare('SELECT tasks.task_id, tasks.title, tasks.assigned_to, tasks.priority, project.projectName,tasks.status,project.ID 
                                            FROM tasks ,project
                                            WHERE tasks.project_id = project.ID
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
                                <td><a href="#" class="delete-task card-link" data-id="<?php echo $row['task_id']; ?>">Delete</a></td>
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
            event.preventDefault();

            let taskId = this.getAttribute("data-id");
            console.log("Task ID to delete:", taskId);  // Debugging

            if (!taskId) {
                alert("Task ID not found.");
                return;
            }

            let row = this.closest("tr");

            if (confirm("Are you sure you want to delete this task?")) {
                fetch("delete_task.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "delete=" + encodeURIComponent(taskId)
                })
                .then(response => response.text())
                .then(data => {
                    console.log("Server Response:", data); // Debugging
                    if (data.trim() === "success") {
                        row.remove();
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

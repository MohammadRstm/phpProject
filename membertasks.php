<?php session_start();
include "establisDBconnection.php";

$message="";

if (!isset($_SESSION["ID"]) || !$_SESSION["WHO"]["isEmployee"]) { // check if someone is signed in and that someone is an employee
    header("location: login.php");
    exit;
} else {
    // get all the tasks this employee has
    $stmt2 = $conn->prepare("SELECT * FROM tasks, project WHERE assigned_to = ? AND project.ID = tasks.project_id");
    $stmt2->bind_param("i", $_SESSION["ID"]["employeeID"]);
    $stmt2->execute();
    $result = $stmt2->get_result();

    if ($result->num_rows > 0) {
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $message = "No tasks assigned to you.";
    }
    $stmt2->close();

    if (isset($_POST["submit"])) {      
        $taskID = $_POST["taskID"];
        $status = (string)$_POST["status"]; // completed - In progress - Pending
        $projectName = (string)$_POST["projectName"];

        // fetch tasks for this employee
        $stmt = $conn->prepare("UPDATE tasks 
                                SET status = ?
                                WHERE assigned_to = ? AND task_id = ?
                                AND project_id IN (
                                    SELECT ID FROM project 
                                    WHERE project.projectName = ?
                                )"); // change the status of the task that is assigned to the current user and corresponds to the given project name 
        // the reason we check for the project ID is because multiple projects can have the same task 
        if (!$stmt) {
            $message = "Failed to prepare query";
            echo "ERROR CODE 1003";
            exit;
        }
        
        $stmt->bind_param("siis", $status, $_SESSION["ID"]["employeeID"], $taskID, $projectName);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $message = "Status successfully updated";
            // refresh page to update select tags 
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
    <link rel="stylesheet" href="membertasks.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <h1>My <span style="color:#ff5722;">Tasks</span></h1>
        <nav>
            <ul>
                  <li><a href="memberdb.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <div class="tasks-container">
        <?php if (!empty($tasks)): ?>
        <?php foreach ($tasks as $task): ?>
        <form method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
            <div class="task-card">
                <h3>Task: <?php echo htmlspecialchars($task['title']); ?></h3>
                <div class="form-group">
                    <label for="status<?php echo $task['task_id']; ?>">Status:</label>
                    <select name="status" id="status<?php echo $task['task_id']; ?>">
                        <option value="Pending" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>Not Started</option>
                        <option value="In Progress" <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                        <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Done</option>
                    </select>
                    <div class="project-name-container">
                        <label>Project:</label>
                        <span class="project-name"><?php echo htmlspecialchars($task['projectName']); ?></span>
                        <input type="hidden" name="projectName" value="<?php echo htmlspecialchars($task['projectName']); ?>" />
                    </div>
                    <input type='hidden' name='taskID' value='<?php echo $task['task_id']; ?>'/>
                </div>
                <div class="form-group file-upload-container">
                    <input type="submit" value='Update status' name='submit'/>
                </div>
            </div>
        </form>
        <?php endforeach; ?>
        <?php else: ?>
            <div class='no-tasks-message'>
                <p class = "no_tasks_message"><?php echo $message ?></p>
            </div>
        <?php endif; ?>
        </div>
    </div>
    <footer class="footer">
        2025 Project Tracker Pro.
    </footer>
</body>
</html>

<?php session_start();
include "establisDBconnection.php";

$message="";

if (!isset($_SESSION["ID"]) || !$_SESSION["WHO"]["isEmployee"]) { // check if someone is signed in and that someone is an employee
    header("login.php");
}else{
    // get all the task this employee has
    $stmt2 = $conn->prepare("SELECT * FROM tasks , projects WHERE assigned_to = ? and project.ID = tasks.task_id");
    $stmt2->bind_param("i", $_SESSION["ID"]["employeeID"]);
    $stmt2->execute();
    $result = $stmt2->get_result();

    if ($result->num_rows > 0) {
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $message = "No tasks assigned to you.";
    }
    $stmt2->close();
    if (isset($_POST["submit"])){      

            $taskID = $_POST["taskID"];
            $status = (string)$_POST["status"];// completed - In progress - Pending
            $projectName = (string)$_POST["projectName"];

            // fetch tasks for this employee
            $stmt = $conn->prepare("UPDATE tasks 
                                    SET status = ?
                                    WHERE assigned_to = ? AND task_id = ?
                                    AND project_id IN (
                                        SELECT ID FROM project 
                                        WHERE project.projectName = ?
                                    )");// change the status of the task that is assigned to the current user and corresponds to the given project name 
            $stmt -> bind_param("siis", $status,  $_SESSION["ID"]["employeeID"],$taskID, $projectName);
             
            if (!$stmt){
                $message = "failed to prepare query";
                echo "ERROR CODE 1003";
                exit;
            }
            $stmt -> execute();
            $affect_rows = $stmt -> affected_rows;
            if ($affect_rows > 0) {
                $message = "Status succesfully updated";
            }
            $stmt -> close();
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
        <h1>My <span style="color:#ff5722;" >Tasks</span></h1>
        <nav>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="tasks-container">
        <?php if (!empty($tasks)): ?>
        <?php foreach ($tasks as $task): ?>
        <form method = 'post' action = '<?php echo $_SERVER['PHP_SELF'];?>'>
            <div class="task-card">
                <h3>Task :<?php echo htmlspecialchars($task['title']);?></h3>
                <div class="form-group">
                    <label for="status<?php echo $task['task_id']; ?>">Status:</label>
                    <select id="status<?php echo $task['task_id']; ?>">
                        <option value="Pending" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>Not Started</option>
                        <option value="In Progress" <?php if ($task['status'] == 'In progress') echo 'selected'; ?>>In Progress</option>
                        <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Done</option>
                    </select>
                    <input type = 'text' name = 'projectName' value = '<?php echo htmlspecialchars($task['projectName']); ?>' placeholder="enter project's name"/>
                    <input type = 'hidden' name = 'taskID' value = '<?php echo$task['task_id'];?>'/>
                </div>
                <div class="form-group file-upload-container">
                    <input type="submit" value = 'Update status' name = 'submit<?php echo$task['task_id'];?>'/>
                </div>
            </div>
        </form>
        <?php endforeach; ?>
        <?php else: ?>
            <div class = 'no-tasks-message'>
                <p>No tasks assigned to you.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer">
        2025 Project Tracker Pro.
    </footer>

</body>
</html>
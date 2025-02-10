<?php
session_start();  // Start the session
// Include database connection
include('establisDBconnection.php');


// Check if the session contains the manager's ID
if (!$_SESSION["WHO"]['isManager']){
    header('Location:login.php');
    exit();
}

// Get the manager ID from the session
$managerID = $_SESSION["ID"]["managerID"];
// Query to fetch projects assigned to the manager
$query = "
    SELECT p.id AS project_id, p.projectName, t.title AS task_title, t.start_date, e.firstName, e.lastName 
    FROM project p
    LEFT JOIN tasks t ON p.id = t.project_id
    LEFT JOIN employee e ON t.assigned_to = e.ID
    WHERE p.managerID = ?
    ORDER BY p.id, t.start_date
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $managerID);
$stmt->execute();

// Check for errors in the query
if ($stmt->error) {
    echo "Error: " . $stmt->error;
}

$result = $stmt->get_result();
$projects = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch manager details
$managerQuery = "SELECT firstname, lastname FROM manager WHERE id = ?";
$managerStmt = $conn->prepare($managerQuery);
$managerStmt->bind_param("i", $managerID);
$managerStmt->execute();
$managerResult = $managerStmt->get_result();
$manager = $managerResult->fetch_assoc();
$managerStmt->close();
$conn->close();

// Group tasks by project
$groupedProjects = [];
foreach ($projects as $project) {
    $project_id = $project['project_id'];
    if (!isset($groupedProjects[$project_id])) {
        $groupedProjects[$project_id] = [
            'project_id' => $project_id,
            'projectName' => $project['projectName'],
            'tasks' => []
        ];
    }
    $groupedProjects[$project_id]['tasks'][] = [
        'task_title' => $project['task_title'],
        'start_date' => $project['start_date'],
        'firstName' => $project['firstName'],
        'lastName' => $project['lastName']
    ];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <link rel="stylesheet" href="projectDetails.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <h1>Project <span style="color:#ff5722">Details</span></h1>
        <nav>
            <ul>
                <li><a href="managerdb.php">Dashboard</a></li>
                <li><a href="logout.html">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <?php if (empty($groupedProjects)): ?>
            <p>No projects assigned.</p>
        <?php else: ?>
            <?php foreach ($groupedProjects as $project): ?>
            <div class="project-card">
                <h2>Project ID: <?php echo htmlspecialchars($project['project_id'] ?? ''); ?></h2>
                <h3><?php echo htmlspecialchars($project['projectName'] ?? ''); ?></h3>
                <div class="task-list">
                    <h3>Task Assignments</h3>
                    <ul>
                        <?php foreach ($project['tasks'] as $task): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($task['task_title'] ?? ''); ?></strong>
                            <div class="assigned-employees">
                                <span>Assigned to: <?php echo htmlspecialchars($task['firstName'] ?? '') . ' ' . htmlspecialchars($task['lastName'] ?? ''); ?></span>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <footer class="footer">
        &copy; 2025 Project Tracker Pro.
    </footer>
</body>
</html>
<?php
include "establisDBconnection.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"])) {
    $task_id = intval($_POST["delete"]);

    $stmt = $conn->prepare('DELETE FROM tasks WHERE task_id = ?');
    $stmt->bind_param('i', $task_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
}
?>

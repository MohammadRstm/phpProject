<?php
include "establisDBconnection.php"; // Database connection


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"])) {
    $task_id = intval($_POST["delete"]);

    if ($task_id === 0) {
        die("Invalid Task ID");
    }

    error_log("Deleting Task ID: " . $task_id); // Log the ID for debugging

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

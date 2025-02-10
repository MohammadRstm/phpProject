<?php
session_start();
include "establisDBconnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"], $_POST["replacement"])) {
    $employeeID = intval($_POST["delete"]);
    $replacementID = intval($_POST["replacement"]);

    if ($employeeID === $replacementID) {
        echo "Error: Replacement employee cannot be the same as the one being deleted.";
        exit();
    }

    // Check if the replacement manager exists
    $checkReplacementStmt = $conn->prepare("SELECT ID FROM employee WHERE ID = ?");
    $checkReplacementStmt->bind_param("i", $replacementID);
    $checkReplacementStmt->execute();
    $checkReplacementStmt->store_result();

    if ($checkReplacementStmt->num_rows === 0) {
        echo "Error: Replacement employee ID not found.";
        exit();
    }
    $checkReplacementStmt->close();

    // Update foreign key references in `projects` and `tables`
    $updateTasksstmt = $conn->prepare("UPDATE tasks SET assigned_to = ? WHERE assigned_to = ?");
    $updateTasksstmt->bind_param("ii", $replacementID, $employeeID);
    $updateTasksstmt->execute();
    $updateTasksstmt->close();

    // Delete the manager
    $deleteStmt = $conn->prepare("DELETE FROM employee WHERE ID = ?");
    $deleteStmt->bind_param("i", $employeeID);

    if ($deleteStmt->execute()) {
        echo "success";
    } else {
        echo "Error: Failed to delete manager.";
    }

    $deleteStmt->close();
    $conn->close();
} else {
    echo "Error: Invalid request.";
}
?>

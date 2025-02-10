<?php
session_start();
include "establisDBconnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"], $_POST["replacement"])) {
    $managerID = intval($_POST["delete"]);
    $replacementID = intval($_POST["replacement"]);

    if ($managerID === $replacementID) {
        echo "Error: Replacement manager cannot be the same as the one being deleted.";
        exit();
    }

    // Check if the replacement manager exists
    $checkReplacementStmt = $conn->prepare("SELECT ID FROM manager WHERE ID = ?");
    $checkReplacementStmt->bind_param("i", $replacementID);
    $checkReplacementStmt->execute();
    $checkReplacementStmt->store_result();

    if ($checkReplacementStmt->num_rows === 0) {
        echo "Error: Replacement manager ID not found.";
        exit();
    }
    $checkReplacementStmt->close();

    // Update foreign key references in `projects` and `tables`
    $updateProjectsStmt = $conn->prepare("UPDATE project SET managerID = ? WHERE managerID = ?");
    $updateProjectsStmt->bind_param("ii", $replacementID, $managerID);
    $updateProjectsStmt->execute();
    $updateProjectsStmt->close();

    $updateTablesStmt = $conn->prepare("UPDATE employee SET managerID = ? WHERE managerID = ?");
    $updateTablesStmt->bind_param("ii", $replacementID, $managerID);
    $updateTablesStmt->execute();
    $updateTablesStmt->close();

    // Delete the manager
    $deleteStmt = $conn->prepare("DELETE FROM manager WHERE ID = ?");
    $deleteStmt->bind_param("i", $managerID);

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

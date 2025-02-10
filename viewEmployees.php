<?php
session_start();

include "establisDBconnection.php";

// Check if the user is logged in
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's role (admin or manager)
$isAdmin = isset($_SESSION['ID']['adminID']);
$managerID = $_SESSION['ID']['managerID'] ?? null;

// Build the query based on the user's role (admin or manager)
if ($isAdmin) {
    // Admin can see all employees managed by any manager
    $query = "SELECT e.ID, e.firstName, e.lastName FROM employee e
              INNER JOIN manager m ON e.managerID = m.ID";
} elseif ($managerID) {
    // Manager can see only their own employees
    $query = "SELECT ID, firstName, lastName FROM employee WHERE managerID = ?";
}

// Prepare and execute the query
$stmt = $conn->prepare($query);

// Bind the parameter for the manager (only for managers, not admins)
if (!$isAdmin) {
    $stmt->bind_param("i", $managerID);
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch all the employees
$employees = $result->fetch_all(MYSQLI_ASSOC);

// Close the statement and connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employees - Project Tracker Pro</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Table specific styles */
        table {
            width: 70%; /* Made the table smaller */
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #1d2128; /* Darker background for the table */
            color: #fff;
            border-radius: 8px; /* Rounded corners for the table */
            overflow: hidden; /* Prevents table border from overflowing */
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #333; /* Dark border */
        }

        th {
            background-color: #222; /* Darker background for header */
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr:nth-child(even) {
            background-color: #2a2f38; /* Slightly lighter background for even rows */
        }

        tr:hover {
            background-color: #444b57; /* Dark hover effect */
        }

        td, th {
            font-size: 14px; /* Slightly smaller font */
            text-transform: capitalize;
        }

        /* Styling for header */
        .header h1 {
            font-size: 2rem;
            color: #fff;
        }
        
        .header nav ul {
            display: flex;
            list-style-type: none;
        }

        .header nav ul li {
            margin-right: 20px;
        }

        .header nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        /* Footer styles */
        .footer {
            text-align: center;
            color: #fff;
            margin-top: 20px;
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>View <span style="color: #ff5722; ">Employees</span></h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <p>List of employees under your management.</p>
    </section>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?= htmlspecialchars($employee['ID']) ?></td>
                        <td><?= htmlspecialchars($employee['firstName']) ?></td>
                        <td><?= htmlspecialchars($employee['lastName']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
        &copy; 2025 Project Tracker Pro.
    </footer>
</body>
</html>
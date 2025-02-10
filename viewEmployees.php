<?php
session_start();
include "establisDBconnection.php";

// Check if the user is logged in
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit();
}

// Determine user role
$isAdmin = $_SESSION['WHO']['isAdmin'] ?? false;
$isManager = $_SESSION['WHO']['isManager'] ?? false;

// Query to get all employees (both managers and admins can see all employees)
$query = "SELECT ID, firstName, lastName FROM employee";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$employees = $result->fetch_all(MYSQLI_ASSOC);

// Close connection
$stmt->close();
$conn->close();

// Determine dashboard redirection
$dashboardPage = $isAdmin ? "admindb.php" : ($isManager ? "managerdb.php" : "#");
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
        table {
            width: 70%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #1d2128;
            color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #333;
        }
        th {
            background-color: #222;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        tr:nth-child(even) {
            background-color: #2a2f38;
        }
        tr:hover {
            background-color: #444b57;
        }
        td, th {
            font-size: 14px;
            text-transform: capitalize;
        }
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
        <h1>View <span style="color: #ff5722;">Employees</span></h1>
        <nav>
            <ul>
                <li><a href="<?= $dashboardPage ?>">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <p>List of all employees.</p>
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

<?php 
session_start();

// database connection
include "establisDBconnection.php";

// Fetch all managers from the database
$query = "SELECT * FROM manager";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Managers - Project Tracker Pro</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Table specific styles */
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

        /* Fixing scroll and background */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            position: relative;
            background-image: url('643353.png');
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            color: #fff;
            text-align: center;
            overflow-x: hidden;
            min-height: 100vh; /* Ensures the body takes at least the full height */
            display: flex;
            flex-direction: column;
        }

        body::before {
            content: ''; 
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .container {
            display: flex;
            justify-content: center;
            flex-direction: column;
            margin-top: 50px;
            flex-wrap: wrap;
            padding: 0 15px;
            overflow: auto;
        }
        
    </style>
</head>
<body>
    <header class="header">
        <h1>Manage <span>Managers</span></h1>
        <nav>
            <ul>
                <li><a href="admindb.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <p>Below is the list of all managers in the system.</p>
    </section>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Age</th>
                    <th>Username</th>
                    <th>Date of Entry</th>
                    <th>Date of Retirement</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['ID']; ?></td>
                        <td><?php echo $row['firstName']; ?></td>
                        <td><?php echo $row['lastName']; ?></td>
                        <td><?php echo $row['age']; ?></td>
                        <td><?php echo $row['userName']; ?></td>
                        <td><?php echo $row['dateOfEntry']; ?></td>
                        <td><?php echo $row['dateOfRetirement'] ?: 'N/A'; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    
</body>
</html>
<?php 
include "establisDBconnection.php";
session_start();

if (isset($_GET["member"])) {
    $_SESSION['signup'] = 'member';
    $managers = [];
    $result = $conn->query("SELECT ID, firstName, lastName FROM manager");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $managers[] = $row;
        }
    }

    
}else if (isset($_GET['manager'])) {
    $_SESSION['signup'] = 'manager';
}else if (isset($_GET['admin'])) {
    $_SESSION['signup'] = 'admin';
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Users - Project Tracker Pro</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="signusers.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <h1>Manage <span>Users</span></h1>
        <nav>
            <ul>
                <li><a href="admindb.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
<?php if (!isset($_GET["manager"]) && !isset($_GET["admin"]) && !isset($_GET['member'])) {?>
    <?php  if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo "<h3>User Added Successfully</h3>";
    } else if (isset($_GET['status']) && $_GET['status'] == 'failure') {
        echo "<h3>Failed To Add User</h3>";
    } ?>
    <div class = "container">
    <a href = "signusers.php?member=1" class ="card-link" >
        <div class="card">
            <h3>✅ Sign Up A Member</h3>
            <p>Add a new member to your company.</p>
        </div>
    </a>
    <a href = "signusers.php?manager=1" class ="card-link" >
        <div class="card">
            <h3>✅ Sign Up A Manager</h3>
            <p>Add a new manager to your company.</p>
        </div>
    </a>
    <a href = "signusers.php?admin=1" class ="card-link" >
        <div class="card">
            <h3>✅ Sign Up A New Admin</h3>
            <p>Add a new admin to your company.</p>
        </div>
    </a>
    </div>
<?php } else{ ?>
    <div class="container">
        <div class="form-container">
            <h3>Create New User</h3>
            <form action="signup.php" method="POST">
                <div class="form-group">
                    <label for="first-name">Name</label>
                    <div class="name-inputs">
                        <input type="text" id="first-name" name="firstname" placeholder="Enter first name" required>
                        <input type="text" id="last-name" name="lastname" placeholder="Enter last name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                </div>
                <div class="form-group">
                    <label for="confpassword">Confirm Password</label>
                    <input type="password" id="password" name="confpassword" placeholder="re-enter password" required>
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="number" name="age" placeholder="enter age" required>
                </div>

                <?php if (isset($_GET['member']) && $_GET['member'] == 1){?>
                    <div class="form-group">
                    <label for="assignManager">Assign A Manager TO Member</label>
                    <select name ="assignManager" id = "assignManager">
                        <option value = "">Select Manger</option>
                        <?php foreach($managers as $manager):?>
                            <option value =" <?= $manager['ID']; ?>">
                            <?= $manager['firstName'] . " " . $manager['lastName']; ?> 
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div> 
                <?php }?>
                <button type="submit" name = 'submit' class="signup-btn">Create User</button>
            </form>
        </div>
    </div>
<?php } ?>
    <footer class="footer">
        &copy; 2025 Project Tracker Pro.
    </footer>
</body>
</html>

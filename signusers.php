<?php 
include "establisDBconnection.php";
session_start();

// these vars are sent back from sigup.php incase an invalid input is recieved 

$firstname = $lastname = $username = $age = "";
$selectedManager = "";

if (isset($_GET["firstname"])) $firstname = htmlspecialchars($_GET["firstname"]);
if (isset($_GET["lastname"])) $lastname = htmlspecialchars($_GET["lastname"]);
if (isset($_GET["username"])) $username = htmlspecialchars($_GET["username"]);
if (isset($_GET["age"])) $age = htmlspecialchars($_GET["age"]);
if (isset($_GET["assignManager"])) $selectedManager = htmlspecialchars($_GET["assignManager"]);

// if a member is being signed in we need to show the admin a list of managers he can assign him to

if (isset($_GET["member"])) {
    $_SESSION['signup'] = 'member'; // this is to help sign.php know who is being signed up
    $managers = [];
    $result = $conn->query("SELECT ID, firstName, lastName FROM manager");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $managers[] = $row;
        }
    }
} else if (isset($_GET['manager'])) {
    $_SESSION['signup'] = 'manager';
} else if (isset($_GET['admin'])) {
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
<!-- signup.php sends a get request coming back to here with either of these vars set to 1 if we still didn't go to signup.php that means its our
 first time entering the page so we need to know who we are going to sign up -->
<?php if (!isset($_GET["manager"]) && !isset($_GET["admin"]) && !isset($_GET['member'])) { ?>
    <?php  
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo "<h3>User Added Successfully</h3>";
    } else if (isset($_GET['status']) && $_GET['status'] == 'failure') {
        echo "<h3>Failed To Add User</h3>";
    } 
    ?>
    <div class="container">
        <a href="signusers.php?member=1" class="card-link">
            <div class="card">
                <h3>✅ Sign Up A Member</h3>
                <p>Add a new member to your company.</p>
            </div>
        </a>
        <a href="signusers.php?manager=1" class="card-link">
            <div class="card">
                <h3>✅ Sign Up A Manager</h3>
                <p>Add a new manager to your company.</p>
            </div>
        </a>
        <a href="signusers.php?admin=1" class="card-link">
            <div class="card">
                <h3>✅ Sign Up A New Admin</h3>
                <p>Add a new admin to your company.</p>
            </div>
        </a>
    </div>

<?php } else { ?>
    <div class="container">
        <div class="form-container">
            <h3>Create New User</h3>
            <form action="signup.php" method="POST">
                <div class="form-group">
                    <label for="first-name">Name</label>
                    <div class="name-inputs">
                        <input type="text" id="first-name" name="firstname" placeholder="Enter first name" value="<?= $firstname ?>" required>
                        <input type="text" id="last-name" name="lastname" placeholder="Enter last name" value="<?= $lastname ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter username" value="<?= $username ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                </div>
                <div class="form-group">
                    <label for="confpassword">Confirm Password</label>
                    <input type="password" id="confpassword" name="confpassword" placeholder="Re-enter password" required>
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" placeholder="Enter age" value="<?= $age ?>" min = '18' max = '100' required>
                </div>
                <?php if (isset($_GET['member']) && $_GET['member'] == 1) { ?>
                    <div class="form-group">
                        <label for="assignManager">Assign A Manager To Member</label>
                        <select name="assignManager" id="assignManager" required>
                            <option value="">Select Manager</option>
                            <?php foreach ($managers as $manager) : ?>
                                <option value="<?= $manager['ID']; ?>" <?= ($selectedManager == $manager['ID']) ? 'selected' : ''; ?>>
                                    <?= $manager['firstName'] . " " . $manager['lastName']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php } ?>

                <!-- Display Error Messages -->
                <?php if (isset($_GET['username'])) { ?>
                    <h4 style="color:red;">Username must be greater than 6 characters</h4>
                <?php } else if (isset($_GET['password'])) { 
                    if (isset($_GET['missmatch'])) { ?>
                        <h4 style="color:red;">Confirm password unequal to password</h4>
                    <?php } else if (isset($_GET['special'])) { ?>
                        <h4 style="color:red;">Password must contain at least one special character</h4>
                    <?php } else if (isset($_GET['number'])) { ?>
                        <h4 style="color:red;">Password must contain at least one number</h4>
                    <?php } else if (isset($_GET['capital'])) { ?>
                        <h4 style="color:red;">Password must contain at least one capital letter</h4>
                    <?php } else if (isset($_GET['matchusername'])) { ?>
                        <h4 style="color:red;">Password can't be the same as the username</h4>
                    <?php } else if (isset($_GET['foundinusername'])) { ?>
                        <h4 style="color:red;">Password can't be found in the username</h4>
                    <?php } else if (isset($_GET['length'])) { ?>
                        <h4 style="color:red;">Password must be greater than 6 characters</h4>
                    <?php } 
                } ?>
                <?php if (isset($_GET['userFound'])){ ?>
                    <h4 style="color:red;">Username already exists</h4>
                <?php } ?>

                <button type="submit" name="submit" class="signup-btn">Create User</button>
            </form>
        </div>
    </div>
<?php } ?>

<footer class="footer">
    &copy; 2025 Project Tracker Pro.
</footer>
</body>
</html>

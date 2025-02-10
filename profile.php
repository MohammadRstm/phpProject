<?php 
session_start();
include "establisDBconnection.php";

if (!isset($_SESSION["WHO"])){
    header("Location:login.php");
    exit();
}

if ($_SESSION["WHO"]["isManager"]){
    $user_id = $_SESSION["ID"]["managerID"];

    $stmt = $conn -> prepare("SELECT * FROM manager WHERE ID = ?");
    $stmt -> bind_param("i", $user_id);

    $stmt -> execute();
    $result = $stmt->get_result();
    $row = $result -> fetch_assoc();

    $username = $row["userName"];
    $name = $row["firstName"]." ".$row['lastName'];
    $age = $row['age'];
    $entry_date = $row['dateOfEntry'];
    $id = $row["ID"];
    $image_path = $row["profilePicture"];

}elseif ($_SESSION["WHO"]["isEmployee"]){
    $user_id = $_SESSION["ID"]["employeeID"];
    $stmt = $conn -> prepare("SELECT * FROM employee WHERE ID = ?");
    $stmt -> bind_param("i", $user_id);

    $stmt -> execute();
    $result = $stmt->get_result();
    $row = $result -> fetch_assoc();

    $username = $row["userName"];
    $name = $row["firstName"]." ".$row['lastName'];
    $age = $row['age'];
    $entry_date = $row['dateOfEntry'];
    $id = $row["ID"];
    $image_path = $row["profilePicture"];

}else if ($_SESSION["WHO"]["isAdmin"]){
    $user_id = $_SESSION["ID"]["adminID"];
    $stmt = $conn -> prepare("SELECT * FROM `admin` WHERE id = ?");
    $stmt -> bind_param("i", $user_id);

    $stmt -> execute();
    $result = $stmt->get_result();
    $row = $result -> fetch_assoc();

    $username = $row["username"];
    $id = $row["id"];
    $name = $row["first_name"]." ".$row['last_name'];
    $age = $row['age'];
    $entry_date = $row['date_started_working'];
    $image_path = $row["profilePicture"];
}else{
    header("Location:login.php");
    exit();
}


if(isset($_POST["submit"])) {
    $target_dir = "uploads/"; // Folder to store images
    $target_file = $target_dir . basename($_FILES["profileImage"]["name"]);
    
    // Check if it's a valid image
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];

    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file)) {
            
            if ($_SESSION['WHO']['isManager']){

            $stmt = $conn->prepare("UPDATE manager SET profilePicture=? WHERE ID=?");
            $stmt->bind_param("si", $target_file, $user_id);
           
            }else if ($_SESSION['WHO']['isEmployee']){
                $stmt = $conn->prepare("UPDATE employee SET profilePicture=? WHERE ID=?");
                $stmt->bind_param("si", $target_file, $user_id);
               
            }else if ($_SESSION['WHO']['isAdmin']){
                $stmt = $conn->prepare("UPDATE `admin` SET profilePicture=? WHERE id=?");
                $stmt->bind_param("si", $target_file, $user_id);
                
            }else{
                header("Location:login.php");
                exit();
            }


            if ($stmt->execute()) {
                header("Location:profile.php");// refresh page
                exit();
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
}

$stmt->close();
$conn -> close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="header" style = " z-index: 1000;  top: 0;
                                left: 0;">
        <h1>Project <span>Tracker</span> Pro</h1>
        <nav>
            <ul>
            <?php if ($_SESSION["WHO"]["isManager"]){
            echo "<li><a href=\"managerdb.php\">Dashboard</a></li>";
            }else if ($_SESSION["WHO"]["isEmployee"]){
            echo "<li><a href=\"memberdb.php\">Dashboard</a></li>";
            }else if ($_SESSION["WHO"]["isAdmin"]){
            echo "<li><a href=\"admindb.php\">Dashboard</a></li>";
            }else{
                header("Location:login.php");
                exit();
            }
            ?>
            <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="profile-container" style =" margin-top: 80px;">
      <form action="profile.php" method="post" enctype="multipart/form-data">
        <div class="profile-left">
            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Profile Picture" class="profile-image"><br>
            <input type="file" name="profileImage" class = "change-picture"  style="display: none;"  id="fileInput" required>
            <label for="fileInput" class="custom-file-button">Choose Image</label>
            <input type="submit" name="submit" value="Change Picture" class="change-picture"/>
        </div>
      </form>
        <div class="profile-right">
            <h2><?php echo $name; ?></h2></br>
            <p><strong>ID :</strong><?php echo $id; ?></p></br>
            <p><strong>Username:</strong><?php echo $username; ?></p></br>
            <p><strong>Date of Entry:</strong> <?php echo (string)$entry_date; ?></p></br>
            <p><strong>Age:</strong><?php echo $age; ?></p></br>
        </div>
    </div>
</body>
</html>

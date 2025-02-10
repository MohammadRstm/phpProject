<?php session_start(); 
include 'establisDBconnection.php';// establish DB connection 
$_SESSION['ID']=[
    'adminID'=> -1,
    'employeeID'=> -1,
    'managerID'=> -1,
];
$_SESSION['WHO'] =[
    'isManager'=> false,
    'isAdmin'=> false,
    'isEmployee'=> false,
];
$_SESSION['errorMessage'] = "";

$errorPassMessage = "";
$errorUserMessage = "";
$stmt;

$log = fopen("Log.txt","a") or die("file failed to open");
if ($log){
    fwrite($log , date("y-m-d")." in Login helper\n");
}

    if (isset($_POST["submit"])) {
        if ($log) {
            fwrite($log, date("y-m-d")." Role: " . $_POST["role"] . "\n");  // Write the value of $who to the log
            fclose($log);  // Close the log file
        } else {
            // If file couldn't be opened
            echo "Error: Could not open log file.";
        }
        // take username and password
        $username = $_POST["username"]; 
        $password= $_POST["password"];
        $who = $_POST["role"];

        if ($who == "member"){
            $_SESSION["WHO"]["isEmployee"] = true;
            //check if user in database in database
            
            $stmt = $conn-> prepare("SELECT * FROM employee WHERE userName = ? and employeePasswordHash = ?");
            $stmt->bind_param("ss", $username , $password);

            $stmt->execute();

            $result = $stmt->get_result();
            $count = 0;
            while($row = $result->fetch_assoc()) {
                $_SESSION['ID']['employeeID'] = $row['ID'];
                $count++;
            }
            if ($count == 1) {// account found and is of type employee
                $stmt->close();
                $conn->close();
                header("Location:memberdb.php");
                exit;
            }
            else if ($count == 0) {// no account 
                $_SESSION["WHO"]["isEmployee"] = false;
                $stmt->close();
                $conn->close();
                header("Location:login.php");
                exit;
            }else{// shouldnt happen 
                $_SESSION["WHO"]["isEmployee"] = false;
                $stmt->close();
                $conn->close();
                header("Location:login.php");
                exit;
            }
        }else if ($who == "manager") {
            $_SESSION["WHO"]["isManager"] = true;
            $stmt = $conn->prepare("SELECT ID FROM manager WHERE userName = ? AND managerPasswordHash = ?");
            $stmt->bind_param("ss", $username , $password);
            
            $stmt->execute();

            $result = $stmt->get_result();
            $count = 0;

            while ($row = $result->fetch_assoc()) { 
                $_SESSION["ID"]["managerID"] = $row["ID"];
                $count++;
            }
            if ($count == 1) {
                $stmt->close();
                $conn->close();
                header("Location:managerdb.php"); 
                exit;
            }else if ($count == 0) {// no account 
                $_SESSION["WHO"]["isManager"] = false;
                $stmt->close();
                $conn->close();
                exit;
            }else{// shouldnt happen 
                $_SESSION["WHO"]["isManager"] = false;
                $stmt->close();
                $conn->close();
                exit;
            }
        }else if ($who == "admin"){
            $_SESSION["WHO"]["isAdmin"] = true;
            $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ? and password = ?");
            $stmt->bind_param("ss", $username , $password);

            $stmt->execute();

            $result = $stmt->get_result();
            $count = 0;

            while ($row = $result->fetch_assoc()) {
                $_SESSION["ID"]["adminID"] = $row["id"];
                 $count++;
            }

            if ($count == 1) {
                $stmt->close();
                $conn->close();
                header("Location:admindb.php");
                exit;
            }else if($count == 0) {
                $_SESSION["WHO"]["isAdmin"] = false;
                $stmt->close();
                $conn->close();
                header("Location:login.php");
                exit;
            }else{
                $_SESSION["WHO"]["isAdmin"] = false;
                $stmt->close();
                $conn->close();
                header("Location:login.php");
                exit;
            }
        }       
    }

?>
    




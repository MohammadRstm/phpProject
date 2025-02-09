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


function checkUsername($username) {

    $len = strlen($username);

    // lenght check
    if ($len <= 6){
        $errorUserMessage = "username can't be less than or equal 6 characters\n";
        return false;
    }
    // special characters check
    if (preg_match("/[^a-zA-z0-9]/", $username)) {
        $errorUserMessage = "username can't contain any special characters\n";
        return false;
    }

    // space check
    for ( $i = 0; $i < strlen($username); $i++ ) {
        if ($username[$i] == ' ') {
            $errorUserMessage = "username can't contain password\n";
            return false;
        }
    }

    return true;

}

function checkPassword($password , $username){// func to check the validity of the password as a string

    // SAME AS USER NAME CHECK
    if ($password == $username){
         $errorPassMessage = "password can't be the same as the password\n";
         return false;
    }
    
    $passlen = strlen($password);
    $userlen = strlen($username);

    // LENGTH CHECK
    if ($passlen < 6) {
        $errorPassMessage = "password can't be 6 character or less\n";
        return false;
    }

    // SUB-STRING CHECK
    for ($i = 0; $i < $passlen - $userlen + 1 ; $i++) {
        if (strcmp( substr($username , $i,$passlen), $password) == 0) {
            $errorPassMessage = "password can't be a sub-string of your username\n";
            return false;
         }
    }

    // CHECK FOR CAPITAL LETTERS 
   if (!preg_match('/[A-Z]/' , $password)){
    $errorPassMessage = "password must contain atleast one capital letter\n";
    return false;
   }
   // check for spcial characters
   if (preg_match('/[^a-zA-Z0-9]/',$password)){
    $errorPassMessage = "password must contain atleast one special character\n";
    return false;
   }

   if (preg_match('/[^0-9]/' , $password)){
    $errorPassMessage = "password must contain atleast one number\n";
    return false;
   }

   return true;


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
        // user and pass check (triggers)
       /* if (!checkUsername($username) ){
            echo $errorUserMessage;
            header("location : login.php");
        }  */
       /* if (!checkPassword($password , $username) ){
            echo $errorUserMessage;
            header("location : login.php");

        } */   

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
                header("Location:memberdb.html");
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
                header("Location:managerdb.html"); 
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
                header("Location:admindb.html");
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
    




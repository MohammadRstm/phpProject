<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php

require 'establisDBconnection';// establish DB connection 
session_start();
$_SESSION['managerID'] = -1;
$errorPassMessage = "";
$errorUserMessage = "";
$stmt;

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
        if (username[$i] == ' ') {
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
        // take username and password
        $username = $_POST["username"];
        $password= $_POST["password"];
        // user and pass check (triggers)
        if (!checkUsername($username) ){
            echo $errorUserMessage;
            header("location : login.php");

        }  
        if (!checkPassword($password , $username) ){
            echo $errorUserMessage;
            header("location : login.php");

        }       

        if ($_POST["submit"] = "signInAsEmployee"){

            //check if user in database in database
            
            $stmt = $conn-> prepare("SELECT COUNT(ID) FROM employee WHERE username = ? and employeePasswordHash = ?");
            $stmt->bind_param("ss", $username , $password);

            $stmt->execute();

            $result = $stmt->get_result();
            $count = 0;
            $row;

            while($row = $result->fetch_assoc()) {// move through result (should have only one row)
                $count++;
            }

            if ($count == 1) {// account found and is of type employee
                echo "WELOME ".$row['firstname']." ".$row['lastname']."<br>YOUR ARE SIGNED IN AS AN EMPLOYEE"; 
                exit;
            }
            else if ($count == 0) {// no account 
                echo "SORRY WE DON'T HAVE ANYONE WITH THIS ACCOUNT.";
                exit;
            }else{// shouldnt happen 
                echo"ERRO CODE : 1001";
                exit;
            }
        }else if ($_POST["submit"] = "signInAsManager") {

            $stmt = $conn->prepare("SELECT COUNT(ID) FROM manager WHERE userName = ? AND managerPasswrodHash = ?");
            $stmt->bind_param("ss", $username , $password);
            
            $stmt->execute();

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) { $count++;}
            if ($count == 1) { 
                echo "WELCOME ".$row["firstname"]." ".$row["lastname"]."<br>YOU SIGNED IN AS A MANAGER";
                $_SESSION["managerID"]  = $row[ID];// save manager's id incase a signup occures
                exit;
            }else if ($count == 0) {// no account 
                echo "SORRY WE DON'T HAVE ANYONE WITH THIS ACCOUNT.";
                exit;
            }else{// shouldnt happen 
                echo"ERRO CODE : 1001";
                exit;
            }
        }

        $stmt->close();
        $conn->close();

    }

?>
    
</body>
</html>




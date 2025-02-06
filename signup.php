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

if (!$_SESSION["isAdmin"] && !$_SESSION["isManager"] ){ // check if the user is a manager (if not head to login page)
    echo "ONLY ADMINS/MANAGERS CAN ACCESS THIS PAGE";
    header ("location : login.php");
    exit;
}

$errorMessage = "";// for password

function checkUsername($username) {

    $len = strlen($username);

    // lenght check
    if ($len <= 6){
        $errorserMessage = "username can't be less than or equal 6 characters\n";
        return false;
    }
    // special characters check
    if (preg_match("/[^a-zA-z0-9]/", $username)) {
        $errorMessage = "username can't contain any special characters\n";
        return false;
    }

    return true;

}

function checkPassword($password , $username , $password_confirm){// func to check the validity of the password as a string
    // check if password confirmation match password
    if (strcmp($password , $password_confirm) != 0) {
        $errorMessage = "password conformation doesn't match\n";
        return false;
    }

    // SAME AS USER NAME CHECK
    if ($password == $username){
         $errorMessage = "password can't be the same as the password\n";
         return false;
    }
    
    $passlen = strlen($password);
    $userlen = strlen($username);

    // LENGTH CHECK
    if ($passlen < 6) {
        $errorMessage = "password can't be 6 character or less\n";
        return false;
    }

    // SUB-STRING CHECK
    for ($i = 0; $i < $passlen - $userlen + 1 ; $i++) {
        if (strcmp( substr($username , $i,$passlen), $password) == 0) {
            $errorMessage = "password can't be a sub-string of your username\n";
            return false;
         }
    }

    // CHECK FOR CAPITAL LETTERS 
   if (!preg_match('/[A-Z]/' , $password)){
    $errorMessage = "password must contain atleast one capital letter\n";
    return false;
   }
   // check for spcial characters
   if (preg_match('/[^a-zA-Z0-9]/',$password)){
    $errorMessage = "password must contain atleast one special character\n";
    return false;
   }

   if (preg_match('/[^0-9]/' , $password)){
    $errorMessage = "password must contain atleast one number\n";
    return false;
   }

   return true;


}
function checkName($name){
     // special characters check
     if (preg_match("/[^a-zA-z0-9]/", $name)) {
        $errorMessage = "username can't contain any special characters\n";
        return false;
    }
}





// connection established 

// get info for new users 

if (isset($_POST["submit"])){
    $username = $_POST["username"];
    $password = $_POST["password"];
    $password_confirm = $_POST["confpass"];
    $firstname  = $_POST["firstname"];
    $lastname  = $_POST["lastname"];
    $age = $_POST["age"];
    


    //some constraints on the username 
    if (!checkUsername($username) ){
        echo $errorUserMessage;
        header("location : signup.php");
    }

    // check password 
    if (!checkPassword($password , $username , $password_confirm)){
        echo $errorPassMessage;
        header("location : signup.php");
    }

    if (!checkName( $firstname) || !checkName( $lastname) ){
        echo $errorUserMessage;
        header("location : signup.php");
    }

    // All checks are good 
    $stmt;
    if ($_SESSION["WHO"]['isManager']){
    $stmt = $conn->prepare("INSERT INTO TABLE
                                    employee (firstname , lastname , age , username , employeePasswordHash , managerID ) 
                                    values (? , ? , ? , ? , ? , ?  , ?)");
    }else if ($_SESSION["WHO"]["isAdmin"]){
    $stmt = $conn->prepare("INSERT INTO TABLE
                                  manager (firstname , lastname , age , username , employeePasswordHash , managerID) 
        values (? , ? , ? , ? , ? , ?  , ?)");
    }

    $stmt->bind_param("ssissis",$firstname , $lastname , $age , $username , $password , $_SESSION['managerID']);

    $stmt->execute();

    if ($stmt->affected_rows() > 0){
        echo "employee successfully added";
    }else{// shouldn't happen 
        echo "ERROR CODE : 1002";
    }

    $stmt->close();
    $conn->close();
}
?>

</body>
</html>
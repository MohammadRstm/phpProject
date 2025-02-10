<?php

include 'establisDBconnection.php';// establish DB connection 
session_start();

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
    $password_confirm = $_POST["confpassword"];
    $firstname  = $_POST["firstname"];
    $lastname  = $_POST["lastname"];
    $age = $_POST["age"];
    if (isset($_POST["assignManager"])) $manager = (int)$_POST["assignManager"]; // the id of the manager that the member will work for 
    


    //some constraints on the username 
   /* if (!checkUsername($username) ){
        echo $errorUserMessage;
        header("location : signup.php");
        exit();
    }

    // check password 
    if (!checkPassword($password , $username , $password_confirm)){
        echo $errorPassMessage;
        header("location : signup.php");
    }

    if (!checkName( $firstname) || !checkName( $lastname) ){
        echo $errorUserMessage;
        header("location : signup.php");
    }*/

    // All checks are good 
    $stmt;
    if ($_SESSION['signup'] == "admin"){
    $stmt = $conn->prepare("INSERT INTO
                                    `admin` (first_name , last_name , age , username , `password`) 
                                    values (? , ? , ? , ? , ?)");
    $stmt->bind_param("ssiss", $firstname, $lastname , $age ,$username , $password);
    }else if ($_SESSION['signup'] == "manager"){
    $stmt = $conn->prepare("INSERT INTO 
                                  manager (firstname , lastname , age , username , managerPasswordHash) 
        values (? , ? , ? , ? , ?)");
    $stmt->bind_param("ssiss", $firstname, $lastname , $age ,$username , $password);
    }else if ($_SESSION['signup'] == "member"){
        $stmt = $conn->prepare("INSERT INTO 
                                  employee (firstname , lastname , age , username , employeePasswordHash , managerID) 
        values (? , ? , ? , ? , ? , ?)");
    $stmt->bind_param("ssissi", $firstname, $lastname , $age ,$username ,$password ,  $manager);
    }

    $stmt->execute();
    
    if ($stmt->affected_rows > 0){
        $_SESSION['added'] = true;
    }else{// shouldn't happen 
        $_SESSION['added'] = false;
    }
    $stmt->close();
    $conn->close();

    // Redirect after form submission to avoid re-submission on refresh
if ($_SESSION['added']) {
    header("Location: signusers.php?status=success");
    exit();
} else {
    header("Location: signusers.php?status=failure");
    exit();
}

}
?>


<?php

include 'establisDBconnection.php';// establish DB connection 
session_start();

// function to check the validity of the user as a string and check if there is a duplicate in data base
function checkUsername($username) {

    $len = strlen($username);

    // lenght check
    if ($len <= 6){
        return false;
    }

    return true;

}

function checkPassword($password , $username , $password_confirm){// func to check the validity of the password as a string
    // check if password confirmation match password
    if (strcmp($password , $password_confirm) != 0) {
        return "missmatch";
    }

    // SAME AS USER NAME CHECK
    if ($password == $username){
         return "matchusername";
    }
    
    $passlen = strlen($password);
    $userlen = strlen($username);

    // LENGTH CHECK
    if ($passlen < 6) {
        return "length";
    }

    // SUB-STRING CHECK
    for ($i = 0; $i <= $userlen - $passlen; $i++) {
        if (strcmp(substr($username, $i, $passlen), $password) == 0) {
            return "foundinusername";
        }
    }

    // CHECK FOR CAPITAL LETTERS 
   if (!preg_match('/[A-Z]/' , $password)){
    return "capital";
   }
   // check for spcial characters
   if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
    return "special";
}


   return "";


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
    


    $role = $_SESSION['signup']; // Get the role (admin, manager, or member)
    

    $queryParams = http_build_query([
        "$role" => 1,
        "firstname" => $firstname,
        "lastname" => $lastname,
        "age" => $age,
        "assignManager" => $manager ?? null
    ]);

    if (!checkUsername($username)) {
        if ($_SESSION['signup'] == "admin"){
        header("Location: signusers.php?$queryParams&admin=1&error=1");
        }elseif ($_SESSION['signup'] == "member"){
        header("Location: signusers.php?$queryParams&member=1&error=1");
        }elseif ($_SESSION['signup'] == "manager"){
        header("Location: signusers.php?$queryParams&manager=1&error=1");
        }
        exit();
    }else if (checkUsername($username)) {// check if already in data base
        $stmt = $conn->prepare("SELECT * FROM employee WHERE username = ?");
        $stmt->bind_param("s", $username); $stmt->execute() or die("failed to execute query". $stmt->error);
        $res = $stmt->get_result();

        $stmt1 = $conn->prepare("SELECT * FROM manager WHERE username = ?");
        $stmt1->bind_param("s", $username);
        $stmt1->execute() or die("Failed to execute query".$stmt1->error);
        $res1 = $stmt1->get_result();

        $stmt2 = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt2->bind_param("s", $username); $stmt2->execute() or die("Failed to execute query".$stmt2->error);
        $res2 = $stmt2->get_result();


        if ($res->num_rows > 0 || $res1->num_rows > 0 || $res2->num_rows > 0) {// if user name exists anywhere in the data base then refuse to add
            header ("Location:signusers.php?$queryParams&userFound=1&error=1");
            $stmt->close();
            exit();
        }
        $stmt->close();
        $stmt1->close();
        $stmt2->close();
    }

    if (($str = checkPassword($password, $username, $password_confirm)) != "") {
        $passwordError = match ($str) {
            "missmatch" => "missmatch=1",
            "length" => "length=1",
            "number" => "number=1",
            "capital" => "capital=1",
            "special" => "special=1",
            "foundinusername" => "foundinusername=1",
            "matchusername" => "matchusername=1",
            default => ""
        };

    header("Location: signusers.php?$queryParams&password=1&$passwordError&error=1");
        exit();
    }
}

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


?>


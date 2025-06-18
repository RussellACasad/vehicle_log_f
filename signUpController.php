<?php 
/**
 * 
 * This is the backend for creating an account, it adds a new account to the database if all data is valid. 
 * 
 */
session_start();

include_once './User/config.php'; 

$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL); // Gets all data from the form. 
$username = filter_input(INPUT_POST, "username");
$password = filter_input(INPUT_POST, "password");
$passwordVerify = filter_input(INPUT_POST, "passwordVerify");
$firstName = filter_input(INPUT_POST, "firstName");
$lastName = filter_input(INPUT_POST, "lastName");
$date = date('Y-m-d H:i:s');

$emailInvalid = !$email; // Validates the info from the form. 
$passwordInvalid = !$password || !$passwordVerify || $password != $passwordVerify;
$firstNameInvalid = !$firstName; 
$lastNameInvalid = !$lastName; 
$usernameInvalid = !$username;

if($emailInvalid || $passwordInvalid || $firstNameInvalid || $lastNameInvalid || $usernameInvalid)
{
    echo "ERR <br />"; // Displays if data is invalid. Should not be seen unless client-side code is modified. 
    echo "Email: $emailInvalid <br />";
    echo "Pass: $passwordInvalid <br />";
    echo "fn: $firstNameInvalid <br />";
    echo "ln: $lastNameInvalid <br />";
    echo "un: $usernameInvalid <br />";
    die; 
}

$sql = "SELECT UserID FROM Users WHERE Username = :name"; // Checks to see if the username is in use already
$statement = $db -> prepare($sql);
$statement -> bindValue(":name", $username); 
$statement -> execute(); 
$usernameTest = $statement -> fetch(); 
$statement -> closeCursor(); 

if (isset($usernameTest["UserID"])) // If username is used, tell the user and refuse the new account. 
{
    $_SESSION["signupWarn"] = "Username is already in use.";
    header("Location: ./signup.php"); 
    die; 
}

$hash = password_hash($password, PASSWORD_DEFAULT); // Hashe the password

// Saves the user to the database
$query = 'INSERT INTO Users  
    (FirstName, LastName, Email, Password, CreatedDate, ModifiedDate, Username)
        VALUES (:first, :last, :email, :pass, :cre, :mod, :un)';
        
$statement = $db -> prepare($query); 
$statement -> bindValue(":first", $firstName); 
$statement -> bindValue(":last", $lastName); 
$statement -> bindValue(":email", $email); 
$statement -> bindValue(":pass", $hash); 
$statement -> bindValue(":cre", $date); 
$statement -> bindValue(":mod", $date); 
$statement -> bindValue(":un", $username); 

$statement -> execute(); 
$statement -> closeCursor(); 

header("Location: ./login.php"); // Redirects the user to the login page to log in to their account. 
die; 
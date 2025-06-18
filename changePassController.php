<?php 
/*
This is run when the user is set to change their password upon login, this is the backend
Can be called in 2 situations:
    - User resets their password
    - Manager makes an account for a user and selects the option for the user to reset their password on login. 
*/
session_start(); 
require_once './User/config.php';

$id = filter_input(INPUT_POST, "id"); // Gets the userID, and the passwords from the form
$password = filter_input(INPUT_POST, "password"); 
$passwordVerify = filter_input(INPUT_POST, "passwordValidate"); 

$passwordInvalid = !$password || !$passwordVerify || $password != $passwordVerify; // verifies the password isn't invalid

if($passwordInvalid) // outputs to the user that the paswords are invalid if they are
{
    $_SESSION["changePassWarn"] = "Passwords do not match.";
    header("Location: ./ChangePassword.php");
    die; 
}

$hash = password_hash($password, PASSWORD_DEFAULT); // Hashes the password

$sql = "UPDATE Users SET Password = :pass, ChangePassword = :change WHERE UserID = :id"; // Changes the password in the database
$statement = $db -> prepare($sql); 
$statement -> bindValue(":id", $_SESSION["user-id"]); 
$statement -> bindValue(":pass", $hash); 
$statement -> bindValue(":change", 0); 
$statement -> execute(); 
$statement -> closeCursor(); 

$_SESSION["login-valid"] = true; // Validates the login


switch ($_SESSION["user-role"]) { // Redirects to the dashboard based on role 

    case "user":
        header("Location: ./User/");
        break;
    case "admin":
        header("Location: ./Admin/");
        break;
}
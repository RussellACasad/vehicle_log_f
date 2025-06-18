<?php 

/**
 * If a user forgets their password, this function takes in their username and email, confirms it with the database, and 
 * generates a new password. 
 */

session_start(); 

$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL); 
$un = filter_input(INPUT_POST, "username"); 

if(empty($un)) { // checks username
    $_SESSION["forgotPassWarn"] = "Invalid Username <br/>";
    $usernameInvalid = true; 
}
else
{
    $usernameInvalid = false;
}

if(empty($email)) { // checks email
    $_SESSION["forgotPassWarn"] = "Invalid Email <br/>";
    $emailInvalid = true; 
}
else 
{
    $emailInvalid = false;
}

if ($usernameInvalid || $emailInvalid) // if either is invalid, that is said
{
    header("Location: ./forgotPassword.php"); 
}

include_once './User/config.php';  // get database

$sql = "SELECT UserID, LastName FROM Users WHERE Username = :username AND Email = :email"; // Selects from the database
$statement = $db->prepare($sql); 
$statement->bindValue(":email", $email); 
$statement->bindValue(":username", $un); 
$statement->execute(); 
$user = $statement->fetch(); 
$statement->closeCursor();

if (!$user) { // If user is invalid, it means the username is invalid. This data is mentioned back to the user.
    $_SESSION["forgotPassWarn"] = "No user found: $un <br/>";
    header("Location: ./forgotPassword.php");
    die;
}

$num = random_int(10, 90); // Generates a random number between 10 and 90
$pass = $user["LastName"] . $num; // Generates a password that is the user's last name + a random number
$hash = password_hash($pass, PASSWORD_DEFAULT); // hashes the password

$sql = "UPDATE Users SET Password = :pass, ChangePassword = :changePass WHERE UserID = :id"; 
$statement = $db -> prepare($sql); 
$statement -> bindValue(":pass", $hash);
$statement -> bindValue(":changePass", 1); 
$statement -> bindValue("id", $user["UserID"]); 
$statement -> execute(); 
$statement -> closeCursor(); // changes the password



$_SESSION["login-info"] = "Your password has been set to $pass.<br/>Please log in to change";
// Tells the user their new password. Would normally be sent in an email, but I feel as if that is out of the scope of this project. 
header("Location: ./login.php");
die; 

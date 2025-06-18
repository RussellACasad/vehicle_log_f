<?php
/**
 * Backend for logging in, creates the session if true, redirects back to the login page when false.
 */
session_start();

require_once './User/config.php';

$username = filter_input(INPUT_POST, "username");
$pass = filter_input(INPUT_POST, "password");

$query = "SELECT UserID, Role, Password, ChangePassword FROM Users WHERE Username = :user";
$statement = $db->prepare($query);
$statement->bindValue(":user", $username);
$statement->execute();
$user = $statement->fetch();
$statement->closeCursor();

if (!isset($user["Password"])) { // Checks if the username is valid
    $_SESSION["login-valid"] = "false";
    header("Location: ./login.php");
    die;
}

$hash = $user["Password"]; // gets thr password hash

$valid = password_verify($pass, $hash); // Compares the hashes to verify a user

if ($valid) { // If the login is valid, it creates the session, and redirects to the correct dashboard. 
    $_SESSION["login-valid"] = $user["ChangePassword"] == 1 ? false : true;
    $_SESSION["user-role"] = $user["Role"];
    $_SESSION["user-id"] = $user["UserID"];

    $date = date('Y-m-d H:i:s');

    $sql = "UPDATE Users SET LastLoginDate = :date WHERE UserID = :id";
    $statement = $db->prepare($sql);
    $statement->bindValue(":date", $date);
    $statement->bindValue(":id", $user["UserID"]);
    $statement->execute();
    $statement->closeCursor();

    if ($user["ChangePassword"] == 1) {
        header("Location: ./ChangePassword.php"); 
    } else {
        switch ($user["Role"]) {
            case "user":
                header("Location: ./User/");
                break;
            case "admin":
                header("Location: ./Admin/");
                break;
        }
    }
    die;
} else { // If login is invalid, the user is redirected to the login page where an error message will be displayed
    $_SESSION["login-valid"] = "false";
    header("Location: ./login.php");
    die;
}

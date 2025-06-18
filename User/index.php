<?php
$minRole = "user"; 
include '../Views/sessionManager.php'; // Ensure the user has the correct permissions to be on the page
include './config.php';

$action = filter_input(INPUT_GET, "action");
$selectedPage = "dash";

// Gets the user table always, as it's used in most pages

$query = 'SELECT * FROM Vehicles WHERE UserID = :id';
$statement = $db->prepare($query);
$statement -> bindValue(":id", $_SESSION["user-id"]); 
$statement->execute();
$Vehicles = $statement->fetchAll();
$statement->closeCursor();

$action = empty($action) ? "home" : $action;

include 'dashboard.php';

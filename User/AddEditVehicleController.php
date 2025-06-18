<?php
session_start(); // Start session

$id = filter_input(INPUT_POST, 'id'); // get inputs
$year = filter_input(INPUT_POST,"year", FILTER_SANITIZE_NUMBER_INT);
$brand = filter_input(INPUT_POST,"brand");
$model = filter_input(INPUT_POST,"model");
$color = filter_input(INPUT_POST,"color");
$vin = filter_input(INPUT_POST,"vin");
$licencePlate = filter_input(INPUT_POST,"plate");
$state = filter_input(INPUT_POST,"state");
$purchaseDate = filter_input(INPUT_POST,"purchaseDate");
$purchasePrice = filter_input(INPUT_POST,"price");
$purchaseMiles = filter_input(INPUT_POST,"miles");
$status = filter_input(INPUT_POST,"status");

$user = $_SESSION["user-id"]; // Uses the logged in user to get the UserID

$idInvalid = !$id; // Ensure no inputs are null
$yearInvalid = !$year;
$brandInvalid = !$brand; 
$modelInvalid = !$model; 
$colorInvalid = !$color; 
$vinInvalid = !$vin; 
$plateInvalid = !$licencePlate; 
$purchaseDateInvalid = !$purchaseDate; 
$purchasePriceInvalid = !$purchasePrice; 
$purchaseMilesInvalid = !$purchaseMiles; 
$statusInvalid = !$status;  

if(!$idInvalid && !$statusInvalid && ($yearInvalid || $brandInvalid || $modelInvalid || $colorInvalid || $vinInvalid || $plateInvalid || $purchaseDateInvalid || $purchasePriceInvalid || $purchaseMilesInvalid)) {
    require_once "./config.php"; // If only the ID and Status are valid, edits only the status
    $sql = "UPDATE Vehicles SET Status = :1 WHERE VehicleID = :id"; 
    
    $Statement = $db -> prepare($sql);
    $Statement -> bindValue(":1", $status);
    $Statement -> bindValue(":id", $id); 
    $Statement -> execute(); 
    $Statement -> closeCursor(); 
    
    $_SESSION["message"] = "editStatus";
    header("Location: ./?action=info&id=$id");
    die; 
}
else if ($statusInvalid) { // This should never happen unless the user modifies client code
    header("Location: ./?action=info&id=$id");
    die(); 
}
else // If adding or editing the vehicle
{
    require_once "./config.php"; 
    if($id) // is edit
    {
        $sql = "UPDATE Vehicles SET
                    UserID = :1,
                    Brand = :2,
                    Model = :3,
                    Year =  :4,
                    PurchaseDate = :5,
                    Color = :6,
                    VIN = :7,
                    LicensePlate = :8,
                    State = :9,
                    PurchasePrice = :10,
                    PurchaseMileage = :11
                WHERE VehicleID = :id"; 
                
        $Statement -> bindValue(":id", $id); // Prepares the query
    }
    else // is add
    {
        $sql = 'INSERT INTO Vehicles (UserID, Brand, Model, Year, PurchaseDate, Color, VIN, LicensePlate, State, PurchasePrice, PurchaseMileage) 
                    VALUES
                        (:1, :2, :3, :4, :5, :6, :7, :8, :9, :10, :11)'; 

        $Statement = $db -> prepare($sql); // Prepares the query
    }

    // Binds the data for either statement
    $Statement -> bindValue(":1", $user); 
    $Statement -> bindValue(":2", $brand); 
    $Statement -> bindValue(":3", $model); 
    $Statement -> bindValue(":4", $year); 
    $Statement -> bindValue(":5", $purchaseDate); 
    $Statement -> bindValue(":6", $color); 
    $Statement -> bindValue(":7", $vin); 
    $Statement -> bindValue(":8", $licencePlate); 
    $Statement -> bindValue(":9", $state); 
    $Statement -> bindValue(":10", $purchasePrice); 
    $Statement -> bindValue(":11", $purchaseMiles); 
    
    $Statement -> execute(); 
    $Statement -> closeCursor(); 

    if ($id) // Redirectts the user to the edited vehicle if edit
    {
        $_SESSION["message"] = "edit";
        header("Location: ./?action=info&id=$id");
    }
    else // Redirects the user to the vehicle list if add
    {
        $_SESSION["message"] = "add";
        header('Location: ./?action=vehicles');
    } 
    die(); 
}
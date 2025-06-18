<?php
session_start(); // Starts the session

$id = filter_input(INPUT_POST, 'id');  // Gets the data from the forum
$vehicleID = filter_input(INPUT_POST, 'vehicleID');
$source = filter_input(INPUT_POST, 'source');
$gallons = filter_input(INPUT_POST, 'gallons');
$cost = filter_input(INPUT_POST, 'cost');
$mileage = filter_input(INPUT_POST, 'miles');
$date = filter_input(INPUT_POST, 'date');
$fuelType = filter_input(INPUT_POST, 'type');

$sourceInvalid = !$source; // Validates the data from the form, ensuring it's not empty, or invalid. 
$vehicleIDInvalid = !$vehicleID;
$gallonsInvalid = !$gallons || !is_numeric($gallons);
$costInvalid = !$cost || !is_numeric($cost);
$mileageInvalid = !$mileage || !is_numeric($mileage);
$dateInvalid = !$date;
$fuelTypeInvalid = !$fuelType;

if ($sourceInvalid || $vehicleIDInvalid || $gallonsInvalid || $costInvalid || $mileageInvalid || $dateInvalid || $fuelTypeInvalid) {
    echo "err <br />"; // Displays an error page if data is invalid. THIS SHOULD NEVER DISPLAY DUE TO HTML VALIDATION.
    echo "Source: $sourceInvalid <br />"; // THE ONLY WAY THIS WOULD DISPLAY IS IF A USER IS EDITING THE CLIENT CODE
    echo "VehID: $vehicleIDInvalid <br />";
    echo "Gallons: $gallonsInvalid <br />";
    echo "Cost: $costInvalid <br />";
    echo "Mileage: $mileageInvalid <br />";
    echo "Date: $dateInvalid <br />";
    echo "Type: $fuelTypeInvalid <br />";
    exit();
}

require_once 'config.php'; // Gets the database

if ($id) { // If the ID exists, we're editing the fuel log, not adding. 
    $query = 'UPDATE Fuel
              SET Source = :source,
                  Gallons = :gallons,
                  TotalCost = :cost,
                  Mileage = :mileage,
                  Date = :date,
                  FuelType = :fuelType
              WHERE FuelID = :id';
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id); // Binds edit-exclusive data
} else { // Add fuel log
    $query = 'INSERT INTO Fuel
              (VehicleID, Source, Gallons, TotalCost, Mileage, Date, FuelType)
              VALUES
              (:vehicleID, :source, :gallons, :cost, :mileage, :date, :fuelType)';
    $statement = $db->prepare($query);
    $statement->bindValue(':vehicleID', $vehicleID); // Binds add-exclusive data to the query
}

$statement->bindValue(':source', $source); // Binds the rest of the data to the query
$statement->bindValue(':gallons', $gallons);
$statement->bindValue(':cost', $cost);
$statement->bindValue(':mileage', $mileage);
$statement->bindValue(':date', $date);
$statement->bindValue(':fuelType', $fuelType);
$statement->execute(); // Executes the add or edit query
$statement->closeCursor(); 

if ($id)// Redirects the user to the fuel log that was edited
{
    $_SESSION["message"] = "editGas";
    header("Location: ./?action=gas&id=$id");
}
else // Redirects the user to the vehicle that had the fuel log added to it
{
    $_SESSION["message"] = "addGas";
    header("Location: ./?action=info&id=$vehicleID");
} 
die;

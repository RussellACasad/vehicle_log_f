<?php
session_start(); 

$id = filter_input(INPUT_POST, 'id'); // Gets all data from the form
$vehicleID = filter_input(INPUT_POST, 'vehicleID'); 
$vendor = filter_input(INPUT_POST, 'vendor');
$vendorAddress = filter_input(INPUT_POST, 'vendorAddress');
$description = filter_input(INPUT_POST, 'desc');
$cost = filter_input(INPUT_POST, "cost"); 
$date = filter_input(INPUT_POST, "dateStart"); 
$dateAfter = filter_input(INPUT_POST, "dateFin");
$milesBefore = filter_input(INPUT_POST, "milesBefore"); 
$milesAfter = filter_input(INPUT_POST, "milesAfter"); 

$vendorInvalid = !$vendor; // Basic validation of the form data
$vendorAddressInvalid = !$vendorAddress;
$descriptionInvalid = !$description;
$costInvalid = !$cost || !is_numeric($cost);
$dateInvalid = !$date;
$milesBeforeInvalid = !$milesBefore || !is_numeric($milesBefore);

if ($vendorInvalid || $vendorAddressInvalid || $descriptionInvalid || $costInvalid || $dateInvalid || $milesBeforeInvalid) {
    echo 'ERR <br />'; // Displays an error if data is invalid, SHOULD NOT APPEAR UNLESS USER MODIFIES CLIENT CODE
    echo "Vendor: $vendorInvalid <br/>";
    echo "Address: $vendorAddressInvalid <br/>";
    echo "Desc: $descriptionInvalid <br/>";
    echo "Cost: $dateInvalid <br/>";
    echo "Date: $dateInvalid <br/>";
    echo "Miles: $milesBeforeInvalid <br/>";
    exit();
}

require_once 'config.php'; // Gets the database

if ($id) { // id ID is given, it's editing

    // Query has optional data, only adds the data to the query if supplied
    $query = 'UPDATE Maintenance
              SET Vendor = :vendor,
                  VendorAddress = :vendorAddress,
                  Description = :description,
                  TotalCost = :cost,
                  Date = :date,
                  MileageBefore = :milesBefore' . 
                  ($milesAfter ? ', MileageAfter = :milesAfter' : "") . 
                  ($dateAfter ? ', Completed = :dateAfter' : "") . 
                  ' WHERE MaintenanceID = :id';
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id); // Bind edit-exclusive data
} else { // Adding

    // Query has optional data, only adds the data to the query if supplied
    $query = "INSERT INTO Maintenance 
              (VehicleID, Vendor, VendorAddress, Description, TotalCost, Date, " . ($dateAfter ? "Completed, " : "") . "MileageBefore" . ($milesAfter ? ", MileageAfter" : "") . ")" .
              "VALUES
              (:vehicleID, :vendor, :vendorAddress, :description, :cost, :date, " . ($dateAfter ? ":dateAfter, " : "") . ":milesBefore" . ($milesAfter ? ", :milesAfter" : "") . ")";
    $statement = $db->prepare($query); 
    $statement->bindValue(':vehicleID', $vehicleID); // Bind add-exclusive data
}

$statement->bindValue(':vendor', $vendor); // Bind the rest of the data
$statement->bindValue(':vendorAddress', $vendorAddress);
$statement->bindValue(':description', $description);
$statement->bindValue(':cost', $cost);
$statement->bindValue(':date', $date);
$statement->bindValue(':milesBefore', $milesBefore);
if ($milesAfter) { // If these optional parameters are added, bind their values
    $statement->bindValue(':milesAfter', $milesAfter);
}
if ($dateAfter) { // If these optional parameters are added, bind their values
    $statement->bindValue(':dateAfter', $dateAfter);
}
$statement->execute();
$statement->closeCursor();



if ($id) // Redirect the user to the edited maintenance log if edited, otherwise, the vehicle they added the log to.
{
    $_SESSION["message"] = "editMaintenence";
    header("Location: ./?action=maint&id=$id");
}
else
{
    $_SESSION["message"] = "addMaintenence";
    header("Location: ./?action=info&id=$vehicleID");
} 

die;
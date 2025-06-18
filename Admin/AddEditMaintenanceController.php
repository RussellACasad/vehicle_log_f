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

$sched = filter_input(INPUT_GET, "schedule"); // Checks if this is scheduled

$vendorInvalid = !$vendor;// Basic validation of the form data
$vendorAddressInvalid = !$vendorAddress;
$descriptionInvalid = !$description;
$costInvalid = !$cost || !is_numeric($cost);
$dateInvalid = !$date;
if (!isset($sched)) { // Validates data if not scheduling mant
    $milesBeforeInvalid = !$milesBefore || !is_numeric($milesBefore);
}
else if ($sched == 1) // Sets variables to false if is scheduling
{
    $milesBeforeInvalid = false; 
    $milesAfterInvalid = false; 
    $dateAfterInvalid = false;
}
else { // Fallback if the URL is modified by the end user
    $milesBeforeInvalid = true; 
    $milesAfterInvalid = true; 
    $dateAfterInvalid = true;
}

if ($vendorInvalid || $vendorAddressInvalid || $descriptionInvalid || $costInvalid || $dateInvalid || $milesBeforeInvalid || $milesAfterInvalid || $dateAfterInvalid) {
    echo 'ERR <br />'; // This is fallback validation for if the user modifies the HTML
    echo "Vendor: $vendorInvalid <br/>";
    echo "Address: $vendorAddressInvalid <br/>";
    echo "Desc: $descriptionInvalid <br/>";
    echo "Cost: $dateInvalid <br/>";
    echo "Date: $dateInvalid <br/>";
    echo "Miles: $milesBeforeInvalid <br/>";
    exit();
}

require_once 'config.php'; // Gets the database

if (isset($sched) && $sched == 1) // Queries for scheduling and binds the data specifc to scheduling
{
    $query = "INSERT INTO Maintenance (VehicleID, Vendor, VendorAddress, Description, TotalCost, Date, Started)
                VALUES (:vehicleID, :vendor, :vendorAddress, :description, :cost, :date, :start)";
    $statement = $db->prepare($query);
    $statement->bindValue(':vehicleID', $vehicleID);
    $statement->bindValue(':start', 0);
}
else if ($id) { // Queries for editing, and binds data specific for editing
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
    $statement->bindValue(':id', $id);
    $statement->bindValue(':milesBefore', $milesBefore);
} else { // Queries for Adding, and binds the data specific to adding 
    $query = "INSERT INTO Maintenance
              (VehicleID, Vendor, VendorAddress, Description, TotalCost, Date, " . ($dateAfter ? "Completed, " : "") . "MileageBefore" . ($milesAfter ? ", MileageAfter" : "") . ")" .
        "VALUES
              (:vehicleID, :vendor, :vendorAddress, :description, :cost, :date, " . ($dateAfter ? ":dateAfter, " : "") . ":milesBefore" . ($milesAfter ? ", :milesAfter" : "") . ")";
    $statement = $db->prepare($query);
    $statement->bindValue(':vehicleID', $vehicleID);
    $statement->bindValue(':milesBefore', $milesBefore);
}
// Binds variables used in all queries
$statement->bindValue(':vendor', $vendor);
$statement->bindValue(':vendorAddress', $vendorAddress);
$statement->bindValue(':description', $description);
$statement->bindValue(':cost', $cost);
$statement->bindValue(':date', $date);
if ($milesAfter && !isset($sched)) {
    $statement->bindValue(':milesAfter', $milesAfter);
}
if ($dateAfter && !isset($sched)) {
    $statement->bindValue(':dateAfter', $dateAfter);
}
$statement->execute();
$statement->closeCursor();


if (isset($sched)) // Redirects to the correct location with the correct message for the notification
{
    $_SESSION["message"] = "schedMaintenence";
    header("Location: ./?action=info&id=$vehicleID"); // -> vehicle the maintenance was scheduled for
}
if ($id) {
    $_SESSION["message"] = "editMaintenence";
    header("Location: ./?action=maint&id=$id"); // -> maintenance log that was edited
} else {
    $_SESSION["message"] = "addMaintenence";
    header("Location: ./?action=info&id=$vehicleID"); // -> vehicle that the maintenance was added to 
}
die;

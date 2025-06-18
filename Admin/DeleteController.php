<?php
/*

This handles the deletion of all table data. Only accessible to ADMINs.

*/


$action ??= filter_input(INPUT_GET, "action"); // Gets the action and an ID. Action decides what table to delete from, ID is what to delete.
$id ??= filter_input(INPUT_GET, "id");

include_once './config.php';

switch ($action) {
    case "fuel": // Deletes a fuel log. 
        $sql = "SELECT VehicleID FROM Fuel WHERE FuelID = :id"; // Gets the vehicle that's being deleted from for redirect. 
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();
        $vehicle = $statement -> fetch(); 
        $statement->closeCursor();

        $sql = "DELETE FROM Fuel WHERE FuelID = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();
        $statement->closeCursor();

        header("Location: ./?action=info&id=" . $vehicle["VehicleID"]);
        break;

    case "maint": // Deletes a maintenance log. 
        $sql = "SELECT VehicleID FROM Maintenance WHERE MaintenanceID = :id"; // Gets the vehicle that's being deleted from for redirect. 
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();
        $vehicle = $statement -> fetch(); 
        $statement->closeCursor();

        $sql = "DELETE FROM Maintenance WHERE MaintenanceID = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();
        $statement->closeCursor();

        header("Location: ./?action=info&id=" . $vehicle["VehicleID"]);
        break;

    case "user": // Deletes a user, alongside their vehicles, maintenance, and fuel. Ensures no orphans. 
        $sql = "SELECT VehicleID FROM Vehicles WHERE UserID = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();
        $vehicles = $statement->fetchAll();
        $statement->closeCursor();

        foreach ($vehicles as $vehicle) {
            $tables = ['Maintenance', 'Fuel', 'Vehicles'];
            foreach ($tables as $table) {
                $sql = "DELETE FROM $table WHERE VehicleID = :id";
                $statement = $db->prepare($sql);
                $statement->bindValue(":id", $vehicle["VehicleID"]);
                $statement->execute();
                $statement->closeCursor();
            }
        }

        $sql = "DELETE FROM Users WHERE UserID = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();
        $vehicles = $statement->fetchAll();
        $statement->closeCursor();

        header("Location: ./?action=users");
        break;

    case "vehicle": // Deletes a vehicle, it's maintenance, and it's fuel. Ensures no orphans. 
        $tables = ['Maintenance', 'Fuel', 'Vehicles'];
        foreach ($tables as $table) {
            $sql = "DELETE FROM $table WHERE VehicleID = :id";
            $statement = $db->prepare($sql);
            $statement->bindValue(":id", $id);
            $statement->execute();
            $statement->closeCursor();
        }

        header("Location: ./?action=vehicles");
        break;

}

die;

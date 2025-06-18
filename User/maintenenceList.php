<?php
/*

This page gives a list of all the user's maintenance on all cars

*/

// Gets all maintenance from the user's car
$sql = "SELECT * 
        FROM Maintenance, Vehicles 
        WHERE Maintenance.VehicleID = Vehicles.VehicleID AND Vehicles.UserID = :id
        ORDER BY Maintenance.Date DESC";
$statement = $db->prepare($sql);
$statement->bindValue(":id", $_SESSION["user-id"]);
$statement->execute();
$MaintLogs = $statement->fetchAll();
$statement->closeCursor();

?>

<div class="table"> <!-- Displays all maintenance in a table -->
    <div class="info-head">
        <h1>Maintenence Log</h1>
        <p>Add Maintenence through a Vehicle</p>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 15%;" class="text-left">Vehicle</th>
                <th style="width: 11%;" class="text-left">Licence Plate</th>
                <th style="width: 13%;" class="text-left">Vendor</th>
                <th style="width: 25%;" class="text-left">Description</th>
                <th style="width: 10%;" class="text-center">Cost</th>
                <th style="width: 13%;" class="text-center">Date</th>
                <th style="width: 5%;" class="text-center">Status</th>
                <th style="width: 10%;"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($MaintLogs) > 0): ?>
                <?php foreach ($MaintLogs as $MaintLog): ?>
                    <tr class="lined">
                        <td class="one-line text-left"><?= $MaintLog["Year"] . " " . $MaintLog["Brand"] . " " . $MaintLog["Model"] ?></td>
                        <td class="one-line text-left"><?= $MaintLog["LicensePlate"] ?></td>
                        <td class="one-line text-left"><?= $MaintLog["Vendor"] ?></td>
                        <td class="one-line text-left"><?= $MaintLog["Description"] ?></td>
                        <td class="one-line text-center"><?= $MaintLog["TotalCost"] ?></td>
                        <td class="one-line text-center"><?= Date("n/d/Y", strtotime($MaintLog["Date"])) ?></td>
                        <td class="one-line text-center">
                            <p class="icon text-center"><?= $MaintLog["Started"] == 0 ? "&#xE7B4" : (isset($MaintLog["Completed"]) ? "&#xE182" : "&#xE80E") ?></p>
                        </td>
                        <td class="one-line text-center"><a href="./User/?action=maint&return=lst&id=<?= $MaintLog["MaintenanceID"] ?>">View</a></td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr class="lined">
                    <td colspan="8" style="text-align: center;">No Maintenance yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php

/*

Displays all maintenance data for a given report

*/

include './config.php';

$maintID = filter_input(INPUT_GET, "id");

$query = "SELECT * FROM Maintenance, Vehicles WHERE Vehicles.VehicleID = Maintenance.VehicleID AND MaintenanceID = :id AND Vehicles.UserID = :uid";
$statement = $db->prepare($query);
$statement->bindValue(":id", $maintID);
$statement->bindValue(":uid", $_SESSION["user-id"]);
$statement->execute();
$maintLog = $statement->fetch();
$statement->closeCursor();

if(!isset($maintLog["MaintenanceID"]))
{
    echo "<script>window.location.href = './User/?action=home';</script>";
    exit;
}
?>


<!-- Edit Maintenance Modal -->
<div class="modal" id="edit-maint">
    <div class="modal-content">
        <h2>Edit Maintenance Record</h2>
        <form method="post" action="./User/AddEditMaintenanceController.php">
            <div class="maint form">
                <div style="grid-area: vendor;">
                    <label for="vendor">Vendor</label>
                    <input required type="text" id="vendor" name="vendor" value="<?= $maintLog["Vendor"] ?>" onblur="validate('vendor')">
                </div>
                <div style="grid-area: vendorAddress;">
                    <label for="vendorAddress">Vendor Address</label>
                    <input required type="text" id="vendorAddress" name="vendorAddress" value="<?= $maintLog["VendorAddress"] ?>" onblur="validate('vendorAddress')">
                </div>
                <div style="grid-area: desc;">
                    <label for="desc">Description</label>
                    <input required type="text" id="desc" name="desc" value="<?= $maintLog["Description"] ?>" onblur="validate('desc')">
                </div>
                <div style="grid-area: cost;">
                    <label for="cost">Total Cost</label>
                    <input required max="99999999.99" min="0.00" type="number" step="0.01" id="cost" name="cost" value="<?= $maintLog["TotalCost"] ?>" onblur="validate('cost', 0, 99999999.99)">
                </div>
                <div style="grid-area: milesBefore;">
                    <label for="milesBefore">Mileage Before</label>
                    <input required max="99999999.99" min="0.00" type="number" step="0.01" id="milesBefore" name="milesBefore" value="<?= $maintLog["MileageBefore"] ?>" onblur="validate('milesBefore', 0, 99999999.99)">
                </div>
                <div style="grid-area: milesAfter;">
                    <label for="milesAfter">Mileage After</label>
                    <input type="number" max="99999999.99" min="0.00" id="milesAfter" step="0.01" name="milesAfter" value="<?= $maintLog["MileageAfter"] ?>">
                </div>
                <div style="grid-area: dateStart;">
                    <label for="vendor">Date Started</label>
                    <input required type="date" id="dateStart" name="dateStart" value="<?= date_format(date_create($maintLog["Date"]), "Y-m-d") ?>">
                </div>
                <div style="grid-area: dateFin;">
                    <label for="dateFin">Date Completed</label>
                    <input type="date" id="dateFin" name="dateFin" value="<?= isset($maintLog["Completed"]) ? date_format(date_create($maintLog["Completed"]), "Y-m-d") : "" ?>">
                </div>
                <div style="grid-area: button;" class="buttons">
                    <input required type="hidden" name="vehicleID" value="<?= $maintLog["VehicleID"] ?>">
                    <input required type="hidden" name="id" value="<?= $maintLog["MaintenanceID"] ?>">
                    <button type="submit">Edit Maintenance Record</button>
                    <button type="button" id="close-maint">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<body>
    
    <div class="p-10">
        <div class="info-head">
            <a href="./User/?action=<?= isset($return) ? "maintenence" : "info&id=" . $maintLog["VehicleID"] ?>" class="icon button">&#xE138</a>
            <h1>Maintenance Record</h1>
            <div class="car-buttons">
                <button id="open-maint">Edit</button>
            </div>
        </div>
        <div class="body gas log">
            <div class="card p-20">
                <table>
                    <tbody>
                        <tr class="lined">
                            <th class="text-left">Vendor</th>
                            <td class="text-left"><?= $maintLog["Vendor"] ?></td>
                        </tr>
                        <tr class="lined">
                            <th class="text-left">Vendor Address</th>
                            <td class="text-left"><?= $maintLog["VendorAddress"] ?></td>
                        </tr>
                        <tr class="lined">
                            <th class="text-left">Description</th>
                            <td class="text-left"><?= $maintLog["Description"] ?></td>
                        </tr>
                        <tr class="lined">
                            <th class="text-left">Cost</th>
                            <td class="text-left"><?= $maintLog["TotalCost"] ?></td>
                        </tr>
                        <tr class="lined">
                            <th class="text-left">Start Date</th>
                            <td class="text-left"><?= Date("n/d/Y", strtotime($maintLog["Date"])) ?></td>
                        </tr>
                        <tr class="lined">
                            <th class="text-left">Completed Date</th>
                            <td class="text-left"><?= isset($maintLog["Completed"]) ? Date("n/d/Y", strtotime($maintLog["Completed"])) : "N/A" ?></td>
                        </tr>
                        <tr class="lined">
                            <th class="text-left">Miles Before</th>
                            <td class="text-left"><?= $maintLog["MileageBefore"] ?? "N/A" ?></td>
                        </tr>
                        <tr class="lined">
                            <th class="text-left">Miles After</th>
                            <td class="text-left"><?= $maintLog["MileageAfter"] ?? "N/A" ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="./scripts/maintReport.js"></script>
    <script src="./scripts/validate.js"></script>
</body>
<?php

include './config.php';

$fuelID = filter_input(INPUT_GET, "id");

$query = "SELECT * FROM Fuel WHERE FuelID = :id"; // Selects the selected fuel log from the database
$statement = $db->prepare($query);
$statement->bindValue(":id", $fuelID);
$statement->execute();
$fuelLog = $statement->fetch();
$statement->closeCursor();
?>

<!-- Edit Gas Modal, shown via javascript -->
<div class="modal" id="edit-gas">
    <div class="modal-content">
        <h2>Edit Fuel Record</h2>
        <form action="./Admin/AddEditFuelController.php" method="post">
            <div class="fuel form">
                <div style="grid-area: source;">
                    <label for="source">Source</label>
                    <input required type="text" id="source" name="source" value="<?= $fuelLog["Source"] ?>" onblur="validate('source')">
                </div>
                <div style="grid-area: gallons;">
                    <label for="gallons">Gallons</label>
                    <input required max="99999999.99" min="0.00" type="number" step="0.01" id="gallons" name="gallons" value="<?= $fuelLog["Gallons"] ?>" onblur="validate('gallons', 0, 99999999.99)">
                </div>
                <div style="grid-area: cost;">
                    <label for="cost">Total Cost</label>
                    <input required max="99999999.99" min="0.00" type="number" step="0.01" id="cost" name="cost" value="<?= $fuelLog["TotalCost"] ?>" onblur="validate('cost', 0, 99999999.99)">
                </div>
                <div style="grid-area: miles;">
                    <label for="miles">Miles</label>
                    <input required max="99999999.99" min="0.00" type="number" step="0.01" id="miles" name="miles" value="<?= $fuelLog["Mileage"] ?>" onblur="validate('miles', 0, 99999999.99)">
                </div>
                <div style="grid-area: date;">
                    <label for="purchaseDate">Purchase Date</label>
                    <input required type="date" id="date" name="date" value="<?= date_format(date_create($fuelLog["Date"]), "Y-m-d") ?>">
                </div>
                <div style="grid-area: type;">
                    <label for="type">Fuel Type</label>
                    <select name="type" id="type">
                        <option <?= $fuelLog["FuelType"] == "unleaded" ? "selected" : "" ?> value="unleaded">Unleaded</option>
                        <option <?= $fuelLog["FuelType"] == "midgrade" ? "selected" : "" ?> value="midgrade">Unleaded Midgrade</option>
                        <option <?= $fuelLog["FuelType"] == "premium" ? "selected" : "" ?> value="premium">Unleaded Premium</option>
                        <option <?= $fuelLog["FuelType"] == "diesel" ? "selected" : "" ?> value="diesel">Diesel</option>
                    </select>
                </div>
                <div style="grid-area: button;" class="buttons">
                    <input type="hidden" name="vehicleID" value="<?= $fuelLog["VehicleID"] ?>">
                    <input type="hidden" name="id" value="<?= $fuelLog["FuelID"] ?>">
                    <button>Edit Fuel Record</button>
                    <button type="button" id="close-gas">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal, Confirms with the user before deletion. -->
<div class="modal" id="delete-modal">
    <div class="modal-content">
        <div class="modal-message">
            <div class="header">
                <h2>Delete Fuel Record?</h2>
            </div>
            <div class="prompt">
                <p>Are you sure you want to delete this fuel record?</p>
            </div>
            <div class="buttons">
                <form action="./Admin/DeleteController.php" method="get">
                    <input type="hidden" value="fuel" name="action">
                    <input type="hidden" value="<?= $fuelLog["FuelID"] ?>" name="id">
                    <button id="close-delete" type="button">Back</button>
                    <button type="submit">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<body>
    <div class="p-10">
        <div class="info-head">
            <a href="./User/?action=info&id=<?= $fuelLog["VehicleID"] ?>" class="icon button">&#xE138</a>
            <h1>Fuel Record</h1> <!-- Title -->
            <div class="car-buttons">
                <button id="open-delete">Delete</button>
                <button id="open-gas">Edit</button>
            </div>
        </div>
        <div class="body gas log">
            <div class="card p-20"> <!-- table giving info about the fuel log pulled up -->
                <table>
                    <tbody>
                        <tr class="lined">
                            <th class="text-left">Source</th>
                            <td class="text-left"><?= $fuelLog["Source"] ?></td>
                        </tr>
                        <tr class="lined">
                            <th class="text-left">Gallons</th>
                            <td class="text-left"><?= $fuelLog["Gallons"] ?></td>
                        </tr>
                        <tr class="lined">
                            <th class="text-left">Cost</th>
                            <td class="text-left"><?= $fuelLog["TotalCost"] ?></td>
                        </tr>
                        <tr class="lined">
                            <th class="text-left">Miles</th>
                            <td class="text-left"><?= $fuelLog["Mileage"] ?></td>
                        </tr>
                        <tr class="lined">
                            <th class="text-left">Purchase Date</th>
                            <td class="text-left"><?= Date("n/d/Y", strtotime($fuelLog["Date"])) ?></td>
                        </tr>
                        <tr class="lined">
                            <th class="text-left">Fuel Type</th>
                            <td class="text-left"><?= $fuelLog["FuelType"] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="./scripts/gasReport.js"></script>
    <script src="./scripts/validate.js"></script>
</body>
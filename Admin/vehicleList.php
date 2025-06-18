<!-- 

This is the list of all the vehicles in the 

-->

<?php 
$sql = "SELECT * FROM Users"; 
$statement = $db -> prepare($sql); 
$statement -> execute(); 
$Users = $statement -> fetchAll(); 
$statement -> closeCursor(); 
?>

<!-- ADD Modal VALID JS -->
<div class="modal" id="add-vehicle">
    <div class="modal-content">
        <h2>Add Vehicle</h2>
        <form method="post" action="./Admin/AddEditVehicleController.php">
            <div class="vehicle-admin form">
                <div style="grid-area: year;">
                    <label for="year">Year</label>
                    <input required required max="9999" min="0.00" maxlength="4" minlength="0" type="number" id="year" name="year" onblur="validate('year', 0, 9999)">
                </div>
                <div style="grid-area: brand;">
                    <label for="brand">Brand</label>
                    <input required type="text" id="brand" name="brand" onblur="validate('brand')">
                </div>
                <div style="grid-area: model;">
                    <label for="model">Model</label>
                    <input required type="text" id="model" name="model" onblur="validate('model')">
                </div>
                <div style="grid-area: date;">
                    <label for="purchaseDate">Purchase Date</label>
                    <input required type="date" id="purchaseDate" name="purchaseDate">
                </div>
                <div style="grid-area: color;">
                    <label for="color">Color</label>
                    <input required type="text" id="color" name="color" onblur="validate('color')">
                </div>
                <div style="grid-area: vin;">
                    <label for="vin">VIN</label>
                    <input required type="text" id="vin" name="vin" onblur="validate('vin')">
                </div>
                <div style="grid-area: plate;">
                    <label for="plate">Licence Plate</label>
                    <input required type="text" id="plate" name="plate" onblur="validate('plate')">
                </div>
                <div style="grid-area: state;">
                    <label for="state">State</label>
                    <select name="state" id="state">
                        <?php foreach ($States as $state): ?>
                            <option value="<?= $state ?>"><?= $state ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="grid-area: price;">
                    <label for="price">Purchase Price</label>
                    <input required max="99999999.99" min="0.00" type="number" step="0.01" id="price" name="price" onblur="validate('price', 0, 99999999.99)">
                </div>
                <div style="grid-area: miles;">
                    <label for="miles">Purchase Mileage</label>
                    <input max="99999999.99" min="0.00" required type="number" step="0.01" id="miles" name="miles" onblur="validate('miles', 0, 99999999.99)">
                </div>
                <div style="grid-area: userid">
                <label for="userid">Owner</label>
                    <select name="userid" id="userid">
                        <?php foreach($Users as $User): ?>
                            <option value="<?= $User["UserID"]?>"><?= $User["FirstName"] . " " . $User["LastName"]?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="grid-area: button;" class="buttons">
                    <input type="hidden" name="status" value="ready">
                    <button>Submit</button>
                    <button type="button" id="close-add-vehicle">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table that lists all the vehicles a user has -->
<div class="table">
    <div class="info-head">
        <h1>Vehicles</h1>
        <button id="open-add-vehicle">New Vehicle</button>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Name</th>
                <th style="width: 20%;">Color</th>
                <th style="width: 20%;">Plate Number</th>
                <th style="width: 20%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($Vehicles) > 0): ?>
                <?php foreach ($Vehicles as $Vehicle): ?>
                    <tr class="lined">
                        <td><a href="./Admin/?action=info&id=<?= $Vehicle["VehicleID"] ?>"><?= $Vehicle["Year"] . " " . $Vehicle["Brand"] . " " . $Vehicle["Model"] ?></a></td>
                        <td><?= $Vehicle["Color"] ?></td>
                        <td><?= $Vehicle["LicensePlate"] ?></td>
                        <td class="text-left">
                            <?php switch ($Vehicle["Status"]) {
                                case "ready":
                                    echo 'In Use';
                                    break;
                                case "inactive":
                                    echo 'Inactive';
                                    break;
                                case "shop":
                                    echo 'In Shop';
                                    break;
                                case "oos":
                                    echo 'Out of Service';
                                    break;
                            } ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr class="lined">
                    <td colspan="4" style="text-align: center;">No vehicles yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <script src="./scripts/validate.js"></script>
</div>
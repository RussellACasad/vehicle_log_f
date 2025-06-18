<?php
$name = "Dashboard";
$selectedPage = "dash";
include './config.php';

$id ??= filter_input(INPUT_GET, "id");

$query = 'SELECT * FROM Vehicles WHERE VehicleID = :id';
$statement = $db->prepare($query);
$statement->bindValue(':id', $id);
$statement->execute();
$Vehicle = $statement->fetch();
$statement->closeCursor();

if (!isset($Vehicle["UserID"]) || $Vehicle["UserID"] != $_SESSION["user-id"]) { // Redirect user if trying to see vehicle they do not own. 
    echo '<script type="text/javascript"> window.location = "./User/?action=vehicles"</script>';
    die; 
}

$userQuery = 'SELECT * FROM Users WHERE UserID = :id';
$userStatement = $db->prepare($userQuery);
$userStatement->bindValue(":id", $Vehicle["UserID"]);
$userStatement->execute();
$User = $userStatement->fetch();
$userStatement->closeCursor();

$fuelQuery = "SELECT * FROM Fuel WHERE VehicleID = :id ORDER BY Date DESC";
$fuelStatement = $db->prepare($fuelQuery);
$fuelStatement->bindValue(':id', $id);
$fuelStatement->execute();
$fuel = $fuelStatement->fetchAll();
$fuelStatement->closeCursor();

$maintenanceQuery = "SELECT * FROM Maintenance WHERE VehicleID = :id ORDER BY Date DESC";
$maintenanceStatement = $db->prepare($maintenanceQuery);
$maintenanceStatement->bindValue(':id', $id);
$maintenanceStatement->execute();
$maintenance = $maintenanceStatement->fetchAll();
$maintenanceStatement->closeCursor();

$States = ['AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'];
?>

<body>
    <!-- EDIT Vehicle Modal, allows for the editing of vehicles -->
    <div class="modal" id="edit-vehicle">
        <div class="modal-content">
            <h2>Edit Vehicle</h2>
            <form method="post" action="./User/AddEditVehicleController.php">
                <div class="vehicle form">
                    <div style="grid-area: year;">
                        <label for="year">Year</label>
                        <input minlength="4" maxlength="4" required type="text" id="year" name="year" value="<?= $Vehicle["Year"] ?>" onblur="validate('year', 0, 9999)">
                    </div>
                    <div style="grid-area: brand;">
                        <label for="brand">Brand</label>
                        <input maxlength="50" required type="text" id="brand" name="brand" value="<?= $Vehicle["Brand"] ?> " onblur="validate('brand')">
                    </div>
                    <div style="grid-area: model;">
                        <label for="model">Model</label>
                        <input maxlength="50" required type="text" id="model" name="model" value="<?= $Vehicle["Model"] ?> " onblur="validate('model')">
                    </div>
                    <div style="grid-area: state;">
                        <label for="state">State</label>
                        <select name="state" id="state">
                            <?php foreach ($States as $state): ?>
                                <option value="<?= $state ?>" <?= $state == $Vehicle["State"] ? "selected" : "" ?>><?= $state ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="grid-area: date;">
                        <label for="purchaseDate">Purchase Date</label>
                        <input required type="date" id="purchaseDate" name="purchaseDate" value="<?= date_format(date_create($Vehicle["PurchaseDate"]), "Y-m-d") ?>">
                    </div>
                    <div style="grid-area: miles;">
                        <label for="miles">Purchase Mileage</label>
                        <input step="0.01" max="99999999.99" min="0.00" required type="number" id="miles" name="miles" value="<?= $Vehicle["PurchaseMileage"] ?>" onblur="validate('miles', 0, 99999999.99)">
                    </div>
                    <div style="grid-area: price;">
                        <label for="price">Purchase Price</label>
                        <input step="0.01" max="99999999.99" min="0.00" required type="number" id="price" name="price" value="<?= $Vehicle["PurchasePrice"] ?>" onblur="validate('price', 0, 99999999.99)">
                    </div>
                    <div style="grid-area: color;">
                        <label for="color">Color</label>
                        <input maxlength="50" required type="text" id="color" name="color" value="<?= $Vehicle["Color"] ?>"  onblur="validate('color')"/>
                    </div>
                    <div style="grid-area: vin;">
                        <label for="vin">VIN</label>
                        <input maxlength="17" required type="text" id="vin" name="vin" value="<?= $Vehicle["VIN"] ?>" onblur="validate('vin')">
                    </div>
                    <div style="grid-area: plate;">
                        <label for="plate">Licence Plate</label>
                        <input maxlength="10" required type="text" id="plate" name="plate" value="<?= $Vehicle["LicensePlate"] ?>" onblur="validate('plate')">
                    </div>
                    <div style="grid-area: button;" class="buttons">
                        <input type="hidden" name="user" value="<?= $Vehicle["UserID"] ?>">
                        <input type="hidden" name="id" value="<?= $Vehicle["VehicleID"] ?>">
                        <input type="hidden" name="status" value="<?= $Vehicle["Status"] ?>">
                        <button>Edit Vehicle</button>
                        <button type="button" id="close-edit">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Gas Log Modal, allows fot the adding of gas logs--->
    <div class="modal" id="add-gas">
        <div class="modal-content">
            <h2>Add Fuel Record</h2>
            <form action="./User/AddEditFuelController.php" method="post">
                <div class="fuel form">
                    <div style="grid-area: source;">
                        <label for="fuelSource">Source</label>
                        <input maxlength="50" required type="text" id="fuelSource" name="source" onblur="validate('fuelSource')">
                    </div>
                    <div style="grid-area: gallons;">
                        <label for="gallons">Gallons</label>
                        <input required max="99999999.99" min="0.00" type="number" step="0.01" id="gallons" name="gallons" onblur="validate('gallons', 0, 99999999.99)">
                    </div>
                    <div style="grid-area: cost;">
                        <label for="fuelCost">Total Cost</label>
                        <input required max="99999999.99" min="0.00" type="number" step="0.01" id="fuelCost" name="cost" onblur="validate('fuelCost', 0, 99999999.99)">
                    </div>
                    <div style="grid-area: miles;">
                        <label for="fuelMiles">Miles</label>
                        <input required max="99999999.99" min="0.00" type="number" step="0.01" id="fuelMiles" name="miles" onblur="validate('fuelMiles', 0, 99999999.99)">
                    </div>
                    <div style="grid-area: date;">
                        <label for="purchaseDate">Purchase Date</label>
                        <input required type="date" id="date" name="date">
                    </div>
                    <div style="grid-area: type;">
                        <label for="type">Fuel Type</label>
                        <select name="type" id="type">
                            <option value="unleaded">Unleaded</option>
                            <option value="midgrade">Unleaded Midgrade</option>
                            <option value="premium">Unleaded Premium</option>
                            <option value="diesel">Diesel</option>
                        </select>
                    </div>
                    <div style="grid-area: button;" class="buttons">
                        <input type="hidden" name="vehicleID" value="<?= $Vehicle["VehicleID"] ?>">
                        <button>Add Fuel Record</button>
                        <button type="button" id="close-gas">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Maintenance Modal, allows for the adding of maintenance logs -->
    <div class="modal" id="add-maint">
        <div class="modal-content">
            <h2>Add Maintenance Record</h2>
            <form method="post" action="./User/AddEditMaintenanceController.php">
                <div class="maint form">
                    <div style="grid-area: vendor;">
                        <label for="maintVendor">Vendor</label>
                        <input maxlength="50" required type="text" id="maintVendor" name="vendor" onblur="validate('vendor')">
                    </div>
                    <div style="grid-area: vendorAddress;">
                        <label for="vendorAddress">Vendor Address</label>
                        <input maxlength="255" required type="text" id="vendorAddress" name="vendorAddress" onblur="validate('vendorAddress')">
                    </div>
                    <div style="grid-area: desc;">
                        <label for="desc">Description</label>
                        <input maxlength="255" required type="text" id="desc" name="desc" onblur="validate('desc')">
                    </div>
                    <div style="grid-area: cost;">
                        <label for="cost">Total Cost</label>
                        <input required max="99999999.99" min="0.00" type="number" step="0.01" id="cost" name="cost" onblur="validate('cost', 0, 99999999.99)">
                    </div>
                    <div style="grid-area: milesBefore;">
                        <label for="milesBefore">Mileage Before</label>
                        <input required max="99999999.99" min="0.00" type="number" step="0.01" id="milesBefore" name="milesBefore" onblur="validate('milesBefore', 0, 99999999.99)">
                    </div>
                    <div style="grid-area: milesAfter;">
                        <label for="milesAfter">Mileage After</label>
                        <input type="number" id="milesAfter" step="0.01" name="milesAfter">
                        <label class="sublabel" for="milesAfter">Not Required</label>
                    </div>
                    <div style="grid-area: dateStart;">
                        <label for="vendor">Date Started</label>
                        <input required type="date" id="dateStart" name="dateStart">
                    </div>
                    <div style="grid-area: dateFin;">
                        <label for="dateFin">Date Completed</label>
                        <input type="date" id="dateFin" name="dateFin">
                        <label class="sublabel" for="milesAfter">Not Required</label>
                    </div>
                    <div style="grid-area: button;" class="buttons">
                        <input required type="hidden" name="vehicleID" value="<?= $Vehicle["VehicleID"] ?>">
                        <button type="submit">Add Maintenance Record</button>
                        <button type="button" id="close-maint">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Status Modal, allows for the editing of vehicle status without the whole edit modal  -->
    <div class="modal" id="edit-status">
        <div class="modal-content">
            <h2>Edit Vehicle Status</h2>
            <form method="post" action="./User/AddEditVehicleController.php">
                <div class="status form">
                    <select required name="status" id="status" style="grid-area: select;">
                        <option <?= $Vehicle["Status"] == "ready" ? "selected" : "" ?> value="ready">In Use</option>
                        <option <?= $Vehicle["Status"] == "inactive" ? "selected" : "" ?> value="inactive">Inactive</option>
                        <option <?= $Vehicle["Status"] == "shop" ? "selected" : "" ?> value="shop">In Shop</option>
                        <option <?= $Vehicle["Status"] == "oos" ? "selected" : "" ?> value="oos">Out of Service</option>
                    </select>
                    <div style="grid-area: buttons;" class="buttons">
                        <input type="hidden" name="id" value="<?= $Vehicle["VehicleID"] ?>">
                        <button type="submit">Edit Status</button>
                        <button type="button" id="close-status">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- BODY -->
    <div class="body info p-10"> <!-- Table displaying the vehicle information -->
        <div class="info-head" style="grid-area: title;">
            <a href="./User/?action=vehicles" class="icon button">&#xE138</a>
            <h1 class="text-center" ><?= $Vehicle["Year"] . " " . $Vehicle["Brand"] . " " . $Vehicle["Model"] ?></h1>
            <div class="car-buttons">
                <button id="open-status">Change Status</button>
                <button id="open-edit">Edit</button>
            </div>
        </div>
        <div class="card p-15 w-100" style="grid-area: info;">
            <table style="table-layout: fixed;">
                <tbody>
                    <tr class="lined">
                        <th class="text-left">Owned By</th>
                        <td class="text-left"><?= $User["FirstName"] . " " . $User["LastName"] ?></td>
                    </tr>
                    <tr class="lined">
                        <th class="text-left">VIN</th>
                        <td class="text-left"><?= $Vehicle["VIN"] ?></td>
                    </tr>
                    <tr class="lined">
                        <th class="text-left">Licence Plate</th>
                        <td class="text-left"><?= $Vehicle["LicensePlate"] ?></td>
                    </tr>
                    <tr class="lined">
                        <th class="text-left">State</th>
                        <td class="text-left"><?= $Vehicle["State"] ?></td>
                    </tr>
                    <tr class="lined">
                        <th class="text-left">Purchase Date</th>
                        <td class="text-left"><?= Date("n/d/Y", strtotime($Vehicle["PurchaseDate"])) ?></td>
                    </tr>
                    <tr class="lined">
                        <th class="text-left">Purchase Price</th>
                        <td class="text-left"><?= '$' . $Vehicle["PurchasePrice"] ?></td>
                    </tr>
                    <tr class="lined">
                        <th class="text-left">Purchase Mileage</th>
                        <td class="text-left"><?= $Vehicle["PurchaseMileage"] ?></td>
                    </tr>
                    <tr class="lined">
                        <th class="text-left">Status</th>
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
                </tbody>
            </table>
        </div>
        <!-- Fuel table, listing all fuel logs for the vehicle -->
        <div class="card p-10 w-100" style="grid-area: gas;">
            <div class="card-body">
                <div class="info-head">
                    <h3>Fuel Log</h3>
                    <button id="open-gas">Add</button>
                </div>
                <div class="scroll-list">
                    <table>
                        <thead>
                            <th class="text-center" style="width: 16.67%;" >Location</th>
                            <th class="text-center" style="width: 16.67%;" >Cost</th>
                            <th class="text-center" style="width: 16.67%;" >Gallons</th>
                            <th class="text-center" style="width: 16.67%;" >Mileage</th>
                            <th class="text-center" style="width: 16.67%;" >Date</th>
                            <th class="text-center" style="width: 16.67%;" ></th>
                        </thead>
                        <tbody>
                            <?php if (count($fuel) > 0): ?>
                            <?php foreach ($fuel as $fuelItem): ?>
                                <tr class="lined">
                                    <td class="text-center"><?= $fuelItem["Source"] ?></td>
                                    <td class="text-center"><?= "$" . $fuelItem["TotalCost"] ?></td>
                                    <td class="text-center"><?= $fuelItem["Gallons"] ?></td>
                                    <td class="text-center"><?= $fuelItem["Mileage"] ?></td>
                                    <td class="text-center"><?= Date("n/d/Y", strtotime($fuelItem["Date"])) ?></td>
                                    <td class="text-center"><a href="./User/?action=gas&id=<?= $fuelItem["FuelID"] ?>">View</a></td>
                                </tr>
                            <?php endforeach ?>
                            <?php else: ?>
                                <tr class="lined">
                                    <td colspan="5" style="text-align: center;">No fuel logs yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Maintenance table, listing all Maintenance logs for the vehicle -->
        <div class="card p-10 w-100" style="grid-area: maint;">
            <div class="card-body">
                <div class="info-head">
                    <h3>Maintenance Log</h3>
                    <button id="open-maint">Add</button>
                </div>
                <div class="scroll-list">
                    <table>
                        <thead>
                            <th class="text-center" style="width: 20%;">Vendor</th>
                            <th class="text-center" style="width: 20%;">Cost</th>
                            <th class="text-center" style="width: 20%;">Date</th>
                            <th class="text-center" style="width: 20%;">Status</th>
                            <th class="text-center" style="width: 20%;"></th>
                        </thead>
                        <tbody>
                        <?php if (count($maintenance) > 0): ?>
                            <?php foreach ($maintenance as $maintItem): ?>
                                <tr class="lined">
                                    <td class="text-center" ><?= $maintItem["Vendor"] ?></td>
                                    <td class="text-center" ><?= '$' . $maintItem["TotalCost"] ?></td>
                                    <td class="text-center" ><?= Date("n/d/Y", strtotime($maintItem["Date"])) ?></td>
                                    <td><p class="icon text-center"><?= $maintItem["Started"] == 0 ? "&#xE7B4" : (isset($maintItem["Completed"]) ? "&#xE182" : "&#xE80E") ?></p></td>
                                    <td class="text-center" ><a href="./User/?action=maint&id=<?= $maintItem["MaintenanceID"] ?>">View</a></td>
                                </tr>
                            <?php endforeach ?>
                            <?php else: ?>
                                <tr class="lined">
                                    <td colspan="5" class="text-center" >No maintenence logs yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="./scripts/vehicleInfo.js"></script>
        <script src="./scripts/validate.js"></script>
    </div>
</body>
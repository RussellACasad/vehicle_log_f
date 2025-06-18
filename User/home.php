<?php
/*

This is the home page for the dashboard, and it displays a bunch of graphs and data about the vehicles logge by the user.

*/

// Get all maintenance logs that are started but not finished, ordered by date with the most recent first.
$sql = "SELECT * 
        FROM Maintenance, Vehicles 
        WHERE Maintenance.VehicleID = Vehicles.VehicleID AND Vehicles.UserID = :id AND Maintenance.MileageAfter IS NULL AND Maintenance.Started = 1
        ORDER BY Maintenance.Date DESC";
$statement = $db->prepare($sql);
$statement->bindValue(":id", $_SESSION["user-id"]);
$statement->execute();
$MaintLogs = $statement->fetchAll();
$statement->closeCursor();

// Get all maintenance logs, ordered by date with the most recent first.
$sql = "SELECT * 
        FROM Maintenance, Vehicles 
        WHERE Maintenance.VehicleID = Vehicles.VehicleID AND Vehicles.UserID = :id AND Maintenance.Started = 1
        ORDER BY Maintenance.Date DESC";
$statement = $db->prepare($sql);
$statement->bindValue(":id", $_SESSION["user-id"]);
$statement->execute();
$AllMaintLogs = $statement->fetchAll();
$statement->closeCursor();

// Gets all maintenence logs not started but scheduled
$sql = "SELECT * 
        FROM Maintenance, Vehicles 
        WHERE Maintenance.VehicleID = Vehicles.VehicleID AND Vehicles.UserID = :id AND Maintenance.Started = 0
        ORDER BY Maintenance.Date DESC";
$statement = $db->prepare($sql);
$statement->bindValue(":id", $_SESSION["user-id"]);
$statement->execute();
$ScheduledMaintLogs = $statement->fetchAll();
$statement->closeCursor();

// Gets all fuel logs
$fuelsql = "SELECT * FROM Fuel, Vehicles WHERE Vehicles.UserID = :id AND Fuel.VehicleID = Vehicles.VehicleID";
$statement = $db->prepare($fuelsql);
$statement->bindValue(":id", $_SESSION["user-id"]);
$statement->execute();
$GasLogs = $statement->fetchAll();
$statement->closeCursor();

/* Car Status Widget */

$ready = 0;
$inactive = 0;
$shop = 0;
$oos = 0;

foreach ($Vehicles as $Vehicle) { // Counts how many vehicles have each status
    switch ($Vehicle["Status"]) {
        case "ready":
            $ready++;
            break;
        case "inactive":
            $inactive++;
            break;
        case "shop":
            $shop++;
            break;
        case "oos":
            $oos++;
            break;
    }
}

/* Average Gas Price Widget */
$monthValues = [];

$gasStationFrequency = [];

foreach ($GasLogs as $Log) {
    $month = date("Y-m", strtotime($Log["Date"])); // Gets the year and month of each gas log
    if (!isset($monthValues[$month])) { // registers the month in the array of values if not set
        $monthValues[$month] = [];
    }
    if (!isset($gasStationFrequency[$Log["Source"]])) { // Registers the gas station in the array of values ig not set
        $gasStationFrequency[$Log["Source"]] = 0;
    }

    $monthValues[$month][] = $Log["TotalCost"]; // adds the cost to the month to the array
    $gasStationFrequency[$Log["Source"]]++; // adds the gas station to the values, to count how mant times it was went to
}

$currentDate = date("Y-m");
$last12Months = []; // Gets the last 12 months

for ($i = 0; $i < 12; $i++) {
    $last12Months[] = date("Y-m", strtotime("-$i months"));
}

sort($last12Months); // Sorts the last 12 months

$Months = [];
$Values = [];

foreach ($last12Months as $month) { // gets the average of the last 12 months of fuel costs
    if (isset($monthValues[$month])) {
        $Months[] = $month;
        $Values[] = array_sum($monthValues[$month]) / count($monthValues[$month]);
    }
}

/* Miles Per Gallon Widget */
$cars = [];
foreach ($GasLogs as $log) { // Gets each gas log and puts it into a 2D array, with each car having all it's logs tied to it. 
    $name = $log["Year"] . " " . $log["Model"] . " " . $log["Brand"];
    if (!isset($cars[$name])) {
        $cars[$name] = [];
    }

    $cars[$name][] = [
        "miles" => $log["Mileage"],
        "gallons" => $log["Gallons"]
    ];
}

$mpgData = [];

$carsArray = [];
$mpgArray = [];

foreach ($cars as $carName => $logs) {// Iterates through each log, calculating the miles per gallon for each log, and puts it into an array
    $value = 0;
    $mpg = [];
    $previousMilesValue = 0;

    foreach ($logs as $entry) {
        if ($previousMilesValue == 0) {
            $previousMilesValue = $entry["miles"];
            continue;
        } else {
            if ($entry["gallons"] == 0) continue;
            $mpg[] = ($entry["miles"] - $previousMilesValue) / $entry["gallons"];
            $previousMilesValue = $entry["miles"];
        }
    }
    $carsArray[] = $carName;
    $mpgArray[] = count($mpg) == 0 ? 0 : array_sum($mpg) / count($mpg);
}

/* Maintenence Cost Widget */

$chartMaint = [];
foreach ($AllMaintLogs as $log) { // Makes an associative array of cars -> maintenance cost, to get the total spent on each car 
    $name = $log["Year"] . " " . $log["Model"] . " " . $log["Brand"];
    if (!isset($chartMaint[$name])) {
        $chartMaint[$name] = 0;
    }

    $chartMaint[$name] += $log["TotalCost"];
}

$maintCarsArray = [];
$maintTotalsArray = [];
foreach ($chartMaint as $car => $total) {
    $maintCarsArray[] = $car;
    $maintTotalsArray[] = $total;
}

/* Gas Station Frequency Widget */
$gasFrequencySource = [];
$gasFrequencyAmount = [];
foreach ($gasStationFrequency as $source => $amount) { // Gets the amount of times each gas station was used
    $gasFrequencySource[] = $source;
    $gasFrequencyAmount[] = $amount;
}
?>

<script>
    // inject PHP code into javascript vars to display each chart using chart.js
    const gasFrequencySource = [
        <?php foreach ($gasFrequencySource as $Value) {
            echo "\"$Value\", ";
        } ?>
    ];

    const gasFrequencyAmount = [
        <?php foreach ($gasFrequencyAmount as $Value) {
            echo "\"$Value\", ";
        } ?>
    ];

    const GasDates = [
        <?php foreach ($Months as $Month) {
            echo "\"$Month\", ";
        } ?>
    ];

    const GasValues = [
        <?php foreach ($Values as $Value) {
            echo "\"$Value\", ";
        } ?>
    ];

    const mpgCars = [
        <?php foreach ($carsArray as $Value) {
            echo "\"$Value\", ";
        } ?>
    ];

    const mpg = [
        <?php foreach ($mpgArray as $Value) {
            echo "\"$Value\", ";
        } ?>
    ]

    const maintCarArray = [
        <?php foreach ($maintCarsArray as $Value) {
            echo "\"$Value\", ";
        } ?>
    ];

    const maintTotalsArray = [
        <?php foreach ($maintTotalsArray as $Value) {
            echo "\"$Value\", ";
        } ?>
    ];
</script>

<body>
    <div class="body home"> 
        <!-- Average gas price widget -->
        <div class="card p-10 h-100 w-100" style="grid-area: box1;">
            <div class="info-head">
                <h3>Average Gas Price</h3> 
            </div>
            <div class="canvas">
                <canvas id="gas-graph"></canvas>
            </div>
        </div>

        <!-- Car Status widget -->
        <div class="card p-10 h-100 w-100" style="grid-area: box2;">
            <div class="info-head">
                <h3>Car Statuses</h3>
            </div>
            <table>
                <tbody>
                    <tr class="lined">
                        <th class="text-left" style="width: 90%;">Ready</th>
                        <td class="text-right" style="width: 10%;"><?= $ready ?></td>
                    </tr>
                    <tr class="lined">
                        <th class="text-left" style="width: 90%;">In Shop</th>
                        <td class="text-right" style="width: 10%;"><?= $shop ?></td>
                    </tr>
                    <tr class="lined">
                        <th class="text-left" style="width: 90%;">Inactive</th>
                        <td class="text-right" style="width: 10%;"><?= $inactive ?></td>
                    </tr>
                    <tr class="lined">
                        <th class="text-left" style="width: 90%;">Out of Service</th>
                        <td class="text-right" style="width: 10%;"><?= $oos ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Ongoing Maintenance widget -->
        <div class="card p-10 h-100 w-100" style="grid-area: box3;">
            <div class="info-head">
                <h3>Ongoing Maintenance</h3>
            </div>
            <div class="scroll-list">
                <table>
                    <thead>
                        <th class="text-center" style="width: 35%;">Vehicle</th>
                        <th class="text-center" style="width: 25%;">Vendor</th>
                        <th class="text-center" style="width: 15%;">Cost</th>
                        <th class="text-center" style="width: 15%;">Date</th>
                    </thead>
                    <tbody>
                        <?php if (count($MaintLogs) > 0): ?>
                            <?php foreach ($MaintLogs as $maintItem): ?>
                                <tr class="lined">
                                    <td class="text-center"><?= $maintItem["Year"] . " " . $maintItem["Brand"] . " " . $maintItem["Model"] ?></td>
                                    <td class="text-center"><?= $maintItem["Vendor"] ?></td>
                                    <td class="text-center"><?= '$' . $maintItem["TotalCost"] ?></td>
                                    <td class="text-center"><?= Date("n/d/Y", strtotime($maintItem["Date"])) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr class="lined">
                                <td colspan="5" class="text-center">No Ongoing Maintenance.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Scheduled Maintenance widget -->
        <div class="card p-10 h-100 w-100" style="grid-area: box4;">
            <div class="info-head">
                <h3>Scheduled Maintenance</h3>
            </div>
            <div class="scroll-list">
                <table>
                    <thead>
                        <th class="text-center" style="width: 35%;">Vehicle</th>
                        <th class="text-center" style="width: 25%;">Vendor</th>
                        <th class="text-left" style="width: 25%;">Description</th>
                        <th class="text-center" style="width: 15%;">Date</th>
                    </thead>
                    <tbody>
                        <?php if (count($MaintLogs) > 0): ?>
                            <?php foreach ($ScheduledMaintLogs as $scheduledMaintItem): ?>
                                <tr class="lined">
                                    <td class="text-center"><?= $scheduledMaintItem["Year"] . " " . $scheduledMaintItem["Brand"] . " " . $scheduledMaintItem["Model"] ?></td>
                                    <td class="text-center"><?= $scheduledMaintItem["Vendor"] ?></td>
                                    <td class="text-center"><?= $scheduledMaintItem["Description"] ?></td>
                                    <td class="text-center"><?= Date("n/d/Y", strtotime($scheduledMaintItem["Date"])) ?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr class="lined">
                                <td colspan="5" class="text-center">No Ongoing Maintenance.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Average MPG widget -->
        <div class="card p-10 h-100 w-100" style="grid-area: box5;">
            <div class="info-head">
                <h3>Average Miles Per Gallon</h3>
            </div>
            <div class="canvas">
                <canvas id="mpg-graph"></canvas>
            </div>
        </div>
        <!-- Car Maintenance Cost widget -->
        <div class="card p-10 h-100 w-100" style="grid-area: box6;">
            <div class="info-head">
                <h3>Maintenance Costs</h3>
            </div>
            <div class="canvas">
                <canvas id="maintCost-graph"></canvas>
            </div>
        </div>
        <!-- Gas Station Usage widget -->
        <div class="card p-10 h-100 w-100" style="grid-area: box7;">
            <div class="info-head">
                <h3>Gas Station Usage</h3>
            </div>
            <div class="canvas">
                <canvas id="stationChart"></canvas>
            </div>
        </div>
    </div>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
    </script>
    <script src="./scripts/home.js"></script>
</body>
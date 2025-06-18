<?php

/*

Summary:
This is the Dashboard of the application, and it houses all of the pages for the user to use. 
It also allows for the displaying of notificatins for the user.

 */

$name = "Dashboard";
$selected = "dash";

$States = ['AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'];
$message = $_SESSION["message"] ?? "";

$return = filter_input(INPUT_GET, "return");
?>

<body>
    <?php include '../Views/navbar.php'; ?> <!-- The navbar at the top of the page -->
    <?php if (!empty($message)): ?><!-- This is the notification in the top right -->
        <script>
            var isShowingMessage = <?= $message ? 'true' : 'false'; ?>;
        </script>
        <div class="notification" id="notification">
            <h3>Success!</h3>
            <p>
                <?php switch ($message) { // This are the pre-programmed messages sent to the notification
                    case "add":
                        echo "New car added successfully!";
                        break;
                    case "edit":
                        echo "Vehicle edited successfully";
                        break;
                    case "editStatus":
                        echo "Vehicle status edited successfully";
                        break;
                    case "addGas":
                        echo "Gas record added successfully";
                        break;
                    case "editGas":
                        echo "Gas record edited successfully";
                        break;
                    case "addMaintenence":
                        echo "Maintenence record was added successfully";
                        break;
                    case "editMaintenence":
                        echo "Maintenence record was edited successfully";
                        break;
                    case "schedMaintenence":
                        echo "Maintenence was scheduled successfully!";
                        break;
                    default: // No message pre-programmed for it
                        echo $message;
                        break;
                }
                ?>
            </p>
        </div>
    <?php
        $_SESSION["message"] = ""; // Resets the message variable so it doesn't keep displaying
    endif;
    ?>

    <div class="body dash"> <!-- The code for the sidebar buttons -->
        <div class="sidebar">
            <ul>
                <li>
                    <a class="button lg <?= $action == 'home' ? "selected" : "" ?>" href="./User/?action=home">
                        <p class="icon">&#xE2C2</p>
                        <p class="nav-text">Home</p>
                    </a>
                </li>
                <li>
                    <a class="button lg <?= $action == 'vehicles' || $action == 'info' || $action == 'gas' || ($action == 'maint' && !isset($return)) ? "selected" : "" ?>" href="./User/?action=vehicles">
                        <p class="icon">&#xE8CC</p>
                        <p class="nav-text">Vehicles</p>
                    </a>
                </li>
                <li>
                    <a class="button lg <?= $action == 'maintenence' || ($action == 'maint' && isset($return)) ? "selected" : "" ?>" href="./User/?action=maintenence">
                        <p class="icon">&#xE5D4</p>
                        <p class="nav-text">Maintenence</p>
                    </a>
                </li>
                <li>
                    <a class="button lg <?= $action == 'about' ? "selected" : "" ?>" href="./User/?action=about">
                        <p class="icon">&#xE2CE</p>
                        <p class="nav-text">About</p>
                    </a>
                </li>
            </ul>
        </div>
        <div class="dash-view"> <!-- The main content of the page, including a different page for different scenarios. -->
            <?php switch ($action) {
                case "home":
                    include './home.php'; // The dashboard home, which displays useful information about all vehicles
                    break;
                case "vehicles":
                    include './vehicleList.php'; // The list of all the user's vehicles
                    break;
                case "info":
                    include './vehicleInfo.php'; // The information about a user's vehicle
                    break;
                case "gas":
                    include './GasReport.php'; // A fuel report from a user's vehicle
                    break;
                case "maint":
                    include './MaintenenceReport.php'; // A maintenance report from a user's vehicle
                    break;
                case "maintenence":
                    include './maintenenceList.php'; // The list of all maintenance for all the user's vehicles
                    break;
                case "about":
                    include './about.php'; // The about page, for giving data about who made AutoLog, and any libraries used.
                    break;
                default:
                    echo 'BAD';
                    break;
            } ?>
        </div>
    </div>
</body>
<script src="./scripts/dashboard.js"></script>
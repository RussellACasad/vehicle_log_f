<?php
$selectedPage = $selectedPage ?: "home";
?>

<!DOCTYPE html>

<html>

<head>
    <base href="/finalProject/vehicle_log_f/">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/main.css">
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.0"></script>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
</head>

<nav class="nav">
    <ul class="left">
        <li class="logo"><a href="#">AutoLog <?= $loginRole == "admin" ? "Admin" : "" ?></a></li>
        <?php if ($loginRole != "guest"): ?>
        <?php endif; ?>
    </ul>
    <ul class="right">
        <?php switch ($loginRole):
            case "user":
            case "admin":
        ?>
                <li><a class="button lg primary" href="./logoutController.php">Log Out</a></li>
            <?php break;
            default:
            ?>
                <li><a class="button lg primary" href="./signup.php">Sign Up</a></li>
                <li><a class="button lg primary" href="./login.php">Log In</a></li>
        <?php break;
        endswitch; ?>
    </ul>
</nav>
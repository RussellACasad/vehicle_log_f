<?php
include './Views/sessionManager.php';
/**
 * A basic landing page for guests. 
 * 
 * Users and Admins will not see this, and will be redirected to the Dashboard. 
 */
if (isset($_SESSION["login-valid"]) && $_SESSION["login-valid"]) {
    switch ($_SESSION["user-role"]) {
        case "user":
            header("Location: ./User/");
            break;
        case "admin":
            header("Location: ./Admin/");
            break;
    }
}

$name = "Home";
$selectedPage = "home";
?>

<body>
    <?php include './Views/navbar.php'; ?>
    <div class="body">
        <div class="card splash">
            <h2>Welcome to AutoLog</h2>
            <p class="lead">Your ultimate solution for tracking and managing all your car details.</p>
        </div>
        <div class="boxes">
            <div class="box card">
                <h3>Track Gas Mileage</h3>
                <p>Monitor all of your car's gas mileage to ensure performance is optimal.</p>
            </div>
            <div class="box card">
                <h3>Track Maintenance</h3>
                <p>Never lose track of your car's maintenance again, and ensure someone isn't overcharging for the same job.</p>
            </div>
            <div class="box card">
                <h3>Access Statistics</h3>
                <p>Access loads of statistics to ensure nothing is wrong with your cars.</p>
            </div>
        </div>
    </div>
    <div class="footer">
        <p>AutoLog - Russell Casad - CPT-283</p>
    </div>
</body>
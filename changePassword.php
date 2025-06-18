<?php
/*
This is run when the user is set to change their password upon login, this is the frontend
Can be called in 2 situations:
    - User resets their password
    - Manager makes an account for a user and selects the option for the user to reset their password on login. 
*/
include './Views/sessionManager.php';
$name = "Home";
$selectedPage = "home";

if (!isset($_SESSION["login-valid"]) || $_SESSION["login-valid"] == true || !isset($_SESSION["user-id"])) {
    header("Location: ./login.php");
    die;
} // Redirects to login unless explicitly asked for with the database. 

?>

<?php include './Views/navbar.php'; ?>

<body> <!-- Prompts for 2 passwords, the password and a validate -->
    <div class="body">
        <div class="card p-20 m-20">
            <h2>Change Password</h2>
            <?php if (isset($_SESSION["changePassWarn"])): ?>
                <p class="warning text-center"><?= $_SESSION["changePassWarn"] ?></p>
            <?php $_SESSION["changePassWarn"] = null;
            endif; ?>
            <form action="./changePassController.php" method="post">
                <div class="login form">
                    <div style="grid-area: username;">
                        <label for="password">Password</label>
                        <input required type="password" id="password" name="password">
                    </div>
                    <div style="grid-area: password;">
                        <label for="passwordValidate">Validate Password</label>
                        <input required type="password" id="passwordValidate" name="passwordValidate">
                    </div>
                    <div style="grid-area: button;" class="buttons">
                        <input type="hidden" value="<?= $_SESSION["user-id"] ?>" name="id" ?>
                        <button>Change</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
        <p>AutoLog - Russell Casad - CPT-283</p>
    </div>
</body>
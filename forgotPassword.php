<?php

/*

If the user forgets their password, they can reset it here. They need to provide a username and email address to validate their data.

If data is validatd, a password is generated for them and printed to them. (I didn't want to make an email service for this project, sorry!)

*/

include './Views/sessionManager.php';
$name = "Home";
$selectedPage = "home";
?>

<?php include './Views/navbar.php'; ?>

<body>
    <div class="body">
        <div class="card p-20 m-20">
            <h2>Forgot Password</h2>
            <?php if (isset($_SESSION["forgotPassWarn"])): ?>
                <p class="warning text-center"><?= $_SESSION["forgotPassWarn"] ?></p>
            <?php endif;
            $_SESSION["forgotPassWarn"] = null; ?> <!-- Displays a warning if a user is not found -->
            <form action="./forgotPasswordController.php" method="post">
                <div class="login form">
                    <div style="grid-area: username;">
                        <label for="username">Username</label>
                        <input required type="text" id="username" name="username">
                    </div>
                    <div style="grid-area: password;">
                        <label for="password">Email</label>
                        <input required type="email" id="email" name="email">
                    </div>
                    <div style="grid-area: button;" class="buttons">
                        <button>Reset Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
        <p>AutoLog - Russell Casad - CPT-283</p>
    </div>
</body>
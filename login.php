<?php
/**
 * 
 * A login page. It's where people can log in. 
 * 
 * They can also go to the forgot password page. 
 * 
 */
include './Views/sessionManager.php';
$name = "Home";
$selectedPage = "home";
?>

<?php include './Views/navbar.php'; ?>

<body>
    <div class="body">
        <div class="card p-20 m-20">
            <h2>Log in</h2>
            <?php if (isset($_SESSION["login-valid"]) && $_SESSION["login-valid"] == "false"): ?>
                <p class="warning text-center">Login Failed!</p>
            <?php endif; $_SESSION["login-valid"] = null; ?>
            <?php if (isset($_SESSION["login-info"])): ?>
                <p class="info-card text-center"><?= $_SESSION["login-info"] ?></p>
            <?php
            $_SESSION["login-info"] = null;  
            endif; ?>
            <form action="./loginController.php" method="post">
                <div class="login form">
                    <div style="grid-area: username;">
                        <label for="username">Username</label>
                        <input required type="text" id="username" name="username">
                    </div>
                    <div style="grid-area: password;">
                        <label for="password">Password</label>
                        <input required type="password" id="password" name="password">
                    </div>
                    <div style="grid-area: button;" class="buttons">
                        <button>Log in</button>
                    </div>
                </div>
            </form>
        </div>
        <a href="./forgotPassword.php" class="link-dec">Forgot password?</a>
    </div>
    <div class="footer">
        <p>AutoLog - Russell Casad - CPT-283</p>
    </div>
</body>
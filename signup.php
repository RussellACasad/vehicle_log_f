<?php
/**
 * 
 * This allows a guest to make an account, and utilize the service of AutoLog. 
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
            <h2>Sign Up</h2>
            <?php if (isset($_SESSION["signupWarn"])): ?>
            <?php endif;
            $_SESSION["signupWarn"] = null; ?>
            <form action="./signUpController.php" method="post">
                <div class="signup form">
                    <div style="grid-area: email;">
                        <label for="email">Email</label>
                        <input required type="email" id="email" name="email" class="">
                    </div>
                    <div style="grid-area: username;">
                        <label for="username">Username</label>
                        <input required type="text" id="username" name="username" class="">
                    </div>
                    <div style="grid-area: password;">
                        <label for="password">Password</label>
                        <input required type="password" id="password" name="password" class="">
                    </div>
                    <div style="grid-area: passwordVerify;">
                        <label for="password">Verify Password</label>
                        <input required type="password" id="passwordVerify" name="passwordVerify" class="">
                    </div>
                    <div style="grid-area: firstName;">
                        <label for="firstName">First Name</label>
                        <input required type="text" id="firstName" name="firstName" class="">
                    </div>
                    <div style="grid-area: lastName;">
                        <label for="lastName">Last Name</label>
                        <input required type="text" id="lastName" name="lastName" class="">
                    </div>
                    <div style="grid-area: button;" class="buttons">
                        <button>Sign Up</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
        <p>AutoLog - Russell Casad - CPT-283</p>
    </div>
</body>
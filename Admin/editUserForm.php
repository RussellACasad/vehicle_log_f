<?php 
/*

A form to edit a user. 

*/
require_once './config.php'; 

$userID = filter_input(INPUT_GET, "id"); 

$sql = "SELECT * FROM Users WHERE UserID = :id";
$statement = $db -> prepare($sql);
$statement -> bindValue(":id", $userID); 
$statement -> execute(); 
$user = $statement -> fetch(); 
$statement -> closeCursor(); 

?>

<div class="card p-20 m-20">
    <h2>Edit User</h2>
    <form action="./Admin/AddEditUserController.php" method="post">
        <div class="user-edit form">
            <div style="grid-area: firstName;">
                <label for="firstName">First Name</label>
                <input maxlength="50" required type="text" id="firstName" name="firstName" value="<?= $user["FirstName"] ?>" onblur="validate('firstName')">
            </div>
            <div style="grid-area: lastName;">
                <label for="lastName">Last Name</label>
                <input maxlength="50" required type="text" id="lastName" name="lastName" value="<?= $user["LastName"] ?>" onblur="validate('lastName')">
            </div>
            <div style="grid-area: username;">
                <label for="username">Username</label>
                <input maxlength="50" required type="text" id="username" name="username" value="<?= $user["Username"] ?>" onblur="validate('username')">
            </div>
            <div style="grid-area: email;">
                <label for="email">Email</label>
                <input maxlength="50" required type="email" id="email" name="email" value="<?= $user["Email"] ?>" onblur="validate('email')">
            </div>
            <div class="check" style="grid-area: changePass;">
                <input type="checkbox" id="changePass" name="changePass" <?= $user["ChangePassword"] == "1" ? "checked" : "" ?>>
                <label for="changePass">Change password on first login</label>
            </div>
            <div style="grid-area: role;">
                <label for="role">Role</label>
                <select name="role" id="role">
                    <option value="user" <?= $user["Role"] == "user" ? "selected" : "" ?>>User</option>
                    <option value="admin"<?= $user["Role"] == "admin" ? "selected" : "" ?>>Admin</option>
                </select>
            </div>
            <div style="grid-area: button;" class="buttons">
                <button>Edit User</button>
                <a href="./Admin/?action=users" class="button">Back</a>
                <input name="id" type="hidden" value="<?= $userID ?>" />
            </div>
        </div>
    </form>
    <script src="./scripts/validate.js"></script>
</div>
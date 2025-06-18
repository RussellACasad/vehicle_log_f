<?php
$sql = "SELECT * FROM Users";
$statement = $db->prepare($sql);
$statement->execute();
$Users = $statement->fetchAll();
$statement->closeCursor();
?>

<!-- Delete Modal, confirms delete -->
<div class="modal" id="delete-modal">
    <div class="modal-content">
        <div class="modal-message">
            <div class="header">
                <h2 id="delete-modal-title"></h2>
            </div>
            <div class="prompt">
                <p>Are you sure you want to delete this user?</p>
            </div>
            <div class="buttons">
                <form action="./Admin/DeleteController.php">
                    <input type="hidden" value="user" name="action">
                    <input type="hidden" value="" name="id" id="deleteID">
                    <button id="close-delete" type="button">Back</button>
                    <button type="submit">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal" id="add-user">
    <div class="modal-content">
        <h2>Add User</h2>
        <form action="./Admin/AddEditUserController.php" method="post">
            <div class="user form">
                <div style="grid-area: firstName;">
                    <label for="firstName">First Name</label>
                    <input maxlength="50" required type="text" id="firstName" name="firstName" onblur="validate('firstName')">
                </div>
                <div style="grid-area: lastName;">
                    <label for="lastName">Last Name</label>
                    <input maxlength="50" required type="text" id="lastName" name="lastName" onblur="validate('lastName')">
                </div>
                <div style="grid-area: username;">
                    <label for="username">Username</label>
                    <input maxlength="50" required type="text" id="username" name="username" onblur="validate('username')">
                </div>
                <div style="grid-area: email;">
                    <label for="email">Email</label>
                    <input maxlength="50" required type="email" id="email" name="email" onblur="validate('email')">
                </div>
                <div style="grid-area: password;">
                    <label for="password">Password</label>
                    <input maxlength="50" required type="password" id="password" name="password" onblur="validate('password')">
                </div>
                <div class="check" style="grid-area: changePass;">
                    <input type="checkbox" id="changePass" name="changePass" checked>
                    <label for="changePass">Change password on first login</label>
                </div>
                <div style="grid-area: role;">
                    <label for="role">Role</label>
                    <select name="role" id="role">
                        <option value="user" selected>User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div style="grid-area: button;" class="buttons">
                    <button>Add User</button>
                    <button type="button" id="close-add-user">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Displays all registered users with the option to edit or delete -->
<div class="table">
    <div class="info-head">
        <h1>Users</h1>
        <button id="open-add-user">New User</button>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 11.11%;">Name</th>
                <th style="width: 11.11%;">Username</th>
                <th style="width: 11.11%;">Email</th>
                <th style="width: 11.11%;">Role</th>
                <th style="width: 11.11%;">Created</th>
                <th style="width: 11.11%;">Last Login</th>
                <th style="width: 11.11%;">Modified</th>
                <th style="width: 11.11%;"></th>
                <th style="width: 11.11%;"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($Users as $User): ?>
                <tr class="lined">
                    <td><?= $User["FirstName"] . " " . $User["LastName"] ?></td>
                    <td><?= $User["Username"] ?></td>
                    <td><?= $User["Email"] ?></td>
                    <td><?= ucfirst($User["Role"]) ?></td>
                    <td><?= Date("n/d/Y", strtotime($User["CreatedDate"])) ?></td>
                    <td><?= isset($User["LastLoginDate"]) ? Date("n/d/Y", strtotime($User["LastLoginDate"])) : "Never" ?></td>
                    <td><?= Date("n/d/Y", strtotime($User["ModifiedDate"])) ?></td>
                    <td><a class="button primary" href="./Admin/?action=useredit&id=<?=$User["UserID"]?>">Edit</a></td>
                    <td><button type="button" id="delete-user" onclick="showDeleteModal('<?= $User["Username"] ?>', '<?= $User["UserID"] ?>')">Delete</button></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <script src="./scripts/userList.js"></script>
    <script src="./scripts/validate.js"></script>
</div>
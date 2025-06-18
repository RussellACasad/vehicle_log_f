<?php

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT); // Get fields from the form 
$firstName = filter_input(INPUT_POST, "firstName");
$lastName = filter_input(INPUT_POST, "lastName");
$uname = filter_input(INPUT_POST, "username");
$email = filter_input(INPUT_POST, "email");
$pass = filter_input(INPUT_POST, "password");
$changePass = filter_input(INPUT_POST, "changePass", FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
$role = filter_input(INPUT_POST, "role");

$errors = [];

// Validate required fields
if (empty($firstName)) {
    $errors[] = "First name is required.";
}
if (empty($lastName)) {
    $errors[] = "Last name is required.";
}
if (empty($uname)) {
    $errors[] = "Username is required.";
}
if ($email === false) {
    $errors[] = "Invalid email address.";
}
if (empty($pass) && empty($id)) {
    $errors[] = "Invalid password";
}
if (empty($role)) {
    $errors[] = "Role is required.";
}

// Output errors if any, should NOT be seen unless user modifies HTML
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "$error <br>";
    }
    exit;
}

require_once 'config.php'; // Gets the database

$date = date('Y-m-d H:i:s'); // Gets the current date

if (isset($id)) { // edit user
    $query = 'UPDATE Users  
        SET FirstName = :first, 
            LastName = :last, 
            Email = :email, 
            ModifiedDate = :mod, 
            Username = :un, 
            Role = :role, 
            ChangePassword = :changepass
            WHERE UserID = :id
        ';

    $statement = $db->prepare($query);
    $statement->bindValue(":id", $id); // Bind edit only vars


} else { // add
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $query = 'INSERT INTO Users  
    (FirstName, LastName, Email, Password, CreatedDate, ModifiedDate, Username, Role, ChangePassword)
        VALUES (:first, :last, :email, :pass, :cre, :mod, :un, :role, :changepass)';

    $statement = $db->prepare($query);
    $statement->bindValue(":pass", $hash); // Bind add only vars
    $statement->bindValue(":cre", $date);

}


$statement->bindValue(":first", $firstName); // Bind universal vars
$statement->bindValue(":last", $lastName);
$statement->bindValue(":email", $email);
$statement->bindValue(":mod", $date);
$statement->bindValue(":un", $uname);
$statement->bindValue(":role", $role);
$statement->bindValue(":changepass", $changePass == 1 ?: 0);
$statement->execute();
$statement->closeCursor();

// Redirect, both ADD and EDIT go to the same page.
header("Location: ./?action=users");
die;

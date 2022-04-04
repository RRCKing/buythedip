<?php
    require('db_connect.php');

    if ($_POST && !empty($_POST['login_name']) && !empty($_POST['password'])) {

        // Santitize user input to escape HTML entitles and filter out dangerous characters.
        $loginName = filter_input(INPUT_POST, 'login_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


        // Hash the password before store to the database.
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $queryEdit = "UPDATE members SET Login_Name = :login_name, Password = :password WHERE Member_ID = :member_id LIMIT 1";
        $statementEdit = $db->prepare($queryEdit);

        $statementEdit->bindValue('login_name', $loginName);
        $statementEdit->bindValue('password', $passwordHash);

        // Execute the INSERT.
        // execute() will check for possible SQL injection and remove if necessary
        if($statementEdit->execute()){
            echo "password changed";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    
    <form method="post" action="change_password.php">
        <label for="login_name">Login Name</label>
        <input id="login_name" name="login_name">
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        <input type="submit">
    </form>
</body>
</html>
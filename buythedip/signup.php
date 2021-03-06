<?php
    require('db_connect.php');    
    include('authen.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <?php include('nav.php')?>
    <form method="post" action="signup_process.php">
        <ul>
            <li>
                <label for="login_name">Login Name</label>
                <input id="login_name" name="login_name">
            </li>
            <li>
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </li>
            <li>
                <label for="password">Confirm Password</label>
                <input type="password" id="password2" name="password2">
            </li>
            <li>
                <label for="email">Email:</label>
                <input type="email" name="email" pattern=".+@buythedip\.com" size="30" required>
            </li>
            <li>
                <label for="street">Street</label>
                <input id="street" name="street">
            </li>
            <li>
                <label for="area">Area</label>
                <input id="area" name="area">
            </li>
            <li>
                <input type="submit">
            </li>
        </ul>
    </form>
</body>
</html>
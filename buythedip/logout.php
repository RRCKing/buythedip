<?php
    //require('db_connect.php');
    //session_start();
    if(isset($_POST['logout'])) {

        $_SESSION['sess_member_id']   = '';
        $_SESSION['sess_login_name'] = '';
        $_SESSION['sess_role_id'] = '';
        session_destroy();
        if(empty($_SESSION['sess_member_id'])) {
            header("location: index.php");
        }

    }
    
?>

<form method="post">

  <input type="submit" name="logout" id="logout" value="Logout" />
</form>
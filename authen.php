<?php
    session_start();

    $role = '';
    $userLoginId = '';
    if(isset($_SESSION['sess_member_id']) && $_SESSION['sess_member_id'] != "") {
        echo '<h1>Welcome '.$_SESSION['sess_login_name'].'</h1>';
        $role = $_SESSION['sess_role'];
        $userLoginId = $_SESSION['sess_member_id'];
    }
?>
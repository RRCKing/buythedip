<?php
    session_start();

    $role = '';
    if(isset($_SESSION['sess_member_id']) && $_SESSION['sess_member_id'] != "") {
        echo '<h1>Welcome '.$_SESSION['sess_login_name'].', your ID is '.$_SESSION['sess_member_id'].'</h1>';
        $role = $_SESSION['sess_role'];
    }
?>
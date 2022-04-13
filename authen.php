<?php
    session_start();

    $role = '';
    $userLoginId = '';
    $userLoginName = 'Guest.';
    if(isset($_SESSION['sess_member_id']) && $_SESSION['sess_member_id'] != "") {
        $role = $_SESSION['sess_role'];
        $userLoginId = $_SESSION['sess_member_id'];
        $userLoginName = $_SESSION['sess_login_name'];
    }
?>
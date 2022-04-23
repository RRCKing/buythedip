<?php
    require('db_connect.php');
    include('authen.php');


    $msg = ""; 
    if(isset($_POST['submitBtnLogin'])) {
        
        $usernameNotTrim = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $username = trim($usernameNotTrim);

        $password = trim($_POST['password']);

        if($username != "" && $password != "") {
            try {
                $query = "SELECT * FROM members WHERE Login_Name=:login_name";
                $stmt = $db->prepare($query);
                $stmt->bindParam('login_name', $username, PDO::PARAM_STR);
                $stmt->execute();
                $count = $stmt->rowCount();
                $row   = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($count == 1 && !empty($row) && password_verify($_POST['password'], $row['Password'])) {
                        
                        $_SESSION['sess_member_id'] = $row['Member_ID'];
                        $_SESSION['sess_login_name'] = $row['Login_Name'];
                        $_SESSION['sess_role'] = $row['Role'];

                        if ($_SESSION['sess_role_id'] == 9){
                            header("Location: post_summary.php"); 
                        }else{
                            header("Location: index.php"); 
                        }
                        
                    
                    } else {
                        $msg = "Invalid username and password!";
                    }
                } catch (PDOException $e) {
                echo "Error : ".$e->getMessage();
                }
        } else {
            $msg = "Both fields are required!";
        }
    }

    if(isset($_POST['logout'])) {

        $_SESSION['sess_member_id']   = '';
        $_SESSION['sess_login_name'] = '';
        $_SESSION['sess_role'] = '';
        session_destroy();
        if(empty($_SESSION['sess_member_id'])) {
            header("location: index.php");
        }

    }

    // show user info if they login
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Product Add</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <div id="top_section">
        <?php include('nav.php')?>
        <div id="search_bar">
            <?php include('search_bar.php')?>
        </div>
    </div>
    <div id="main_section">
        <form method="post">
        <table>
            <tr>
            <th>LOGIN</th>
            </tr>
            <tr>
            <td>
                <label>Username:</label>
                <input type="text" name="username" id="username" value="" autocomplete="off" />
            </td>
            </tr>
            <tr>
            <td><label>Password:</label>
                <input type="password" name="password" id="password" value="" autocomplete="off" /></td>
            </tr>
            <tr>
            <td>
                <input type="submit" name="submitBtnLogin" id="submitBtnLogin" value="Login" />
                <span class="loginMsg"><?php echo @$msg;?></span>
            </td>
            </tr>
        </table>
        </form>
    </div>    
</body>
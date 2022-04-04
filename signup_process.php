<?php
    require('db_connect.php');
    include('authen.php');
    
    $msg = "";
    

    if ($_POST && !empty($_POST['login_name']) && !empty($_POST['password'] && !empty($_POST['password2']))) {

        // Santitize user input to escape HTML entitles and filter out dangerous characters.
        $loginName = filter_input(INPUT_POST, 'login_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $area = filter_input(INPUT_POST, 'area', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Check password and password2 are matching. If password not match, stop process and show message.
        if ($password != $password2){
            $msg = 'Password not match, try again <a href="signup.php">Go back to signup</a>';
        }else{

            // Check any same login name
            $querySameUser = "SELECT * FROM members WHERE Login_Name = ?";
            $statementSameUser = $db->prepare($querySameUser);
            $statementSameUser->execute([$loginName]);

            $sameUser = $statementSameUser->fetch();

            // if register the same name, stop process and show message
            if ($sameUser){
                $msg = 'the login name is used by other users, please try again. <a href="signup.php">Go back to signup</a>';
            }else{
                // hash the password before store to the database.
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                // Build the parameterized SQL query and bind to the above sanitized values.
                $insert = "INSERT INTO members (Login_Name, Password, Email, Street, Area, Bonus, Role) 
                VALUES (:login_name, :password, :email, :street, :area, 0, 'member')";
                $statement = $db->prepare($insert);

                // Build values to the parameters
                //$statement->bindParm(':')
                $statement->bindvalue(':login_name', $loginName);
                $statement->bindvalue(':password', $passwordHash);
                $statement->bindvalue(':email', $email);
                $statement->bindvalue(':street', $street);
                $statement->bindvalue(':area', $area);

                // Do the login process for the register.
                if($statement->execute()){
                    try {
                        $query = "SELECT * FROM members WHERE Login_Name=:login_name LIMIT 1";
                        $stmt = $db->prepare($query);
                        $stmt->bindParam('login_name', $loginName, PDO::PARAM_STR);
                        $stmt->execute();
                        $count = $stmt->rowCount();
                        $row   = $stmt->fetch(PDO::FETCH_ASSOC);
                                
                        $_SESSION['sess_member_id']   = $row['Member_ID'];
                        $_SESSION['sess_login_name'] = $row['Login_Name'];
                        $_SESSION['sess_role'] = $row['Role'];

                        header("Location: index.php"); 

                        } catch (PDOException $e) {
                            echo "Error : ".$e->getMessage();
                        }
                }
            }

            
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
    <p><?=$msg?></P>

</body>
</html>
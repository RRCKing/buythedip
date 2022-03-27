<?php
    require('db_connect.php');
    include('authen.php');

    include('nav.php');

    // Only admin role can access this page
    if($role == "admin"){

        if (isset($_GET['member_id']) && is_numeric($_GET['member_id'])){
        
            // SQL is written as a String .
            $queryMembers = "SELECT * FROM members WHERE Member_ID = :id LIMIT 1";
            $statementMembers = $db->prepare($queryMembers);

            // Sanitize $_GET['id'] to ensure it's a number.
            $id = filter_input(INPUT_GET, 'member_id', FILTER_SANITIZE_NUMBER_INT);
            $statementMembers->bindValue('id', $id);

            $statementMembers->execute();

            $rowMember = $statementMembers->fetch();
        }

    }else{
        // Redirect if not admin
        //header("Location: index.php");       
        //exit;
        
        echo '<h1>Admin only!</h1>';
        exit;
    } 

    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Post Details</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <?php if ($id): ?>
        <form method="post" action="member_process.php">
            <!-- Hidden input for the primary key. -->
            <input type="hidden" name="member_id" value="<?= $rowMember['Member_ID'] ?>">

            <ul>
                <li>
                <!-- Quote author and content are echoed into the input value attibutes. -->
                    <label for="login_name">Login Name</label>
                    <input id="login_name" name="login_name" value=<?=$rowMember['Login_Name']?>>
                </li>
                <li>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" >
                </li>
                <li>
                    <label for="email">Email:</label>
                    <input type="email" name="email" pattern=".+@buythedip\.com" size="30" value="<?=$rowMember['Email']?>" required>
                </li>
                <li>
                    <label for="street">Street</label>
                    <input id="street" name="street" value=<?=$rowMember['Street']?>>
                </li>
                <li>
                    <label for="area">Area</label>
                    <input id="area" name="area" value=<?=$rowMember['Area']?>>
                </li>
                <li>
                    <label for="bonus">Bonus</label>
                    <input id="bonus" name="bonus" value=<?=$rowMember['Bonus']?>>
                </li>
                <li>
                    <label for="role">Role</label>
                    <input id="role" name="role" value=<?=$rowMember['Role']?>>
                </li>
                <li>
                    <input type="submit" name="command" value="Edit" />
                    <input type="submit" name="command" value="Delete" 
                        onclick="return confirm('Are you sure you wish to delete this member?')" />
                </li>
            </ul>
        </form>
    <?php endif ?>
</body>
</html>
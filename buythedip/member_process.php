<?php
    require('db_connect.php');

    if (isset($_POST['command']) && $_POST['command'] == 'Add'){

        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $loginName = filter_input(INPUT_POST, 'login_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $memberId = filter_input(INPUT_POST, 'member_id', FILTER_SANITIZE_NUMBER_INT);
        $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $area = filter_input(INPUT_POST, 'area', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $bonus = filter_input(INPUT_POST, 'bonus', FILTER_SANITIZE_NUMBER_INT);
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Build the parameterized SQL query and bind to the above sanitized values.
        $queryEdit = "INSERT INTO members (Member_ID, Login_Name, Email, Password, Street, Area, Bonus, Role) VALUES (:member_id, :login_name, :email, :password, :street, :area, :bonus, :role)";
        $statementEdit = $db->prepare($queryEdit);

        $statementEdit->bindValue('login_name', $loginName);
        $statementEdit->bindValue('password', $passwordHash);
        $statementEdit->bindValue('email', $email);
        $statementEdit->bindValue('street', $street);
        $statementEdit->bindValue('area', $area);
        $statementEdit->bindValue('bonus', $bonus);
        $statementEdit->bindValue('role', $role);
        $statementEdit->bindValue('member_id', $memberId, PDO::PARAM_INT);

        // Execute the INSERT.
        $statementEdit->execute();

        // Redirect after edit.
        header("Location: members.php");
        exit;

    }elseif (isset($_POST['command']) && $_POST['command'] == 'Delete' && isset($_POST['member_id']) && is_numeric($_POST['member_id'])) {

    	//  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $memberId = filter_input(INPUT_POST, 'member_id', FILTER_SANITIZE_NUMBER_INT);

	    //  Build the parameterized SQL query and bind to the above sanitized values.
        $query = "DELETE FROM members WHERE Member_ID = :member_id LIMIT 1";
        $statement = $db->prepare($query);
        $statement->bindValue('member_id', $memberId, PDO::PARAM_INT);

        // Execute the Delete.
        $statement->execute();

        // Redirect after Delete.
        header("Location: members.php");       
        exit;    

    }elseif (isset($_POST['command']) && $_POST['command'] == 'Edit' && isset($_POST['member_id']) && is_numeric($_POST['member_id']) && isset($_POST['password'])) {    	

        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $loginName = filter_input(INPUT_POST, 'login_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $memberId = filter_input(INPUT_POST, 'member_id', FILTER_SANITIZE_NUMBER_INT);
        $street = filter_input(INPUT_POST, 'street', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $area = filter_input(INPUT_POST, 'area', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $bonus = filter_input(INPUT_POST, 'bonus', FILTER_SANITIZE_NUMBER_INT);
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Build the parameterized SQL query and bind to the above sanitized values.
        $queryEdit = "UPDATE members SET Login_Name = :login_name, Email = :email, Password = :password, Street = :street, Area = :area, Bonus = :bonus, Role = :role WHERE Member_ID = :member_id";
        $statementEdit = $db->prepare($queryEdit);

        $statementEdit->bindValue('login_name', $loginName);
        $statementEdit->bindValue('password', $passwordHash);
        $statementEdit->bindValue('email', $email);
        $statementEdit->bindValue('street', $street);
        $statementEdit->bindValue('area', $area);
        $statementEdit->bindValue('bonus', $bonus);
        $statementEdit->bindValue('role', $role);
        $statementEdit->bindValue('member_id', $memberId, PDO::PARAM_INT);

        // Execute the INSERT.
        $statementEdit->execute();

        // Redirect after edit.
        header("Location: members.php");
        exit;
    	    
        
    }elseif (isset($_POST['id']) && !is_numeric($_POST['id'])){

            header("Location: index.php");
            exit;
                
    }else{
             $id = false; // False if we are not UPDATING or SELECTING.
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Messages</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
    <div id="wrapper">
        <?php if (empty($_POST['title']) || empty($_POST['content'])): ?>
		<h1>An error occured while processing your post.</h1>
		  <p>
		    Both the title and content must be at least one character.  </p>
		<a href="index.php">Return Home</a>
    	<?php endif ?>
    </div> <!-- END div id="wrapper" -->
</body>
</html>
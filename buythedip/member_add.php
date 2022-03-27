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

    if ($_POST && !empty($_POST['title']) && !empty($_POST['content'])) {
        // Santitize user input to escape HTML entitles and filter out dangerous characters.

        $productId = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $memberId = filter_input(INPUT_POST, 'member_id', FILTER_SANITIZE_NUMBER_INT);
        $storeId = filter_input(INPUT_POST, 'store_id', FILTER_SANITIZE_NUMBER_INT);

        // Build the parameterized SQL query and bind to the above sanitized values.
        $addPost = "INSERT INTO posts (Product_ID, Title, Content, Member_ID, Store_ID) VALUES (:product_id, :title, :content, :member_id, :store_id)";
        $postStatement = $db->prepare($addPost);
        
        // Build values to the parameters
        //$statement->bindParm(':')
        $postStatement->bindvalue(':product_id', $productId);
        $postStatement->bindvalue(':title', $title);
        $postStatement->bindvalue(':content', $content);
        $postStatement->bindvalue(':member_id', $memberId);
        $postStatement->bindvalue(':store_id', $storeId);


        // Execute the INSERT.
        // execute() will check for possible SQL injection and remove if necessary
        if($postStatement->execute()){
            header("Location: index.php"); 

        }
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
    <form method="post" action="member_process.php">
        <ul>
            <li>
                <label for="login_name">Login Name</label>
                <input id="login_name" name="login_name">
            </li>
            <li>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" >
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
                <label for="bonus">Bonus</label>
                <input id="bonus" name="bonus">
            </li>
            <li>
                <label for="role">Role</label>
                <input id="role" name="role">
            </li>
            <li>
                <input type="submit" name="command" value="Add" />
            </li>
        <ul>
    </form>
</body>
</html>
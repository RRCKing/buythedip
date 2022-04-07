<?php
    require('db_connect.php');
    include('authen.php');
    
    // Only logged in can access this page, otherwise redirect to index.php.
    $sessMemberId = "";
    if (!isset($_SESSION['sess_role'])){
        header("Location: index.php"); 
        exit;
    }else{
        $sessMemberId = $_SESSION['sess_member_id'];
    }
    
    if (isset($_POST['command']) && $_POST['command'] == 'Edit' && isset($_POST['category_id']) && is_numeric($_POST['category_id']) && isset($_POST['category_name']) && !empty($_POST['category_name'])) {    	

        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $categoryId = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
        $categoryName = filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Build the parameterized SQL query and bind to the above sanitized values.
        $queryEdit = "UPDATE categories SET Category_Name = :category_name  
                        WHERE Category_ID = :category_id";

        $statementEdit = $db->prepare($queryEdit);
        $statementEdit->bindValue('category_id', $categoryId);        
        $statementEdit->bindValue(':category_name', $categoryName);

        // Execute the INSERT.
        $statementEdit->execute();

        // Redirect after edit.
        header("Location: category_edit.php");
        exit;
    	    
        
    }else{
             echo '<p>Something Wrong</p>';
    }
?>
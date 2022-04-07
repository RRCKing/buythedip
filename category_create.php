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

    if ($_POST && !empty($_POST['category_name'])) {
        
        // Santitize input
        //$categoryId = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
       // $nextCategoryId = $categoryId + 1;
        $categoryName = filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Add Category query
        $addCategory = "INSERT INTO categories (Category_Name) 
                    VALUES (:category_name)";
        $categoryStatement = $db->prepare($addCategory);
        
        // Build values to the parameters
        //$categoryStatement->bindvalue(':category_id', $nextCategoryId);
        $categoryStatement->bindvalue(':category_name', $categoryName);

        if($categoryStatement->execute()){
            
            header("Location: post_create.php");
        }        
    }    
?>
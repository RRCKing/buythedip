<?php
    require('db_connect.php');
    include('authen.php');

    // Only logged in can access this page, otherwise redirect to index.php.
    $sessMemberId = "";
    if (!isset($_SESSION['sess_role'])){
        header("Location: index.php"); 
        exit;
    }

    if ($_POST && !empty($_POST['category_name'])) {
        
        // Santitize input
        //$categoryId = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
       // $nextCategoryId = $categoryId + 1;
        $categoryName = filter_input(INPUT_POST, 'category_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


        // Check any same category name
        $querySameCat = "SELECT * FROM categories WHERE Category_Name = ?";
        $statementSameCat = $db->prepare($querySameCat);
        $statementSameCat->execute([$categoryName]);

        $sameCat = $statementSameCat->fetch();

        // if register the same name, stop process and show message
        if ($sameCat){
            echo 'The category name is duplicated, please try again. <a href="post_create.php">Go back to create post</a>';
            exit;
        }

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
    
    if($_POST && empty($_POST['category_name'])){
        header("Location: post_create.php");
    }else{
        header("Location: post_create.php");
    }
?>
<?php
    require('db_connect.php');
    include('authen.php');
    
    // Only admin role can access this page
    if($role != "admin"){

        // Redirect if not admin
        //header("Location: index.php");       
        //exit;
        
        echo '<h1>Admin only!</h1>';
        exit;

    }
    
    if (isset($_POST['command']) && $_POST['command'] == 'Delete' && isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {

    	//  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $productId = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);

	    //  Build the parameterized SQL query and bind to the above sanitized values.
        $query = "DELETE FROM products WHERE Product_ID = :product_id LIMIT 1";
        $statement = $db->prepare($query);
        $statement->bindValue('product_id', $productId, PDO::PARAM_INT);

        // Execute the Delete.
        $statement->execute();

        // Redirect after Delete.
        header("Location: product_list.php");       
        exit;    

    }elseif (isset($_POST['command']) && $_POST['command'] == 'Edit' && isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {    	

        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $productId = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
        $categoryId = filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT);
        $productDesc = filter_input(INPUT_POST, 'product_desc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (isset($_POST['img_link'])){
            $imgDel = "UPDATE products SET Img_Link = '' WHERE product_ID = :product_id";
            $statementImgDel = $db->prepare($imgDel);
            $statementImgDel->bindValue('product_id', $productId);
            $statementImgDel->execute();

            $imgPath = $_POST['img_link'];

            rename($imgPath, 'del'.DIRECTORY_SEPARATOR.$imgPath);
        }

        if (isset($_POST['img_link400'])){
            $imgDel = "UPDATE products SET Img_Link400 = '' WHERE product_ID = :product_id";
            $statementImgDel = $db->prepare($imgDel);
            $statementImgDel->bindValue('product_id', $productId);
            $statementImgDel->execute();

            $imgPath = $_POST['img_link400'];

            rename($imgPath, 'del'.DIRECTORY_SEPARATOR.$imgPath);
        }

        if (isset($_POST['img_link50'])){
            $imgDel = "UPDATE products SET Img_Link50 = '' WHERE product_ID = :product_id";
            $statementImgDel = $db->prepare($imgDel);
            $statementImgDel->bindValue('product_id', $productId);
            $statementImgDel->execute();

            $imgPath = $_POST['img_link50'];

            rename($imgPath, 'del'.DIRECTORY_SEPARATOR.$imgPath);
        }

        // Build the parameterized SQL query and bind to the above sanitized values.
        $queryEdit = "UPDATE products SET Product_Desc = :product_desc,  
                        Category_ID = :category_id WHERE Product_ID = :product_id";

        $statementEdit = $db->prepare($queryEdit);
        $statementEdit->bindValue('product_id', $productId);        
        $statementEdit->bindValue('product_desc', $productDesc);
        $statementEdit->bindValue('category_id', $categoryId);

        // Execute the INSERT.
        $statementEdit->execute();

        // Redirect after edit.
        header("Location: product_list.php?id={$productId}");
        exit;
    	    
        
    }elseif (isset($_POST['product_id']) && !is_numeric($_POST['product_id'])){

            header("Location: product_list.php");
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
        <p>Error.</p>
    </div>
</body>
</html>
<?php
    require('db_connect.php');
    include('authen.php');

    // Only logged in can access this page, otherwise redirect to index.php.
    if (!isset($_SESSION['sess_role'])){
        header("Location: index.php"); 
        exit;
    } 
    
    if (isset($_POST['command']) && $_POST['command'] == 'Delete' && isset($_POST['post_id']) && is_numeric($_POST['post_id'])) {

    	//  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $postId = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);

	    //  Build the parameterized SQL query and bind to the above sanitized values.
        $query = "DELETE FROM posts WHERE Post_ID = :post_id LIMIT 1";
        $statement = $db->prepare($query);
        $statement->bindValue('post_id', $postId, PDO::PARAM_INT);

        // Execute the Delete.
        $statement->execute();

        // Redirect after Delete.
        header("Location: index.php");       
        exit;    

    }elseif (isset($_POST['command']) && $_POST['command'] == 'Edit' 
                && isset($_POST['title']) && isset($_POST['content']) && !empty(trim($_POST['title'])) 
                && !empty(trim($_POST['content'])) && !empty(trim($_POST['price']))
                && isset($_POST['post_id']) && is_numeric($_POST['post_id'])) {    	

        // Sanitize user input to escape HTML entities and filter out dangerous characters.
        $postId = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $productId = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $memberId = filter_input(INPUT_POST, 'member_id', FILTER_SANITIZE_NUMBER_INT);
        $storeId = filter_input(INPUT_POST, 'store_id', FILTER_SANITIZE_NUMBER_INT);

        // Build the parameterized SQL query and bind to the above sanitized values.
        $queryEdit = "UPDATE posts SET Title = :title, Content = :content,  
                        Product_ID = :product_id, Price = :price, Member_ID = :member_id, 
                        Store_ID = :store_id WHERE Post_ID = :post_id";

        $statementEdit = $db->prepare($queryEdit);
        $statementEdit->bindValue('post_id', $postId);
        $statementEdit->bindValue('title', $title);
        $statementEdit->bindValue('content', $content);
        $statementEdit->bindValue('product_id', $productId);
        $statementEdit->bindValue('price', $price);
        $statementEdit->bindValue('member_id', $memberId);
        $statementEdit->bindValue('store_id', $storeId);

        // Execute the INSERT.
        $statementEdit->execute();

        // Redirect after edit.
        header("Location: post_detail.php?post_id={$postId}");
        echo $_POST['title'];
        exit;
    	    
        
    }elseif (isset($_POST['post_id'])){

            echo '<h1>Error! Go back to <a href="index.php">Home Page</a></h1>';

            //header("Location: index.php");
            if (!is_numeric($_POST['post_id'])){
                echo '<p>Post ID is not numeric.</p>';
            }

            if (empty(trim($_POST['title']))){
                echo '<p>Title cannot be empty.</p>';
            }

            if (empty(trim($_POST['content']))){
                echo "<p>Content cannot be empty.</p>";
            }

            if (empty(trim($_POST['price']))){
                echo '<p>Content cannot be empty.</p>';
            }elseif(!is_numeric($_POST['price'])){
                echo '<p>Price is not valid.</p>';
            }

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
    </div>
</body>
</html>
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

    if ($_POST && !empty($_POST['title']) && !empty($_POST['content']) && !empty($_POST['price'])) {
        
        if (!is_numeric($_POST['price'])){
            echo "Price is not valid.\n";
            echo "<a href='post_create.php'>Try create again</a>";
            exit;
        }

        // Santitize user input to escape HTML entitles and filter out dangerous characters.
        $productId = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
        $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $memberId = filter_input(INPUT_POST, 'member_id', FILTER_SANITIZE_NUMBER_INT);
        $storeId = filter_input(INPUT_POST, 'store_id', FILTER_SANITIZE_NUMBER_INT);

        // Build the parameterized SQL query and bind to the above sanitized values.
        $addPost = "INSERT INTO posts (Product_ID, Price, Title, Content, Member_ID, Store_ID) 
                    VALUES (:product_id, :price, :title, :content, :member_id, :store_id)";
        $postStatement = $db->prepare($addPost);
        
        // Build values to the parameters
        //$statement->bindParm(':')
        $postStatement->bindvalue(':product_id', $productId);
        $postStatement->bindvalue(':price', $price);
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

    // Get the Product selection
    $queryProducts = "SELECT * FROM Products";
    $stmtProducts = $db->prepare($queryProducts);
    $stmtProducts->execute();

    // Get the Store selection
    $queryStores = "SELECT * FROM Stores";
    $stmtStores = $db->prepare($queryStores);
    $stmtStores->execute();

    // Get the category selection
    $queryCategories = "SELECT * FROM Categories";
    $stmtCategories = $db->prepare($queryCategories);
    $stmtCategories->execute();
    $rowCategory = $stmtCategories -> fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Post</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <?php include('nav.php')?>
    <!--Create the Category if it is new -->
    <form method="post" action="category_create.php">
        <legend>Create Category</legend>
        
        <ul>
            <li>
                <label for="category_name">Category Name</label>
                <input id="category_name" name="category_name">
            </li>
            <li>
                <input type="submit" name="command" value="submit" />
            </li>
        </ul>
    </form>
    <!-- Create the Product if it is new -->
    <form method="post" action="product_create.php" enctype='multipart/form-data'>
        <legend>Create Product</legend>
        <ul>
            <li>
                <label for="product_desc">Product Description</label>
                <input id="product_desc" name="product_desc">
            </li>
            <li>
                <label for="category_id">Choose a category:</label>
                <select name="category_id" id="category_id">
                    <?php while($row = $stmtCategories->fetch()): ?>
                        <option value="<?=$row['Category_ID']?>">
                            <?=$row['Category_Name']?>
                        </option>
                    <?php endwhile ?>
                </select>
            </li>
            <li>
                <label for='image'>Image Filename:</label>
                <input type='file' name='image' id='image'>
            </li>
            <li>
                <input type="submit" name="command" value="submit" />
            </li>
        </ul>
    </form>
    <!-- Create the Post -->
    <form method="post" action="post_create.php">
        <legend>Create Post</legend>
        <input type="hidden" name="member_id" value="<?= $sessMemberId ?>">
        <ul>
            <li>
                <label for="title">Title</label>
                <input id="title" name="title">
            </li>
            <li>
                <label for="content">Content</label>
                <textarea name="content" id="content"></textarea>
            </li>
            <li>
                <label for="store_id">Choose a store:</label>
                <select name="store_id" id="store_id">
                    <?php while($row = $stmtStores->fetch()): ?>
                        <option value="<?=$row['Store_ID']?>">
                            <?=$row['Store_Name']?>
                        </option>
                    <?php endwhile ?>
                </select>
            </li>
            <li>        
                <label for="product_id">Choose a product:</label>
                <select name="product_id" id="product_id">
                    <?php while($row = $stmtProducts->fetch()): ?>
                        <option value="<?=$row['Product_ID']?>">
                            <?=$row['Product_Desc']?>
                        </option>
                    <?php endwhile ?>
                </select>
            </li>
            <li>
                <label for="price">Price</label>
                <input id="price" name="price">
            </li>        
            <li>
                <input type="submit" name="command" value="submit" />
            </li>
        </ul>
    </form>
</body>
</html>
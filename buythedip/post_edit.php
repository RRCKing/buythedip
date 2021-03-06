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

    if (isset($_GET['post_id']) && is_numeric($_GET['post_id'])){
        
        //Build and prepare SQL String with :id placeholder parameter.
        $queryPost = "SELECT * FROM posts WHERE Post_ID = :post_id LIMIT 1";
        $statementPost = $db->prepare($queryPost);

        // Sanitize $_GET['id'] to ensure it's a number.
        $postId = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
        // Bind the :id parameter in the query to the sanitized
        // $id specifying a binding-type of Integer.
        $statementPost->bindValue('post_id', $postId);
        $statementPost->execute();

        // Fetch the new row selected by primary key id.
        $rowPost = $statementPost->fetch();

        $queryProduct = "SELECT * FROM products pr JOIN posts po ON pr.Product_ID = po.Product_ID WHERE po.Post_ID = :post_id LIMIT 1";

        $statementProduct = $db->prepare($queryProduct);
        $statementProduct->bindValue('post_id', $postId);
        $statementProduct->execute();

        // Fetch the new row selected by primary key id.
        $rowProduct = $statementProduct->fetch();

        //$img = '<img src="data:image/jpeg;base64,'.base64_encode($rowProduct['Images']).'"/>';
    }

    // Get the Product selection
    $queryProducts = "SELECT * FROM Products";
    $stmtProducts = $db->prepare($queryProducts);
    $stmtProducts->execute();

    // Get the Store selection
    $queryStores = "SELECT * FROM Stores";
    $stmtStores = $db->prepare($queryStores);
    $stmtStores->execute();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Post Edit</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <?php include('nav.php')?>
    <?php if ($postId): ?>
        <form method="post" action="post_process.php">
            <legend>Post Edit</legend>
            <!-- Hidden Post ID. -->
            <input type="hidden" name="post_id" value="<?= $rowPost['Post_ID'] ?>"> 
            <input type="hidden" name="member_id" value="<?= $rowPost['Member_ID'] ?>">      
            <ul>
                <li>
                    <label for="title">Title</label>
                    <input id="title" name="title" value="<?= $rowPost['Title'] ?>">
                </li>
                <li>
                    <label for="content">Content</label>
                    <textarea name="content" id="content"><?= $rowPost['Content']?></textarea>
                </li>
                <li>        
                    <label for="product_id">Choose a product:</label>
                    <select name="product_id" id="product_id">
                        <?php while($row = $stmtProducts->fetch()): ?>
                            <?php if($rowProduct['Product_ID'] == $row['Product_ID']): ?>
                                <option value="<?=$row['Product_ID']?>" selected="selected">
                                    <?=$row['Product_Desc']?>
                                </option>
                            <?php else: ?>
                                <option value="<?=$row['Product_ID']?>">
                                    <?=$row['Product_Desc']?>
                                </option>
                            <?php endif ?>
                        <?php endwhile ?>
                    </select>
                </li>
                <li>
                    <label for="price">Price</label>
                    <input id="price" name="price" value="<?=$rowProduct['Price']?>">
                </li>
                <li>
                    <label for="store_id">Choose a store:</label>
                    <select name="store_id" id="store_id">
                        <?php while($row = $stmtStores->fetch()): ?>
                            <?php if($rowProduct['Store_ID'] == $row['Store_ID']): ?>
                                <option value="<?=$row['Store_ID']?>" selected="selected">
                                    <?=$row['Store_Name']?>
                                </option>
                            <?php else: ?>
                                <option value="<?=$row['Store_ID']?>">
                                    <?=$row['Store_Name']?>
                                </option>
                            <?php endif ?>
                        <?php endwhile ?>
                    </select>
                </li>
                <li>
                    <input type="submit" name="command" value="Edit" />
                    <input type="submit" name="command" value="Delete" 
                        onclick="return confirm('Are you sure you wish to delete this post?')" />
                </li>
            </ul>
        </form>
    <?php endif ?>
</body>
</html>
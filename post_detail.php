<?php
    require('db_connect.php');
    include('authen.php');

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

    $queryAll = "SELECT * FROM posts po JOIN products pr ON pr.Product_ID = po.Product_ID 
                    JOIN stores s ON s.Store_ID = po.Store_ID 
                    JOIN members m ON m.Member_ID = po.Member_ID
                    WHERE po.Post_ID = :post_id LIMIT 1";
    $statementAll = $db->prepare($queryAll);
    $statementAll->bindValue('post_id', $postId);
    $statementAll->execute();

    $rowAll = $statementAll->fetch();

    $queryComment = "SELECT * FROM comments c 
                        JOIN posts po ON po.Post_ID = c.Post_ID
                        JOIN members m ON m.Member_ID = c.Member_ID 
                        WHERE po.Post_ID = :post_id";
    
    $statementComment = $db->prepare($queryComment); 
    $statementComment->bindValue('post_id', $postId);
    $statementComment->execute();

    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Post Details</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <?php include('nav.php')?>
    <h1>Rows Found: <?= $statementPost->rowCount() ?></h1>
    <h2>Post ID</h2>
    <p><?= $rowPost['Post_ID']?></p>
    <h2>Title</h2>
    <p><?= $rowPost['Title']?></p>
    <h2>Content</h2>
    <p><?= $rowPost['Content']?></p>
    <h2>Product ID</h2>
    <p><?= $rowPost['Product_ID']?></p>
    <h2>Product Desc</h2>
    <p><?= $rowProduct['Product_Desc']?></p>
    <h2>Product Price</h2>
    <p><?= $rowProduct['Price']?></p>
    <h2>Category ID</h2>
    <p><?= $rowProduct['Category_ID']?></p>
    <h2>Category Name</h2>
    <p><?= $rowProduct['Category_ID']?></p>
    <h2>Store</h2>
    <p><?= $rowAll['Store_Name']?></p>
    <h2>Store ID</h2>
    <p><?= $rowAll['Store_ID']?></p>
    <h2>Member</h2>
    <p><?= $rowAll['Login_Name']?></p>
    <!--only when the product has images-->
    <?php if (!empty($rowProduct['Img_Link'])):?>
    <h2>Image</h2>
    <img src=<?=$rowProduct['Img_Link']?> alt="" />
    <h2>Image400</h2>
    <img src=<?=$rowProduct['Img_Link400']?> alt="" />
    <h2>Image50</h2>
    <img src=<?=$rowProduct['Img_Link50']?> alt="" />
    <?php endif ?>
    
    <?php if ($role == 'member' || $role == 'admin'): ?>
        <form method="post" action="comment_process.php">
        <input type="hidden" name="post_id" value="<?= $rowAll['Post_ID']?>">
        <input type="hidden" name="member_id" value="<?= $rowAll['Member_ID']?>">
        <ul>
            <li>
                <p>Mem <?= $rowAll['Member_ID'] ?> and Post <?= $rowAll['Post_ID']?></p>
                <label for="comment">Comment</label>
                <textarea name="comment" id="comment"></textarea>
            </li>        
            <li>
                <input type="submit" name="command" value="submit" />
            </li>
        </ul>
        </form>
    <?php endif ?>
    <ol>
        <?php while($rowComment = $statementComment->fetch()): ?>
            <li>
                <p><?= $rowComment['Comment_Time'] ?> by <?= $rowComment['Login_Name'] ?></p>
                <p><?= $rowComment['Comment'] ?></p>            	
            </li>
        <?php endwhile ?>
    </ol>
</body>
</html>
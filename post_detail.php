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
                        WHERE po.Post_ID = :post_id
                        ORDER BY c.Comment_Time DESC";
    
    $statementComment = $db->prepare($queryComment); 
    $statementComment->bindValue('post_id', $postId);
    $statementComment->execute();

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Post Details</title>
    <link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
    <div id="top_section">
        <?php include('nav.php')?>
        <div id="search_bar">
            <?php include('search_bar.php')?>
        </div>
    </div>
    <div id="main_section">
        <div id="post_body">
        <p><?= $rowAll['Login_Name']?> posted by <?=$rowAll['Timestamp']?> </p>
        <h2><?= $rowAll['Title']?></h2>
        <p><?= $rowAll['Content']?></p>
        <p>Product: <?= $rowProduct['Product_Desc']?> from <span id="store_name"><?= $rowAll['Store_Name']?></span></p>
        <p>Price: <?= $rowAll['Price']?></p>
        <!--only when the product has images-->
        <?php if (!empty($rowProduct['Img_Link'])):?>
        <img src=<?=$rowProduct['Img_Link400']?> alt="product_400" />
        <?php endif ?>
        <?php if ($rowAll['Member_ID'] == $userLoginId): ?>
            <p><a href="post_edit.php?post_id=<?= $rowAll['Post_ID'] ?>" target="_blank">Edit</a></p>  
        <?php endif ?>
        </div> 
        
        <?php if ($role == 'member' || $role == 'admin'): ?>
            <div id="comment_box">
            
                <form method="post" action="comment_process.php">
                <input type="hidden" name="post_id" value="<?= $rowAll['Post_ID']?>">
                <input type="hidden" name="member_id" value="<?= $userLoginId?>">
                <ul>
                    <li>
                        <p><label for="comment">Comment</label></p>
                        <textarea name="comment" id="comment"></textarea>
                    </li>
                    <li>
                        <p><img src="captcha.php" /></p>
                        <input type="text" name="captcha" />
                    </li>            
                    <li>
                        <input type="submit" name="command" value="submit" />                                 
                    </li>
                </ul>
                </form>
            
            </div>
        <?php endif ?>
        <div id="comment_list">
            <ol>
                <?php while($rowComment = $statementComment->fetch()): ?>
                    
                    <li>
                        <p><?= $rowComment['Comment_Time'] ?> by <?= $rowComment['Login_Name'] ?></p>                    
                        <?php if($role == 'admin'): ?>
                        <form method="post" action="comment_process.php">
                            <input type="hidden" name="post_id" value="<?= $rowAll['Post_ID']?>">                
                            <input type="hidden" name="comment_id" value="<?= $rowComment['Comment_ID']?>">
                            <input type="hidden" name="member_id" value="<?= $rowComment['Member_ID']?>">
                            <p><?= $rowComment['Comment'] ?></p>
                            <textarea name="comment" id="comment"><?= $rowComment['Comment'] ?></textarea>
                            <input type="submit" name="command" value="Update" />
                            <input type="submit" name="command" value="Delete" />
                        </form>
                        <?php else: ?>
                            <p><?= $rowComment['Comment'] ?></p>
                        <?php endif ?>              	
                    </li>
                <?php endwhile ?>
            </ol>
        </div>

    </div>
        
</body>
</html>
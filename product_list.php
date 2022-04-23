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

    $query = "SELECT * FROM products";

    $statement = $db->prepare($query);
    
    $statement->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Product List</title>
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
    <h1>Product list: Found <?= $statement->rowCount() ?> Products</h1>
    <div id="home_post_list">
    <ol>        
        <?php while($row = $statement->fetch()): ?>
            <li>
                <?= $row['Product_Desc'] ?> and ID is <a href="product_detail.php?id=<?= $row['Product_ID'] ?>" target="_blank"><?= $row['Product_ID'] ?></a>
                <p><a href="product_edit.php?product_id=<?= $row['Product_ID'] ?>" target="_blank">Edit <?= $row['Product_ID'] ?></a></p>
                <?php if (!empty($row['Img_Link50'])):?>
                 <img src="<?=$row['Img_Link50']?>" alt="product_photo" />
                <?php endif ?>
            </li>
        <?php endwhile ?>
    </ol>
    </div>        
    </div>
    
</body>
</html>
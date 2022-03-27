<?php
    require('db_connect.php');
    include('authen.php');

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
    <?php include('nav.php')?>
    <h1>Product list: Found <?= $statement->rowCount() ?> Products</h1>

    <ol>        
        <?php while($row = $statement->fetch()): ?>
            <li>
            	<?= $row['Product_Desc'] ?> and ID is <a href="product_detail.php?id=<?= $row['Product_ID'] ?>" target="_blank"><?= $row['Product_ID'] ?></a>
                <a href="product_edit.php?product_id=<?= $row['Product_ID'] ?>" target="_blank">Edit <?= $row['Product_ID'] ?></a>
            </li>
        <?php endwhile ?>
    </ol>
</body>
</html>